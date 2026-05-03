<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LedgerEntry extends Model
{
    protected $fillable = [
        'party_id', 'party_type', 'flight_booking_id', 'recharge_request_id',
        'entry_type', 'amount', 'balance_after', 'description', 'reference_no', 'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'balance_after'=> 'decimal:2',
    ];

    public function party()
    {
        return $this->belongsTo(User::class, 'party_id');
    }

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    /**
     * Create a debit entry (money leaves the agent account).
     */
    public static function debit(int $partyId, float $amount, string $description, ?int $bookingId = null, ?string $reference = null, string $partyType = 'b2b'): self
    {
        $user = User::find($partyId);
        $balance = $user ? (float) $user->balance : 0;

        return static::create([
            'party_id'          => $partyId,
            'party_type'        => $partyType,
            'flight_booking_id' => $bookingId,
            'entry_type'        => 'debit',
            'amount'            => $amount,
            'balance_after'     => $balance - $amount,
            'description'       => $description,
            'reference_no'      => $reference,
            'created_by'        => auth()->id(),
        ]);
    }

    /**
     * Create a credit entry (money enters the agent account).
     */
    public static function credit(int $partyId, float $amount, string $description, ?int $bookingId = null, ?string $reference = null, string $partyType = 'b2b'): self
    {
        $user = User::find($partyId);
        $balance = $user ? (float) $user->balance : 0;

        return static::create([
            'party_id'          => $partyId,
            'party_type'        => $partyType,
            'flight_booking_id' => $bookingId,
            'entry_type'        => 'credit',
            'amount'            => $amount,
            'balance_after'     => $balance + $amount,
            'description'       => $description,
            'reference_no'      => $reference,
            'created_by'        => auth()->id(),
        ]);
    }

    /**
     * Summary (total debit, total credit, net balance) for a party.
     */
    public static function summary(int $partyId): array
    {
        $rows = static::where('party_id', $partyId)->selectRaw(
            'entry_type, SUM(amount) as total'
        )->groupBy('entry_type')->pluck('total', 'entry_type')->toArray();

        $totalCredit = (float) ($rows['credit'] ?? 0);
        $totalDebit  = (float) ($rows['debit']  ?? 0);

        return [
            'total_receivable' => $totalDebit,
            'total_paid'       => $totalCredit,
            'net_balance'      => $totalCredit - $totalDebit,
        ];
    }
}
