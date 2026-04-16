<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightPassenger extends Model
{
    use HasFactory;

    protected $table = 'flight_passangers';

    // Allow access via both spellings (DB uses 'passanger_type' typo)
    public function getPassengerTypeAttribute(): ?string
    {
        return $this->attributes['passanger_type'] ?? null;
    }

    // ─── Relationships ───────────────────────────────────────

    public function flightBooking(): BelongsTo
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }
}
