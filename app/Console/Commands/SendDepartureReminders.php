<?php

namespace App\Console\Commands;

use App\Helpers\EmailHelper;
use App\Mail\DepartureReminderAgent;
use App\Mail\DepartureReminderPassenger;
use App\Models\FlightNotificationLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendDepartureReminders extends Command
{
    protected $signature   = 'reminders:departure {--dry-run : List bookings without sending}';
    protected $description = 'Send email reminders to passengers and agents for flights departing in ~10 hours';

    public function handle(): int
    {
        $now        = Carbon::now();
        $windowFrom = $now->copy()->addHours(9)->addMinutes(30);
        $windowTo   = $now->copy()->addHours(10)->addMinutes(30);

        $candidates = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('flight_segments as fs', function ($j) {
                $j->on('fs.flight_booking_id', '=', 'fb.id')
                  ->whereRaw('fs.id = (SELECT MIN(s2.id) FROM flight_segments s2 WHERE s2.flight_booking_id = fb.id)');
            })
            ->select(
                'fb.id', 'fb.booking_no', 'fb.pnr_id', 'fb.airlines_pnr',
                'fb.traveller_name', 'fb.traveller_email', 'fb.traveller_contact',
                'fb.departure_date', 'fb.departure_location', 'fb.arrival_location',
                'fb.governing_carriers', 'fb.total_fare',
                'fs.departure_time as seg_departure_time',
                'u.id as agent_id', 'u.name as agent_name', 'u.email as agent_email'
            )
            ->where('fb.status', 2)
            ->whereIn(DB::raw('DATE(fb.departure_date)'), [
                $now->toDateString(),
                $now->copy()->addDay()->toDateString(),
            ])
            ->get();

        $sent = 0;
        $skip = 0;

        foreach ($candidates as $booking) {
            $depDatetime = $this->buildDepartureDatetime($booking->departure_date, $booking->seg_departure_time);

            if (!$depDatetime) {
                $depDatetime = Carbon::parse($booking->departure_date)->setTime(12, 0);
            }

            if ($depDatetime->lt($windowFrom) || $depDatetime->gt($windowTo)) {
                continue;
            }

            $bookingId = $booking->id;

            $passengers = DB::table('flight_passengers')
                ->where('flight_booking_id', $bookingId)
                ->get(['first_name', 'last_name', 'email', 'phone']);

            $passengerNames = $passengers->map(fn($p) => trim($p->first_name . ' ' . $p->last_name))->implode(', ');

            $agentCode = $booking->agent_id ? 'B2B-' . str_pad($booking->agent_id, 3, '0', STR_PAD_LEFT) : null;

            $emailData = [
                'booking_id'         => $bookingId,
                'booking_no'         => $booking->booking_no,
                'pnr'                => $booking->pnr_id,
                'airlines_pnr'       => $booking->airlines_pnr,
                'traveller_name'     => $booking->traveller_name,
                'departure_location' => $booking->departure_location,
                'arrival_location'   => $booking->arrival_location,
                'departure_date'     => $depDatetime->format('d M Y, h:i A'),
                'governing_carriers' => $booking->governing_carriers,
                'contact'            => $booking->traveller_contact,
                'total_fare'         => $booking->total_fare,
                'passenger_names'    => $passengerNames ?: $booking->traveller_name,
                'agent_name'         => $booking->agent_name,
                'agent_email'        => $booking->agent_email,
                'agent_code'         => $agentCode,
            ];

            if ($this->option('dry-run')) {
                $this->line("DRY-RUN: {$booking->booking_no} | dep={$depDatetime->toDateTimeString()} | traveller={$booking->traveller_email} | agent={$booking->agent_email}");
                $sent++;
                continue;
            }

            // ── Passenger email ──────────────────────────────────
            $alreadySentPassenger = FlightNotificationLog::where('flight_booking_id', $bookingId)
                ->where('type', 'passenger_email')
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if (!$alreadySentPassenger && $booking->traveller_email) {
                $ok = EmailHelper::send($booking->traveller_email, new DepartureReminderPassenger($emailData));
                FlightNotificationLog::create([
                    'flight_booking_id' => $bookingId,
                    'type'              => 'passenger_email',
                    'recipient'         => $booking->traveller_email,
                    'status'            => $ok ? 'sent' : 'failed',
                    'error_message'     => $ok ? null : 'Check laravel.log for details',
                ]);
                $ok ? $sent++ : null;
            } else {
                $skip++;
            }

            // ── Agent email ──────────────────────────────────────
            $alreadySentAgent = FlightNotificationLog::where('flight_booking_id', $bookingId)
                ->where('type', 'agent_email')
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if (!$alreadySentAgent && $booking->agent_email) {
                $ok = EmailHelper::send($booking->agent_email, new DepartureReminderAgent($emailData));
                FlightNotificationLog::create([
                    'flight_booking_id' => $bookingId,
                    'type'              => 'agent_email',
                    'recipient'         => $booking->agent_email,
                    'status'            => $ok ? 'sent' : 'failed',
                    'error_message'     => $ok ? null : 'Check laravel.log for details',
                ]);
                $ok ? $sent++ : null;
            } else {
                $skip++;
            }
        }

        $this->info("Departure reminders: {$sent} sent, {$skip} skipped (already sent today).");
        return self::SUCCESS;
    }

    private function buildDepartureDatetime(string $departureDate, ?string $segDepartureTime): ?Carbon
    {
        if (!$segDepartureTime) {
            return null;
        }
        try {
            $timeClean = preg_replace('/[\+\-]\d{2}:\d{2}$/', '', trim($segDepartureTime));
            $timeClean = ltrim($timeClean, 'T');
            return Carbon::parse($departureDate . ' ' . $timeClean);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
