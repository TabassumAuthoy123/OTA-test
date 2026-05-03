<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->q ?? '');

        if (strlen($q) < 2) {
            return view('admin.global_search', ['results' => collect(), 'q' => $q, 'total' => 0]);
        }

        $like     = "%{$q}%";
        $isNum    = is_numeric($q);
        $agentId  = $isNum ? (int)$q : -1;

        // Match agent IDs entered as "B2B-004" or "b2b004"
        $agentIdFromCode = -1;
        if (preg_match('/(?:b2b[-\s]?)(\d+)/i', $q, $m)) {
            $agentIdFromCode = (int)$m[1];
        }

        // Step 1: get matching booking IDs from passengers (separate query to avoid GROUP BY issues)
        $passengerBookingIds = DB::table('flight_passengers')
            ->where(function ($w) use ($like) {
                $w->where('first_name',   'like', $like)
                  ->orWhere('last_name',  'like', $like)
                  ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', $like)
                  ->orWhere('document_no','like', $like)
                  ->orWhere('phone',      'like', $like)
                  ->orWhere('email',      'like', $like);
            })
            ->pluck('flight_booking_id')
            ->toArray();

        // Step 2: main query
        $results = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->select(
                'fb.id', 'fb.booking_no', 'fb.pnr_id', 'fb.airlines_pnr',
                'fb.traveller_name', 'fb.traveller_email', 'fb.traveller_contact',
                'fb.departure_location', 'fb.arrival_location', 'fb.departure_date',
                'fb.total_fare', 'fb.status', 'fb.created_at', 'fb.flight_type',
                'u.id as agent_id', 'u.name as agent_name', 'u.email as agent_email'
            )
            ->where(function ($w) use ($like, $agentId, $agentIdFromCode, $passengerBookingIds) {
                $w->where('fb.booking_no',      'like', $like)
                  ->orWhere('fb.pnr_id',         'like', $like)
                  ->orWhere('fb.airlines_pnr',   'like', $like)
                  ->orWhere('fb.traveller_name',  'like', $like)
                  ->orWhere('fb.traveller_email', 'like', $like)
                  ->orWhere('fb.traveller_contact','like', $like)
                  ->orWhere('u.name',             'like', $like)
                  ->orWhere('u.email',            'like', $like)
                  ->orWhere('u.phone',            'like', $like)
                  ->orWhere('u.id', $agentId)
                  ->orWhere('u.id', $agentIdFromCode);

                if (!empty($passengerBookingIds)) {
                    $w->orWhereIn('fb.id', $passengerBookingIds);
                }
            })
            ->orderBy('fb.created_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.global_search', [
            'results' => $results,
            'q'       => $q,
            'total'   => $results->count(),
        ]);
    }
}
