<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAncillary extends Model
{
    protected $fillable = [
        'flight_booking_id', 'ancillary_option_id', 'type', 'name',
        'pax_index', 'qty', 'unit_price', 'total_price', 'currency', 'status', 'notes',
    ];

    protected $casts = [
        'unit_price'  => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    public function option()
    {
        return $this->belongsTo(AncillaryOption::class, 'ancillary_option_id');
    }
}
