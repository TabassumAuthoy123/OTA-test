<?php

namespace App\Http\Controllers;

use App\Models\AncillaryOption;
use App\Models\BookingAncillary;
use App\Models\FlightBooking;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AncillaryController extends Controller
{
    // ─── ADMIN: Manage ancillary options ───────────────────────────────────────

    public function adminIndex()
    {
        $options = AncillaryOption::orderBy('type')->orderBy('price')->paginate(50);
        return view('ancillary.admin_index', compact('options'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:baggage,meal,seat,other',
            'name'  => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        AncillaryOption::updateOrCreate(
            ['id' => $request->option_id],
            [
                'type'         => $request->type,
                'name'         => $request->name,
                'description'  => $request->description,
                'weight_kg'    => $request->weight_kg,
                'price'        => $request->price,
                'currency'     => $request->currency ?? 'BDT',
                'airline_code' => strtoupper($request->airline_code ?? '') ?: null,
                'route_from'   => strtoupper($request->route_from ?? '') ?: null,
                'route_to'     => strtoupper($request->route_to ?? '') ?: null,
                'is_active'    => $request->boolean('is_active', true),
            ]
        );

        Toastr::success('Ancillary option saved.');
        return redirect()->route('AdminAncillaries');
    }

    public function adminGet($id)
    {
        return response()->json(AncillaryOption::findOrFail($id));
    }

    public function adminDelete($id)
    {
        AncillaryOption::destroy($id);
        Toastr::success('Option deleted.');
        return redirect()->route('AdminAncillaries');
    }

    // ─── AJAX: Get options for a flight (B2B / B2C) ────────────────────────────

    public function getOptions(Request $request)
    {
        $options = AncillaryOption::forFlight(
            $request->airline_code,
            $request->route_from,
            $request->route_to,
            $request->type ?? 'baggage'
        );
        return response()->json($options);
    }

    // ─── Add ancillary to a booking (B2B agent or admin) ───────────────────────

    public function addToBooking(Request $request)
    {
        $request->validate([
            'flight_booking_id' => 'required|integer',
            'type'              => 'required|in:baggage,meal,seat,other',
            'name'              => 'required|string|max:100',
            'qty'               => 'required|integer|min:1',
            'unit_price'        => 'required|numeric|min:0',
            'pax_index'         => 'nullable|integer|min:0',
        ]);

        $booking = FlightBooking::findOrFail($request->flight_booking_id);

        // B2B agents can only add to their own bookings
        if (Auth::user()->user_type == 2 && $booking->booked_by != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        BookingAncillary::create([
            'flight_booking_id'   => $booking->id,
            'ancillary_option_id' => $request->ancillary_option_id,
            'type'                => $request->type,
            'name'                => $request->name,
            'pax_index'           => $request->pax_index ?? 0,
            'qty'                 => $request->qty,
            'unit_price'          => $request->unit_price,
            'total_price'         => $request->unit_price * $request->qty,
            'currency'            => $request->currency ?? 'BDT',
            'notes'               => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Ancillary added to booking.']);
    }

    public function removeFromBooking($id)
    {
        $ancillary = BookingAncillary::findOrFail($id);
        $booking = FlightBooking::find($ancillary->flight_booking_id);

        if (Auth::user()->user_type == 2 && $booking && $booking->booked_by != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ancillary->delete();
        return response()->json(['success' => true]);
    }
}
