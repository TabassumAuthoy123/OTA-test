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

        $like = "%{$q}%";

        $results = DB::table('flight_bookings as fb')
            ->leftJoin('users as u', 'u.id', '=', 'fb.booked_by')
            ->leftJoin('flight_passengers as fp', 'fp.flight_booking_id', '=', 'fb.id')
            ->select(
                'fb.id', 'fb.booking_no', 'fb.pnr_id', 'fb.airlines_pnr',
                'fb.traveller_name', 'fb.traveller_email', 'fb.traveller_contact',
                'fb.departure_location', 'fb.arrival_location', 'fb.departure_date',
                'fb.total_fare', 'fb.status', 'fb.created_at', 'fb.flight_type',
                'u.id as agent_id', 'u.name as agent_name', 'u.email as agent_email'
            )
            ->where(function ($w) use ($like, $q) {
                $w->where('fb.booking_no',   'like', $like)
                  ->orWhere('fb.pnr_id',      'like', $like)
                  ->orWhere('fb.airlines_pnr','like', $like)
                  ->orWhere('fb.traveller_name','like', $like)
                  ->orWhere('fb.traveller_email','like', $like)
                  ->orWhere('fb.traveller_contact','like', $like)
                  ->orWhere('u.name',          'like', $like)
                  ->orWhere('u.email',         'like', $like)
                  ->orWhere(DB::raw("CONCAT('B2B-', LPAD(u.id,3,'0'))"), '=', strtoupper($q))
                  ->orWhere('u.id',            '=', is_numeric($q) ? (int)$q : -1)
                  ->orWhere('fp.first_name',   'like', $like)
                  ->orWhere('fp.last_name',    'like', $like)
                  ->orWhere('fp.document_no',  'like', $like);
            })
            ->groupBy(
                'fb.id','fb.booking_no','fb.pnr_id','fb.airlines_pnr',
                'fb.traveller_name','fb.traveller_email','fb.traveller_contact',
                'fb.departure_location','fb.arrival_location','fb.departure_date',
                'fb.total_fare','fb.status','fb.created_at','fb.flight_type',
                'u.id','u.name','u.email'
            )
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
