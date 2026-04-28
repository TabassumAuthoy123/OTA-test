<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
    // ─── DYNAMIC FARE RULES ─────────────────────────────────────────────────────

    public function dynamicFareRules(Request $request)
    {
        $q = DB::table('dynamic_fare_rules');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('origin', 'like', "%$s%")
                  ->orWhere('destination', 'like', "%$s%")
                  ->orWhere('airline_code', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $rules = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('configuration.dynamic_fare_rules', compact('rules'));
    }

    public function storeDynamicFareRule(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'markup_type'  => 'required|in:fixed,percentage',
            'markup_value' => 'required|numeric|min:0',
        ]);
        DB::table('dynamic_fare_rules')->insert([
            'name'         => $request->name,
            'origin'       => $request->origin ?: null,
            'destination'  => $request->destination ?: null,
            'airline_code' => $request->airline_code ?: null,
            'trip_type'    => $request->trip_type ?? 'all',
            'cabin_class'  => $request->cabin_class ?: null,
            'markup_type'  => $request->markup_type,
            'markup_value' => $request->markup_value,
            'min_fare'     => $request->min_fare ?: null,
            'max_fare'     => $request->max_fare ?: null,
            'valid_from'   => $request->valid_from ?: null,
            'valid_until'  => $request->valid_until ?: null,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'notes'        => $request->notes,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Dynamic fare rule added.');
    }

    public function updateDynamicFareRule(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'markup_type'  => 'required|in:fixed,percentage',
            'markup_value' => 'required|numeric|min:0',
        ]);
        DB::table('dynamic_fare_rules')->where('id', $id)->update([
            'name'         => $request->name,
            'origin'       => $request->origin ?: null,
            'destination'  => $request->destination ?: null,
            'airline_code' => $request->airline_code ?: null,
            'trip_type'    => $request->trip_type ?? 'all',
            'cabin_class'  => $request->cabin_class ?: null,
            'markup_type'  => $request->markup_type,
            'markup_value' => $request->markup_value,
            'min_fare'     => $request->min_fare ?: null,
            'max_fare'     => $request->max_fare ?: null,
            'valid_from'   => $request->valid_from ?: null,
            'valid_until'  => $request->valid_until ?: null,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'notes'        => $request->notes,
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Rule updated.');
    }

    public function deleteDynamicFareRule($id)
    {
        DB::table('dynamic_fare_rules')->where('id', $id)->delete();
        return back()->with('success', 'Rule deleted.');
    }

    public function toggleDynamicFareRule($id)
    {
        $rule = DB::table('dynamic_fare_rules')->where('id', $id)->first();
        if ($rule) {
            DB::table('dynamic_fare_rules')->where('id', $id)->update([
                'is_active'  => $rule->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    // ─── PARTIAL PAYMENT RULES ───────────────────────────────────────────────────

    private array $gdsOptions = ['all' => 'All API', 'sabre' => 'SABRE', 'flyhub' => 'FlyHub'];

    public function partialPaymentRules(Request $request)
    {
        $q = DB::table('partial_payment_rules');
        if ($request->filled('filter_api') && $request->filter_api !== 'all') {
            $q->where('flight_api', $request->filter_api);
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $rules    = $q->orderByDesc('created_at')->paginate(50)->withQueryString();
        $airlines = DB::table('airlines')->where('active', 'Y')->orderBy('name')->get(['id', 'name', 'iata']);
        $gds      = $this->gdsOptions;
        return view('configuration.partial_payment_rules', compact('rules', 'airlines', 'gds'));
    }

    public function storePartialPaymentRule(Request $request)
    {
        $request->validate([
            'flight_api'     => 'required|string|max:20',
            'payment_percent'=> 'required|numeric|min:0|max:100',
        ]);
        DB::table('partial_payment_rules')->insert([
            'flight_api'            => $request->flight_api,
            'airline_code'          => $request->airline_code ?: null,
            'from_dac'              => $request->from_dac == 'yes' ? 1 : 0,
            'to_dac'                => $request->to_dac   == 'yes' ? 1 : 0,
            'domestic'              => $request->domestic  == 'yes' ? 1 : 0,
            'soto'                  => $request->soto      == 'yes' ? 1 : 0,
            'one_way'               => $request->one_way   == 'yes' ? 1 : 0,
            'round_trip'            => $request->round_trip== 'yes' ? 1 : 0,
            'travel_date_from_now'  => (int)$request->travel_date_from_now,
            'payment_before_days'   => (int)$request->payment_before_days,
            'payment_percent'       => $request->payment_percent,
            'is_active'             => 1,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
        return back()->with('success', 'Partial payment rule added.');
    }

    public function updatePartialPaymentRule(Request $request, $id)
    {
        $request->validate([
            'payment_percent' => 'required|numeric|min:0|max:100',
        ]);
        DB::table('partial_payment_rules')->where('id', $id)->update([
            'airline_code'         => $request->airline_code ?: null,
            'from_dac'             => $request->from_dac  == 'yes' ? 1 : 0,
            'to_dac'               => $request->to_dac    == 'yes' ? 1 : 0,
            'domestic'             => $request->domestic   == 'yes' ? 1 : 0,
            'soto'                 => $request->soto       == 'yes' ? 1 : 0,
            'one_way'              => $request->one_way    == 'yes' ? 1 : 0,
            'round_trip'           => $request->round_trip == 'yes' ? 1 : 0,
            'travel_date_from_now' => (int)$request->travel_date_from_now,
            'payment_before_days'  => (int)$request->payment_before_days,
            'payment_percent'      => $request->payment_percent,
            'is_active'            => $request->is_active == 'active' ? 1 : 0,
            'updated_at'           => now(),
        ]);
        return response()->json(['success' => true]);
    }

    public function deletePartialPaymentRule($id)
    {
        DB::table('partial_payment_rules')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // ─── BLOCK ROUTES ────────────────────────────────────────────────────────────

    private function blockRouteDropdowns(): array
    {
        $airports = DB::table('city_airports')
            ->select('airport_code', 'airport_name', 'city_name', 'country_name')
            ->orderBy('airport_code')->get();
        $airlines = DB::table('airlines')->where('active', 'Y')
            ->orderBy('name')->get(['iata', 'name']);
        return compact('airports', 'airlines');
    }

    public function blockRoutes(Request $request)
    {
        $q = DB::table('blocking_rules');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('departure',    'like', "%$s%")
                  ->orWhere('arrival',     'like', "%$s%")
                  ->orWhere('airline_code','like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $rules = $q->orderByDesc('created_at')->paginate(50)->withQueryString();
        ['airports' => $airports, 'airlines' => $airlines] = $this->blockRouteDropdowns();
        return view('configuration.block_routes', compact('rules', 'airports', 'airlines'));
    }

    public function blockRoutesCreate()
    {
        ['airports' => $airports, 'airlines' => $airlines] = $this->blockRouteDropdowns();
        return view('configuration.block_routes_create', compact('airports', 'airlines'));
    }

    public function storeBlockRoute(Request $request)
    {
        $rows = $request->input('routes', []);
        if (empty($rows)) {
            return redirect(route('ConfigBlockRoutes'))->with('success', 'Nothing to save.');
        }
        $inserts = [];
        foreach ($rows as $row) {
            if (empty($row['departure']) || empty($row['arrival'])) continue;
            $inserts[] = [
                'departure'     => strtoupper(trim($row['departure'])),
                'arrival'       => strtoupper(trim($row['arrival'])),
                'airline_code'  => !empty($row['airline_code']) ? strtoupper(trim($row['airline_code'])) : null,
                'one_way'       => isset($row['one_way'])       && $row['one_way']       === 'true' ? 1 : 0,
                'round_trip'    => isset($row['round_trip'])    && $row['round_trip']    === 'true' ? 1 : 0,
                'booking_block' => isset($row['booking_block']) && $row['booking_block'] === 'true' ? 1 : 0,
                'full_block'    => isset($row['full_block'])    && $row['full_block']    === 'true' ? 1 : 0,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }
        if (!empty($inserts)) {
            DB::table('blocking_rules')->insert($inserts);
        }
        return redirect(route('ConfigBlockRoutes'))->with('success', count($inserts) . ' block route(s) added.');
    }

    public function updateBlockRoute(Request $request, $id)
    {
        DB::table('blocking_rules')->where('id', $id)->update([
            'departure'     => strtoupper(trim($request->departure ?? '')),
            'arrival'       => strtoupper(trim($request->arrival ?? '')),
            'airline_code'  => $request->airline_code ? strtoupper(trim($request->airline_code)) : null,
            'one_way'       => $request->one_way       === 'true' ? 1 : 0,
            'round_trip'    => $request->round_trip    === 'true' ? 1 : 0,
            'booking_block' => $request->booking_block === 'true' ? 1 : 0,
            'full_block'    => $request->full_block    === 'true' ? 1 : 0,
            'is_active'     => $request->status        === 'enable' ? 1 : 0,
            'updated_at'    => now(),
        ]);
        return response()->json(['success' => true]);
    }

    public function deleteBlockRoute($id)
    {
        DB::table('blocking_rules')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // ─── AIRPORTS ────────────────────────────────────────────────────────────────

    public function airports(Request $request)
    {
        $q = DB::table('city_airports');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('airport_name', 'like', "%$s%")
                  ->orWhere('airport_code', 'like', "%$s%")
                  ->orWhere('city_name', 'like', "%$s%")
                  ->orWhere('country_name', 'like', "%$s%");
            });
        }
        $airports = $q->orderBy('airport_name')->paginate(25)->withQueryString();
        return view('configuration.airports', compact('airports'));
    }

    public function storeAirport(Request $request)
    {
        $request->validate([
            'airport_name' => 'required|string|max:150',
            'airport_code' => 'required|string|max:10',
            'city_name'    => 'required|string|max:100',
        ]);
        DB::table('city_airports')->insert([
            'city_name'    => $request->city_name,
            'city_code'    => strtoupper($request->city_code ?: $request->airport_code),
            'airport_name' => $request->airport_name,
            'airport_code' => strtoupper($request->airport_code),
            'country_name' => $request->country_name,
            'country_code' => strtoupper($request->country_code ?: ''),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Airport added.');
    }

    public function updateAirport(Request $request, $id)
    {
        $request->validate([
            'airport_name' => 'required|string|max:150',
            'airport_code' => 'required|string|max:10',
            'city_name'    => 'required|string|max:100',
        ]);
        DB::table('city_airports')->where('id', $id)->update([
            'city_name'    => $request->city_name,
            'city_code'    => strtoupper($request->city_code ?: $request->airport_code),
            'airport_name' => $request->airport_name,
            'airport_code' => strtoupper($request->airport_code),
            'country_name' => $request->country_name,
            'country_code' => strtoupper($request->country_code ?: ''),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Airport updated.');
    }

    public function deleteAirport($id)
    {
        DB::table('city_airports')->where('id', $id)->delete();
        return back()->with('success', 'Airport deleted.');
    }

    // ─── AIRLINES ────────────────────────────────────────────────────────────────

    public function airlines(Request $request)
    {
        $q = DB::table('airlines');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('iata', 'like', "%$s%")
                  ->orWhere('icao', 'like', "%$s%")
                  ->orWhere('country', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('active', $request->filter_status);
        }
        $airlines = $q->orderBy('name')->paginate(25)->withQueryString();
        return view('configuration.airlines', compact('airlines'));
    }

    public function storeAirline(Request $request)
    {
        $request->validate(['name' => 'required|string|max:150', 'iata' => 'required|string|max:10']);
        DB::table('airlines')->insert([
            'name'       => $request->name,
            'iata'       => strtoupper($request->iata),
            'icao'       => strtoupper($request->icao ?: ''),
            'country'    => $request->country,
            'active'     => $request->has('active') ? 'Y' : 'N',
            'comission'  => $request->comission ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Airline added.');
    }

    public function updateAirline(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:150', 'iata' => 'required|string|max:10']);
        DB::table('airlines')->where('id', $id)->update([
            'name'       => $request->name,
            'iata'       => strtoupper($request->iata),
            'icao'       => strtoupper($request->icao ?: ''),
            'country'    => $request->country,
            'active'     => $request->has('active') ? 'Y' : 'N',
            'comission'  => $request->comission ?? 0,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Airline updated.');
    }

    public function deleteAirline($id)
    {
        DB::table('airlines')->where('id', $id)->delete();
        return back()->with('success', 'Airline deleted.');
    }

    // ─── TRACKING ────────────────────────────────────────────────────────────────

    private array $trackingTypes = [
        'google_recaptcha'   => 'Google ReCaptcha',
        'google_tag_manager' => 'Google Tag Manager',
        'google_analytics'   => 'Google Analytics',
        'facebook_pixel'     => 'Facebook Pixel',
    ];

    public function tracking(Request $request)
    {
        // Ensure all 4 fixed records exist
        foreach ($this->trackingTypes as $type => $name) {
            $exists = DB::table('tracking_configs')->where('type', $type)->exists();
            if (!$exists) {
                DB::table('tracking_configs')->insert([
                    'name'          => $name,
                    'type'          => $type,
                    'tracking_code' => '',
                    'secret_key'    => null,
                    'is_active'     => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        $configs = DB::table('tracking_configs')
            ->whereIn('type', array_keys($this->trackingTypes))
            ->get()->keyBy('type');

        $activeTab = $request->get('tab', 'google_recaptcha');
        if (!array_key_exists($activeTab, $this->trackingTypes)) {
            $activeTab = 'google_recaptcha';
        }

        return view('configuration.tracking', compact('configs', 'activeTab'));
    }

    public function updateTrackingByType(Request $request, $type)
    {
        if (!array_key_exists($type, $this->trackingTypes)) {
            abort(404);
        }

        $rules = ['name' => 'required|string|max:150'];

        if ($type === 'google_recaptcha') {
            $rules['tracking_code'] = 'nullable|string|max:200';
            $rules['secret_key']    = 'nullable|string|max:200';
        } else {
            $rules['tracking_code'] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        $data = [
            'name'          => $request->name,
            'tracking_code' => $request->tracking_code ?? '',
            'secret_key'    => $type === 'google_recaptcha' ? ($request->secret_key ?? '') : null,
            'is_active'     => $request->has('is_active') ? 1 : 0,
            'updated_at'    => now(),
        ];

        $existing = DB::table('tracking_configs')->where('type', $type)->first();
        if ($existing) {
            DB::table('tracking_configs')->where('type', $type)->update($data);
        } else {
            $data['type']       = $type;
            $data['created_at'] = now();
            DB::table('tracking_configs')->insert($data);
        }

        return redirect(url('configuration/tracking') . '?tab=' . $type . '&saved=1')
            ->with('success', $this->trackingTypes[$type] . ' updated successfully.');
    }

    // kept for backward-compat with old generic routes (no-op redirect)
    public function storeTracking(Request $request)
    {
        return redirect(url('configuration/tracking'))->with('success', 'Use the per-type update form.');
    }

    public function updateTracking(Request $request, $id)
    {
        return redirect(url('configuration/tracking'));
    }

    public function deleteTracking($id)
    {
        return redirect(url('configuration/tracking'));
    }

    // ─── CITY ────────────────────────────────────────────────────────────────────

    public function cities(Request $request)
    {
        $q = DB::table('city_airports')
            ->select('city_name', 'city_code', 'country_name', 'country_code',
                     DB::raw('COUNT(*) as airport_count'),
                     DB::raw('MIN(id) as id'),
                     DB::raw('MAX(updated_at) as updated_at'))
            ->groupBy('city_name', 'city_code', 'country_name', 'country_code');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('city_name', 'like', "%$s%")
                  ->orWhere('city_code', 'like', "%$s%")
                  ->orWhere('country_name', 'like', "%$s%");
            });
        }
        $cities = $q->orderBy('city_name')->paginate(25)->withQueryString();
        return view('configuration.cities', compact('cities'));
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'city_name'    => 'required|string|max:100',
            'airport_name' => 'required|string|max:150',
            'airport_code' => 'required|string|max:10',
        ]);
        DB::table('city_airports')->insert([
            'city_name'    => $request->city_name,
            'city_code'    => strtoupper($request->city_code ?: $request->airport_code),
            'airport_name' => $request->airport_name,
            'airport_code' => strtoupper($request->airport_code),
            'country_name' => $request->country_name,
            'country_code' => strtoupper($request->country_code ?: ''),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'City / Airport added.');
    }

    public function deleteCity(Request $request)
    {
        $request->validate(['city_name' => 'required|string']);
        DB::table('city_airports')->where('city_name', $request->city_name)->delete();
        return back()->with('success', 'City and all its airports deleted.');
    }

    // ─── ANNOUNCEMENTS ───────────────────────────────────────────────────────────

    public function announcements(Request $request)
    {
        $q = DB::table('announcements');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('title', 'like', "%$s%")
                  ->orWhere('message', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $items = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('configuration.announcements', compact('items'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'message' => 'required|string',
            'type'    => 'required|in:info,warning,success,danger',
        ]);
        DB::table('announcements')->insert([
            'title'      => $request->title,
            'message'    => $request->message,
            'type'       => $request->type,
            'target'     => $request->target ?? 'all',
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'show_from'  => $request->show_from ?: null,
            'show_until' => $request->show_until ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Announcement added.');
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'message' => 'required|string',
            'type'    => 'required|in:info,warning,success,danger',
        ]);
        DB::table('announcements')->where('id', $id)->update([
            'title'      => $request->title,
            'message'    => $request->message,
            'type'       => $request->type,
            'target'     => $request->target ?? 'all',
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'show_from'  => $request->show_from ?: null,
            'show_until' => $request->show_until ?: null,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Announcement updated.');
    }

    public function deleteAnnouncement($id)
    {
        DB::table('announcements')->where('id', $id)->delete();
        return back()->with('success', 'Announcement deleted.');
    }

    public function toggleAnnouncement($id)
    {
        $item = DB::table('announcements')->where('id', $id)->first();
        if ($item) {
            DB::table('announcements')->where('id', $id)->update([
                'is_active'  => $item->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }
}
