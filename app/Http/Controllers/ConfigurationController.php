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

    public function partialPaymentRules(Request $request)
    {
        $q = DB::table('partial_payment_rules');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('airline_code', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $rules = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('configuration.partial_payment_rules', compact('rules'));
    }

    public function storePartialPaymentRule(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:150',
            'min_payment_percent' => 'required|numeric|min:1|max:100',
            'payment_due_days'    => 'required|integer|min:1',
        ]);
        DB::table('partial_payment_rules')->insert([
            'name'                => $request->name,
            'min_payment_percent' => $request->min_payment_percent,
            'max_defer_percent'   => 100 - $request->min_payment_percent,
            'payment_due_days'    => $request->payment_due_days,
            'applicable_for'      => $request->applicable_for ?? 'all',
            'airline_code'        => $request->airline_code ?: null,
            'route_from'          => $request->route_from ?: null,
            'route_to'            => $request->route_to ?: null,
            'is_active'           => $request->has('is_active') ? 1 : 0,
            'notes'               => $request->notes,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
        return back()->with('success', 'Partial payment rule added.');
    }

    public function updatePartialPaymentRule(Request $request, $id)
    {
        $request->validate([
            'name'                => 'required|string|max:150',
            'min_payment_percent' => 'required|numeric|min:1|max:100',
            'payment_due_days'    => 'required|integer|min:1',
        ]);
        DB::table('partial_payment_rules')->where('id', $id)->update([
            'name'                => $request->name,
            'min_payment_percent' => $request->min_payment_percent,
            'max_defer_percent'   => 100 - $request->min_payment_percent,
            'payment_due_days'    => $request->payment_due_days,
            'applicable_for'      => $request->applicable_for ?? 'all',
            'airline_code'        => $request->airline_code ?: null,
            'route_from'          => $request->route_from ?: null,
            'route_to'            => $request->route_to ?: null,
            'is_active'           => $request->has('is_active') ? 1 : 0,
            'notes'               => $request->notes,
            'updated_at'          => now(),
        ]);
        return back()->with('success', 'Rule updated.');
    }

    public function deletePartialPaymentRule($id)
    {
        DB::table('partial_payment_rules')->where('id', $id)->delete();
        return back()->with('success', 'Rule deleted.');
    }

    // ─── BLOCK ROUTES ────────────────────────────────────────────────────────────

    public function blockRoutes(Request $request)
    {
        $q = DB::table('blocking_rules');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('route_from', 'like', "%$s%")
                  ->orWhere('route_to', 'like', "%$s%")
                  ->orWhere('airline_code', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        $rules = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('configuration.block_routes', compact('rules'));
    }

    public function storeBlockRoute(Request $request)
    {
        $request->validate(['name' => 'required|string|max:200']);
        DB::table('blocking_rules')->insert([
            'name'         => $request->name,
            'gds'          => $request->gds ?? 'all',
            'airline_code' => $request->airline_code ?: null,
            'route_from'   => $request->route_from ?: null,
            'route_to'     => $request->route_to ?: null,
            'cabin_class'  => $request->cabin_class ?: null,
            'block_type'   => $request->block_type ?? 'route',
            'reason'       => $request->reason,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Block route added.');
    }

    public function updateBlockRoute(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:200']);
        DB::table('blocking_rules')->where('id', $id)->update([
            'name'         => $request->name,
            'gds'          => $request->gds ?? 'all',
            'airline_code' => $request->airline_code ?: null,
            'route_from'   => $request->route_from ?: null,
            'route_to'     => $request->route_to ?: null,
            'cabin_class'  => $request->cabin_class ?: null,
            'block_type'   => $request->block_type ?? 'route',
            'reason'       => $request->reason,
            'is_active'    => $request->has('is_active') ? 1 : 0,
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Block route updated.');
    }

    public function deleteBlockRoute($id)
    {
        DB::table('blocking_rules')->where('id', $id)->delete();
        return back()->with('success', 'Block route deleted.');
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
