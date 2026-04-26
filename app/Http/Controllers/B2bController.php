<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class B2bController extends Controller
{
    // ─── Flight Bookings ────────────────────────────────────────────────────────

    public function flightBookings(Request $request)
    {
        $query = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('company_profiles as cp', 'cp.user_id', '=', 'u.id')
            ->where('u.user_type', 2)
            ->select(
                'fb.id', 'fb.booking_no', 'fb.created_at', 'fb.pnr_id',
                'fb.airlines_pnr', 'fb.flight_type', 'fb.status',
                'fb.total_fare', 'fb.partial_payment',
                'fb.departure_location', 'fb.arrival_location',
                'u.name as booked_by_name',
                DB::raw('COALESCE(cp.name, u.name) as agency_name')
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fb.booking_no', 'like', "%$s%")
                  ->orWhere('fb.pnr_id', 'like', "%$s%")
                  ->orWhere('fb.airlines_pnr', 'like', "%$s%")
                  ->orWhere('u.name', 'like', "%$s%")
                  ->orWhere('cp.name', 'like', "%$s%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('fb.status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('fb.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('fb.created_at', '<=', $request->end_date);
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $bookings = $query->orderBy('fb.created_at', 'desc')->get();
            return $this->exportCsv($bookings, 'b2b_flight_bookings', [
                'booking_no', 'created_at', 'booked_by_name', 'agency_name',
                'pnr_id', 'airlines_pnr', 'flight_type', 'status', 'total_fare', 'partial_payment'
            ]);
        }

        $bookings = $query->orderBy('fb.created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.flight_bookings', compact('bookings'));
    }

    // ─── Tour Bookings ───────────────────────────────────────────────────────────

    public function tourBookings(Request $request)
    {
        $query = DB::table('tour_bookings');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('booking_id', 'like', "%$s%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $bookings = $query->orderBy('created_at', 'desc')->get();
            return $this->exportCsv($bookings, 'tour_bookings', [
                'booking_id', 'name', 'email', 'tour_type', 'travel_date', 'status', 'amount'
            ]);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.tour_bookings', compact('bookings'));
    }

    // ─── Registration Requests ───────────────────────────────────────────────────

    public function registrationRequests(Request $request)
    {
        $query = DB::table('b2b_registration_requests');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('agency_name', 'like', "%$s%")
                  ->orWhere('contact_person', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.registration_requests', compact('requests'));
    }

    public function updateRegistrationRequest(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:0,1,2']);
        DB::table('b2b_registration_requests')->where('id', $id)->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_at' => Carbon::now(),
        ]);
        return back()->with('success', 'Registration request updated.');
    }

    // ─── Partial Pay Bookings ────────────────────────────────────────────────────

    public function partialPayBookings(Request $request)
    {
        $query = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('company_profiles as cp', 'cp.user_id', '=', 'u.id')
            ->where('u.user_type', 2)
            ->where('fb.partial_payment', 1)
            ->select(
                'fb.id', 'fb.booking_no', 'fb.status', 'fb.created_at',
                'fb.departure_date', 'fb.total_fare', 'fb.paid_amount',
                'fb.partial_payment_last_date', 'fb.flight_type',
                DB::raw('COALESCE(cp.name, u.name) as agency_name'),
                DB::raw('GREATEST(0, fb.total_fare - COALESCE(fb.paid_amount, 0)) as due_amount')
            );

        if ($request->filled('start_date')) {
            $query->whereDate('fb.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('fb.created_at', '<=', $request->end_date);
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $bookings = $query->orderBy('fb.created_at', 'desc')->get();
            return $this->exportCsv($bookings, 'partial_pay_bookings', [
                'agency_name', 'flight_type', 'booking_no', 'status',
                'created_at', 'departure_date', 'total_fare', 'paid_amount', 'due_amount', 'partial_payment_last_date'
            ]);
        }

        $bookings = $query->orderBy('fb.created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.partial_pay_bookings', compact('bookings'));
    }

    // ─── Pending Ticket Issuance ─────────────────────────────────────────────────

    public function pendingTicketIssuance(Request $request)
    {
        $query = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('company_profiles as cp', 'cp.user_id', '=', 'u.id')
            ->leftJoin('flight_passengers as fp', function ($join) {
                $join->on('fp.flight_booking_id', '=', 'fb.id')
                     ->where('fp.id', '=', DB::raw('(SELECT MIN(id) FROM flight_passengers WHERE flight_booking_id = fb.id)'));
            })
            ->where('u.user_type', 2)
            ->where('fb.status', 1)
            ->select(
                'fb.id', 'fb.created_at', 'fb.booking_no', 'fb.pnr_id',
                'fb.departure_location', 'fb.arrival_location',
                'fb.adult', 'fb.child', 'fb.infant',
                'fb.total_fare', 'fb.status',
                DB::raw('COALESCE(cp.name, u.name) as agency_name')
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fb.booking_no', 'like', "%$s%")
                  ->orWhere('fb.pnr_id', 'like', "%$s%");
            });
        }
        if ($request->filled('start_date')) {
            $query->whereDate('fb.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('fb.created_at', '<=', $request->end_date);
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $bookings = $query->orderBy('fb.created_at', 'desc')->get();
            return $this->exportCsv($bookings, 'pending_ticket_issuance', [
                'created_at', 'booking_no', 'pnr_id', 'departure_location', 'arrival_location',
                'adult', 'child', 'infant', 'total_fare', 'status'
            ]);
        }

        $bookings = $query->orderBy('fb.created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.pending_ticket_issuance', compact('bookings'));
    }

    // ─── Agency List ─────────────────────────────────────────────────────────────

    public function agencyList(Request $request)
    {
        $query = DB::table('users as u')
            ->leftJoin('company_profiles as cp', 'cp.user_id', '=', 'u.id')
            ->where('u.user_type', 2)
            ->select(
                'u.id', 'u.name', 'u.email', 'u.phone', 'u.status',
                'u.created_at', 'u.balance',
                DB::raw('COALESCE(cp.name, u.name) as agency_name'),
                'cp.logo'
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('u.name', 'like', "%$s%")
                  ->orWhere('u.email', 'like', "%$s%")
                  ->orWhere('cp.name', 'like', "%$s%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('u.status', $request->status);
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $agencies = $query->orderBy('u.created_at', 'desc')->get();
            return $this->exportCsv($agencies, 'agency_list', [
                'id', 'created_at', 'agency_name', 'email', 'phone', 'status', 'balance'
            ]);
        }

        $agencies = $query->orderBy('u.created_at', 'desc')->paginate(15)->withQueryString();
        return view('b2b.agency_list', compact('agencies'));
    }

    public function addMoneyToAgency(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        DB::table('users')->where('id', $userId)->increment('balance', $request->amount);
        DB::table('b2b_account_deductions')->insert([
            'b2b_user_id' => $userId,
            'amount' => $request->amount,
            'details' => 'Credit: ' . ($request->note ?? 'Added via B2B panel'),
            'slug' => uniqid('cr-'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return back()->with('success', 'Balance added successfully.');
    }

    // ─── Upcoming Flights ────────────────────────────────────────────────────────

    public function upcomingFlights(Request $request)
    {
        $query = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('company_profiles as cp', 'cp.user_id', '=', 'u.id')
            ->where('u.user_type', 2)
            ->whereNotIn('fb.status', [3, 4])
            ->where('fb.departure_date', '>=', date('Y-m-d'))
            ->select(
                'fb.id', 'fb.booking_no', 'fb.pnr_id', 'fb.airlines_pnr',
                'fb.departure_location', 'fb.arrival_location',
                'fb.departure_date', 'fb.last_ticket_datetime',
                'fb.flight_type', 'fb.gds', 'fb.adult', 'fb.child', 'fb.infant',
                'fb.status', 'fb.total_fare',
                DB::raw('COALESCE(cp.name, u.name) as agency_name')
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fb.booking_no', 'like', "%$s%")
                  ->orWhere('fb.pnr_id', 'like', "%$s%")
                  ->orWhere('cp.name', 'like', "%$s%");
            });
        }

        if ($request->filled('export') && $request->export === 'excel') {
            $bookings = $query->orderBy('fb.departure_date', 'asc')->get();
            return $this->exportCsv($bookings, 'upcoming_flights', [
                'booking_no', 'agency_name', 'pnr_id', 'airlines_pnr',
                'departure_location', 'arrival_location', 'departure_date',
                'last_ticket_datetime', 'flight_type', 'gds',
                'adult', 'child', 'infant', 'status', 'total_fare'
            ]);
        }

        $bookings = $query->orderBy('fb.departure_date', 'asc')->paginate(15)->withQueryString();
        return view('b2b.upcoming_flights', compact('bookings'));
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    private function exportCsv($rows, string $filename, array $columns)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}_" . date('Y-m-d') . '.csv',
        ];
        $callback = function () use ($rows, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($rows as $row) {
                $row = (array) $row;
                $line = [];
                foreach ($columns as $col) {
                    $line[] = isset($row[$col]) ? $row[$col] : '';
                }
                fputcsv($handle, $line);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    public static function bookingStatusLabel($status)
    {
        $map = [
            0 => 'Booking Request',
            1 => 'Booked',
            2 => 'Ticket Issued',
            3 => 'Booking Cancelled',
            4 => 'Ticket Cancelled',
        ];
        return isset($map[(int)$status]) ? $map[(int)$status] : 'Unknown';
    }

    public static function journeyTypeLabel($type)
    {
        $map = [1 => 'One Way', 2 => 'Round Trip', 3 => 'Multi City'];
        return isset($map[(int)$type]) ? $map[(int)$type] : 'N/A';
    }
}
