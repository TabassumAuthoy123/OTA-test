<?php

namespace App\Services;

use App\Models\FlyhubFlightSearch;
use App\Models\Gds;
use App\Models\SabreFlightSearch;
use Illuminate\Support\Facades\DB;

class FlightSearchService
{
    /**
     * Core flight search — calls active GDS(es) and returns raw results + operating carrier codes.
     *
     * @param array $params  Search parameters:
     *   - origin_code (string)
     *   - destination_code (string)
     *   - departure_date (string Y-m-d)
     *   - return_date (string|null Y-m-d)
     *   - adult (int)
     *   - child (int)
     *   - infant (int)
     *   - flight_type (int) 1=OneWay, 2=Return
     *   - cabin_class (string)
     *   - preferred_airlines (array)
     *   - airline_prefs (array|null) Sabre format [{Code => XX}]
     *
     * @return array ['results' => [...], 'operating_carriers' => [...], 'gds' => 'flyhub|sabre']
     */
    public static function search(array $params): array
    {
        $originCode = $params['origin_code'];
        $destinationCode = $params['destination_code'];
        $departureDate = $params['departure_date'];
        $returnDate = $params['return_date'] ?? null;
        $adult = $params['adult'];
        $child = $params['child'];
        $infant = $params['infant'];
        $flightType = $params['flight_type'];
        $cabinClass = $params['cabin_class'];
        $preferredAirlines = $params['preferred_airlines'] ?? [];
        $airlinePrefs = $params['airline_prefs'] ?? null;

        $searchResults = [];
        $operatingCodes = [];
        $activeGds = null;

        // Sabre GDS
        $sabreGds = Gds::where('code', 'sabre')->first();
        if ($sabreGds && $sabreGds->status == 1) {
            $searchResults = SabreFlightSearch::getFlightSearchResults(
                $originCode,
                $destinationCode,
                $departureDate,
                $returnDate,
                $adult,
                $child,
                $infant,
                $flightType,
                $cabinClass,
                $airlinePrefs
            );
            $activeGds = 'sabre';

            // Extract operating carrier codes from Sabre response
            $decoded = json_decode($searchResults, true);
            if (isset($decoded['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'])) {
                foreach ($decoded['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'] as $data) {
                    $segmentArray = [];
                    foreach ($data['legs'] as $leg) {
                        $legRef = $leg['ref'] - 1;
                        $legDescription = $decoded['groupedItineraryResponse']['legDescs'][$legRef];
                        foreach ($legDescription['schedules'] as $schedule) {
                            $scheduleRef = $schedule['ref'] - 1;
                            $segmentArray[] = $decoded['groupedItineraryResponse']['scheduleDescs'][$scheduleRef];
                        }
                    }
                    if (!empty($segmentArray)) {
                        $operatingCodes[] = $segmentArray[0]['carrier']['operating'];
                    }
                }
            }
            $operatingCodes = array_values(array_unique($operatingCodes));
        }

        // Flyhub GDS
        $flyhubGds = Gds::where('code', 'flyhub')->first();
        if ($flyhubGds && $flyhubGds->status == 1) {
            $searchResults = FlyhubFlightSearch::getFlightSearchResults(
                $originCode,
                $destinationCode,
                $departureDate,
                $returnDate,
                $adult,
                $child,
                $infant,
                $flightType,
                $cabinClass,
                $preferredAirlines
            );
            $activeGds = 'flyhub';

            // Extract operating carrier codes from Flyhub response
            $operatingCodes = [];
            if (is_array($searchResults) && count($searchResults)) {
                foreach ($searchResults as $result) {
                    $operatingCodes[] = $result['operating_carrier_code'];
                }
            }
            $operatingCodes = array_values(array_unique($operatingCodes));
        }

        return [
            'results' => $searchResults,
            'operating_carriers' => $operatingCodes,
            'gds' => $activeGds,
        ];
    }

    /**
     * Resolve preferred airlines from comma-separated IDs to IATA codes.
     */
    public static function resolvePreferredAirlines(?string $preferredAirlinesRaw): array
    {
        $preferredAirlinesArray = [];
        $airlinePrefs = null;

        // Guard against "null"/"undefined" strings sent from the frontend
        if ($preferredAirlinesRaw && !in_array(trim($preferredAirlinesRaw), ['null', 'undefined', ''])) {
            foreach (explode(",", $preferredAirlinesRaw) as $id) {
                $id = trim($id);
                if (!is_numeric($id)) continue;
                $airline = DB::table('airlines')->where('id', $id)->first();
                if ($airline && $airline->iata) {
                    $preferredAirlinesArray[] = $airline->iata;
                }
            }
            $airlinePrefs = array_map(fn($code) => ["Code" => $code], $preferredAirlinesArray);
        }

        return [
            'codes' => $preferredAirlinesArray,
            'sabre_prefs' => $airlinePrefs,
        ];
    }

