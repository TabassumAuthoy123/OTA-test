<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method',
        'transaction_id',
        'payment_status',
        'ticketing_response',
        'updated_at'
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    // ─── Relationships ───────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function segments(): HasMany
    {
        return $this->hasMany(FlightSegment::class, 'flight_booking_id');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(FlightPassenger::class, 'flight_booking_id');
    }
}
