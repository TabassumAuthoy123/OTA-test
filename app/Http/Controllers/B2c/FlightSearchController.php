<?php

namespace App\Http\Controllers\B2c;

use App\Http\Controllers\Controller;
use App\Models\FlyhubFlightRevalidate;
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
        // Build params using shared service
        $params = FlightSearchService::buildParamsFromRequest($request);

        // Search via GDS (same as admin)
        $searchData = FlightSearchService::search($params);

        // Apply B2C markup to prices
        if ($searchData['gds'] === 'flyhub' && is_array($searchData['results'])) {
            $searchData['results'] = PricingService::applyMarkup($searchData['results'], 'b2c');
        }

        // Store in session (uses same session keys as admin)
        FlightSearchService::storeSearchSession($params, $searchData);

        return response()->json(['success' => true, 'redirect' => url('/flights/results')]);
    }

    /**
     * Show B2C search results page — reuses the admin's common results view
     */
    public function results()
    {
        $searchResults = session('search_results', []);
        $search_results_operating_carriers = session('search_results_operating_carriers', []);

        return view('common.flight.searchResults', compact('searchResults', 'search_results_operating_carriers'));
    }

    /**
     * B2C Price filter via AJAX
     */
    public function priceFilter(Request $request)
    {
        if ($request->min_price > 0) {
            session(['filter_min_price' => $request->min_price]);
        }
        if ($request->max_price > 0) {
            session(['filter_max_price' => $request->max_price]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * B2C Airline filter via AJAX
     */
    public function airlineFilter(Request $request)
    {
        $carrierCode = $request->airline_carrier_code;
        $type = $request->type;
        $currentFilters = session('airline_carrier_code', []);

        if ($type === 'add') {
            if (!in_array($carrierCode, $currentFilters)) {
                $currentFilters[] = $carrierCode;
            }
        } else {
            $currentFilters = array_diff($currentFilters, [$carrierCode]);
        }

        session(['airline_carrier_code' => array_values($currentFilters)]);
        return response()->json(['success' => true]);
    }

    /**
     * B2C Revalidate flight for booking
     */
    public function selectFlight($index)
    {
        $searchResults = session('search_results', []);
        if (!isset($searchResults[$index])) {
            Toastr::error('Flight not found. Please search again.');
            return redirect('/flights/results');
        }

        $selectedFlight = $searchResults[$index];

        // Revalidate with Flyhub
        $revalidateResult = FlyhubFlightRevalidate::revalidate($selectedFlight['search_id'], $selectedFlight['result_id']);

        if (isset($revalidateResult['error'])) {
            Toastr::error('Flight is no longer available. Please try another flight.');
            return redirect('/flights/results');
        }

        session(['b2c_selected_flight' => $revalidateResult]);
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