    /**
     * Build search params from a request object.
     */
    public static function buildParamsFromRequest($request): array
    {
        $departureLocationId = $request->departure_location_id;
        $originCityInfo = DB::table('city_airports')->where('id', $departureLocationId)->first();

        $destinationLocationId = $request->destination_location_id;
        $destinationCityInfo = DB::table('city_airports')->where('id', $destinationLocationId)->first();

        $airlines = self::resolvePreferredAirlines($request->preferred_airlines);

        return [
            'departure_location_id' => $departureLocationId,
            'origin_city_info' => $originCityInfo,
            'origin_code' => $originCityInfo->airport_code,
            'destination_location_id' => $destinationLocationId,
            'destination_city_info' => $destinationCityInfo,
            'destination_code' => $destinationCityInfo->airport_code,
            'departure_date' => date("Y-m-d", strtotime($request->departure_date)),
            'return_date' => $request->return_date ? date("Y-m-d", strtotime($request->return_date)) : null,
            'adult' => $request->adult,
            'child' => $request->child,
            'infant' => $request->infant,
            'flight_type' => $request->flight_type,
            'cabin_class' => $request->cabin_class ?? 'Y',
            'preferred_airlines' => $airlines['codes'],
            'airline_prefs' => $airlines['sabre_prefs'],
        ];
    }

    /**
     * Normalize raw Sabre JSON response into a flat array matching Flyhub format.
     * Used by B2C to render results with the standard flight cards view.
     *
     * @param  string $sabreJson  Raw JSON string from Sabre API
     * @return array              Flat array of flight results
     */
    public static function normalizeSabreResults(string $sabreJson): array
    {
        $decoded = json_decode($sabreJson, true);
        if (!isset($decoded['groupedItineraryResponse']['itineraryGroups'])) {
            return [];
        }

        $response   = $decoded['groupedItineraryResponse'];
        $legDescs   = $response['legDescs']      ?? [];
        $schedDescs = $response['scheduleDescs'] ?? [];
        $results    = [];

        foreach ($response['itineraryGroups'] as $group) {
            foreach (($group['itineraries'] ?? []) as $idx => $itinerary) {
                // Resolve first leg's segments
                $leg    = $itinerary['legs'][0] ?? null;
                if (!$leg) continue;
                $legDesc = $legDescs[($leg['ref'] - 1)] ?? null;
                if (!$legDesc) continue;

                $segments = [];
                foreach ($legDesc['schedules'] as $sch) {
                    $seg = $schedDescs[($sch['ref'] - 1)] ?? null;
                    if ($seg) $segments[] = $seg;
                }
                if (empty($segments)) continue;

                $first = $segments[0];
                $last  = end($segments);

                $depAirport = $first['departure']['airport'] ?? '';
                $arrAirport = $last['arrival']['airport']   ?? '';

                // City info lookup
                $depInfo = DB::table('city_airports')->where('airport_code', $depAirport)->first();
                $arrInfo = DB::table('city_airports')->where('airport_code', $arrAirport)->first();

                // Datetime normalisation: "2026-04-20T10:00:00.000" → "2026-04-20 10:00:00"
                $depDt = str_replace('T', ' ', substr($first['departure']['dateTime'] ?? '', 0, 19));
                $arrDt = str_replace('T', ' ', substr($last['arrival']['dateTime']   ?? '', 0, 19));

                $fare     = $itinerary['pricingInformation'][0]['fare']['totalFare'] ?? [];
                $total    = $fare['totalPrice'] ?? 0;
                $currency = $fare['currency']   ?? 'BDT';

                $results[] = [
                    'total_fare'              => $total,
                    'selling_fare'            => $total,
                    'currency'                => $currency,
                    'departure_datetime'      => $depDt,
                    'arrival_datetime'        => $arrDt,
                    'departure_airport_code'  => $depAirport,
                    'arrival_airport_code'    => $arrAirport,
                    'departure_airport_name'  => $depInfo->airport_name ?? $depAirport,
                    'departure_city_name'     => $depInfo->city_name    ?? $depAirport,
                    'departure_country_name'  => $depInfo->country_name ?? '',
                    'arrival_airport_name'    => $arrInfo->airport_name ?? $arrAirport,
                    'arrival_city_name'       => $arrInfo->city_name    ?? $arrAirport,
                    'arrival_country_name'    => $arrInfo->country_name ?? '',
                    'operating_carrier_code'  => $first['carrier']['operating'] ?? 'XX',
                    'flight_number'           => $first['carrier']['operatingFlightNumber'] ?? '',
                    'stop_quantity'           => count($segments) - 1,
                    'baggage'                 => 'Check with airline',
                    'cabin_class'             => 'Economy',
                    'search_id'               => null,
                    'result_id'               => null,
                    'gds'                     => 'sabre',
                    'sabre_index'             => $idx,
                ];
            }
        }

        return $results;
    }

    /**
     * Store search session data (used by both admin and B2C).
     */
    public static function storeSearchSession(array $params, array $searchData): void
    {
        $originCity = $params['origin_city_info'];
        $destCity = $params['destination_city_info'];

        session([
            'departure_location_id' => $params['departure_location_id'],
            'origin_city_name' => $originCity->city_name,
            'destination_location_id' => $params['destination_location_id'],
            'destination_city_name' => $destCity->city_name,
            'departure_date' => $params['departure_date'],
            'return_date' => $params['return_date'],
            'adult' => $params['adult'],
            'child' => $params['child'],
            'infant' => $params['infant'],
            'flight_type' => $params['flight_type'],
            'preferred_airlines' => $params['preferred_airlines'],
            'cabin_class' => $params['cabin_class'],
            'search_results' => $searchData['results'],
            'search_results_operating_carriers' => $searchData['operating_carriers'],
        ]);

        // Clear old filters
        session()->forget('filter_min_price');
        session()->forget('filter_max_price');
        session()->forget('airline_carrier_code');
    }
}
