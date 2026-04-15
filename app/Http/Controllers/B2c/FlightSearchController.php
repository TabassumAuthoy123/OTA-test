<?php

namespace App\Http\Controllers\B2c;

use App\Http\Controllers\Controller;
use App\Models\FlyhubFlightRevalidate;
use App\Models\SabreFlightRevalidate;
use App\Services\FlightSearchService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class FlightSearchController extends Controller
{
    /**
     * B2C Flight Search — called via AJAX from landing page
     * Uses the shared FlightSearchService + PricingService with B2C markup
     */
    public function search(Request $request)
    {
        try {
            // Build params using shared service
            $params = FlightSearchService::buildParamsFromRequest($request);

            // Search via GDS (same as admin)
            $searchData = FlightSearchService::search($params);

            // Build flat B2C display results (normalise Sabre JSON → array, or apply Flyhub markup)
            if ($searchData['gds'] === 'flyhub' && is_array($searchData['results'])) {
                $b2cResults = PricingService::applyMarkup($searchData['results'], 'b2c');
                // Keep markup-applied prices in the shared session too
                $searchData['results'] = $b2cResults;
            } elseif ($searchData['gds'] === 'sabre' && is_string($searchData['results'])) {
                $b2cResults = FlightSearchService::normalizeSabreResults($searchData['results']);
                // shared session keeps the raw JSON (needed by SabreFlightRevalidate)
            } else {
                $b2cResults = [];
            }

            // Store shared session keys (admin + Sabre revalidation use session('search_results'))
            FlightSearchService::storeSearchSession($params, $searchData);

            // Store B2C flat results separately so the B2C view always gets an array
            session(['b2c_flight_results' => $b2cResults]);

            // Also store b2c_ prefixed keys used by the B2C results view
            session([
                'b2c_origin_city_name'     => $params['origin_city_info']->city_name,
                'b2c_destination_city_name'=> $params['destination_city_info']->city_name,
                'b2c_origin_code'          => $params['origin_code'],
                'b2c_destination_code'     => $params['destination_code'],
                'b2c_departure_date'       => $params['departure_date'],
                'b2c_return_date'          => $params['return_date'],
                'b2c_adult'                => $params['adult'],
                'b2c_child'                => $params['child'],
                'b2c_infant'               => $params['infant'],
                'b2c_flight_type'          => $params['flight_type'],
            ]);

            // Clear old b2c filters on new search
            session()->forget(['b2c_filter_min_price', 'b2c_filter_max_price', 'b2c_airline_carrier_code']);

            return response()->json(['success' => true, 'redirect' => url('/flights/results')]);
        } catch (\Exception $e) {
            \Log::error('B2C Flight Search Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Search failed. Please try again.'], 500);
        }
    }

    /**
     * Show B2C search results page
     */
    public function results()
    {
        // b2c_flight_results is always a flat PHP array (set for both Sabre & Flyhub by search())
        $searchResults    = session('b2c_flight_results', session('search_results', []));
        // For Sabre, search_results is a JSON string — ensure we pass an array
        if (is_string($searchResults)) {
            $searchResults = [];
        }
        $operatingCarriers = session('search_results_operating_carriers', []);

        // Build airline name lookup for the results view
        $airlineNames = [];
        if (!empty($operatingCarriers)) {
            DB::table('airlines')
                ->whereIn('iata', $operatingCarriers)
                ->get(['iata', 'name'])
                ->each(function ($a) use (&$airlineNames) {
                    $airlineNames[$a->iata] = $a->name;
                });
        }

        return view('b2c.flights.results', compact('searchResults', 'operatingCarriers', 'airlineNames'));
    }

    /**
     * B2C Price filter via AJAX
     */
    public function priceFilter(Request $request)
    {
        if ($request->min_price !== null && $request->min_price !== '') {
            session(['b2c_filter_min_price' => $request->min_price]);
        } else {
            session()->forget('b2c_filter_min_price');
        }
        if ($request->max_price !== null && $request->max_price !== '') {
            session(['b2c_filter_max_price' => $request->max_price]);
        } else {
            session()->forget('b2c_filter_max_price');
        }
        return response()->json(['success' => true]);
    }

    /**
     * B2C Airline filter via AJAX
     */
    public function airlineFilter(Request $request)
    {
        $carrierCode    = $request->airline_carrier_code;
        $type           = $request->type;
        $currentFilters = session('b2c_airline_carrier_code', []);

        if ($type === 'add') {
            if (!in_array($carrierCode, $currentFilters)) {
                $currentFilters[] = $carrierCode;
            }
        } else {
            $currentFilters = array_diff($currentFilters, [$carrierCode]);
        }

        session(['b2c_airline_carrier_code' => array_values($currentFilters)]);
        return response()->json(['success' => true]);
    }

    /**
     * B2C Revalidate flight for booking
     */
    public function selectFlight($index)
    {
        $searchResults = session('b2c_flight_results', session('search_results', []));
        if (is_string($searchResults)) $searchResults = [];

        if (!isset($searchResults[$index])) {
            Toastr::error('Flight not found. Please search again.');
            return redirect('/flights/results');
        }

        $selectedFlight = $searchResults[$index];

        // Sabre revalidation
        if (isset($selectedFlight['gds']) && $selectedFlight['gds'] === 'sabre') {
            $sabreIndex   = $selectedFlight['sabre_index'];
            $revalidateRaw = SabreFlightRevalidate::flightRevalidate($sabreIndex);
            $revalidated   = json_decode($revalidateRaw, true);

            if (!isset($revalidated['groupedItineraryResponse'])) {
                Toastr::error('Flight is no longer available. Please try another flight.');
                return redirect('/flights/results');
            }

            session(['b2c_selected_flight'       => $revalidated]);
            session(['b2c_selected_flight_gds'   => 'sabre']);
            session(['b2c_selected_flight_index' => $index]);
            return redirect('/flights/booking');
        }

        // Flyhub revalidation
        $revalidateResult = FlyhubFlightRevalidate::revalidate($selectedFlight['search_id'], $selectedFlight['result_id']);

        if (isset($revalidateResult['error'])) {
            Toastr::error('Flight is no longer available. Please try another flight.');
            return redirect('/flights/results');
        }

        session(['b2c_selected_flight'       => $revalidateResult]);
        session(['b2c_selected_flight_gds'   => 'flyhub']);
        session(['b2c_selected_flight_index' => $index]);
        return redirect('/flights/booking');
    }

    /**
     * Live city/airport search (shared, no auth required)
     */
    public function cityAirportSearch(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            if (strlen($search) < 2)
                return response()->json([]);

            $data = DB::table('city_airports')
                ->select("id", DB::raw("CONCAT(city_name, '-', airport_name) AS search_result"))
                ->where(function ($query) use ($search) {
                    $query->where('airport_code', 'LIKE', $search . "%")
                        ->orWhere('city_code', 'LIKE', $search . "%")
                        ->orWhere('city_name', 'LIKE', $search . "%");
                })
                ->limit(10)
                ->get();
        }

        return response()->json($data);
    }
}
