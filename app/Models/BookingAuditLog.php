<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAuditLog extends Model
{
    protected $fillable = [
        'flight_booking_id',
        'user_id',
        'action',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    public static function logAction($flightBookingId, $action, $description = null)
    {
        return self::create([
            'flight_booking_id' => $flightBookingId,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
