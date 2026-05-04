<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightNotificationLog extends Model
{
    protected $fillable = [
        'flight_booking_id', 'type', 'recipient', 'status', 'error_message',
    ];

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }
}
