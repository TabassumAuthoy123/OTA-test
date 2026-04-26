<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightPassenger extends Model
{
    use HasFactory;

    protected $table = 'flight_passengers';

    // ─── Relationships ───────────────────────────────────────

    public function flightBooking(): BelongsTo
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }
}
