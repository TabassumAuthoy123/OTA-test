<?php

namespace App\Services;

use App\Models\FlightBooking;
use App\Models\FlightPassenger;
use App\Models\FlightSegment;
use App\Models\SavedPassenger;
use App\Models\SabreGdsConfig;
use App\Models\FlyhubGdsConfig;
use App\Services\Gds\SabreService;
use App\Services\Gds\FlyhubService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service class that handles all flight booking business logic.
 * Extracts DB operations, passenger/segment saving, and pricing
 * from the FlightBookingController.
 */
class BookingService
{
    protected SabreService $sabreService;
    protected FlyhubService $flyhubService;

    public function __construct(SabreService $sabreService, FlyhubService $flyhubService)
    {
        $this->sabreService = $sabreService;
        $this->flyhubService = $flyhubService;
    }

    /**
     * Process a Sabre flight booking: call GDS API, persist booking, segments, and passengers.
     *
     * @return array ['success' => bool, 'booking_id' => int|null, 'status' => int]
     */
    public function createSabreBooking(Request $request): array
    {
        $revlidatedData = session('revlidatedData');

        $gdsResult = $this->sabreService->createBooking($request, $revlidatedData);

        $status = $gdsResult['success'] ? 1 : 0;

        // Extract pricing from session data (secure — not from hidden fields)
        $pricing = $this->extractSabrePricing($revlidatedData);

        $sabreGdsInfo = SabreGdsConfig::where('id', 1)->first();
        $departureAirportCode = DB::table('city_airports')->where('id', session('departure_location_id'))->first()->airport_code;
        $arrivalAirportCode = DB::table('city_airports')->where('id', session('destination_location_id'))->first()->airport_code;

        $flightBookingId = FlightBooking::insertGetId([
            'flight_type' => session('flight_type'),
            'booking_no' => Str::random(3) . "-" . time(),
            "source" => 1,
            'booked_by' => Auth::user()->id,
            'b2b_comission' => Auth::user()->comission,
            'pnr_id' => $gdsResult['pnr_id'],
            'booking_id' => null,
            'gds' => $request->gds,
            'gds_unique_id' => 'SOOL',
            'traveller_name' => $request->traveller_name,
            'traveller_email' => $request->traveller_email,
            'traveller_contact' => $request->traveller_contact,
            'departure_date' => $request->departure_date,
            'departure_location' => $departureAirportCode,
            'arrival_location' => $arrivalAirportCode,
            'governing_carriers' => $request->governing_carriers,
            'adult' => session('adult'),
            'child' => session('child'),
            'infant' => session('infant'),
            'base_fare_amount' => $pricing['base_fare_amount'],
            'total_tax_amount' => $pricing['total_tax_amount'],
            'total_fare' => $pricing['total_fare'],
            'currency' => $request->currency,
            'last_ticket_datetime' => null,
            'booking_request' => session('booking_request'),
            'booking_response' => $gdsResult['raw_response'],
            'get_booking_response' => null,
            'status' => $status,
            'payment_status' => null,
            'is_live' => $sabreGdsInfo ? $sabreGdsInfo->is_production : 0,
            'created_at' => Carbon::now()
        ]);

        // Save segments
        $this->saveSabreSegments($flightBookingId, $revlidatedData);

        // Save passengers
        $this->savePassengers($flightBookingId, $request);

        // Clean up session
        session()->forget(['adult', 'child', 'infant', 'revlidatedData', 'booking_request']);

        return [
            'success' => true,
            'booking_id' => $flightBookingId,
            'status' => $status,
        ];
    }

    /**
     * Process a Flyhub flight booking: call GDS API, persist booking, segments, and passengers.
     *
     * @return array ['success' => bool, 'booking_id' => int|null, 'error' => string|null]
     */
    public function createFlyhubBooking(Request $request): array
    {
        $revalidatedData = session('revalidatedData');

        $gdsResult = $this->flyhubService->createBooking($request, $revalidatedData);

        if (!$gdsResult['success']) {
            return [
                'success' => false,
                'booking_id' => null,
                'error' => $gdsResult['error'] ?? 'Failed to Book this Flight',
            ];
        }

        $flyhubGdsInfo = FlyhubGdsConfig::where('id', 1)->first();
        $departureAirportCode = DB::table('city_airports')->where('id', session('departure_location_id'))->first()->airport_code;
        $arrivalAirportCode = DB::table('city_airports')->where('id', session('destination_location_id'))->first()->airport_code;

        $flightBookingId = FlightBooking::insertGetId([
            'flight_type' => session('flight_type'),
            'booking_no' => Str::random(3) . "-" . time(),
            "source" => 1,
            'booked_by' => Auth::user()->id,
            'b2b_comission' => Auth::user()->comission,
            'pnr_id' => null,
            'booking_id' => $gdsResult['booking_id'],
            'airlines_pnr' => $gdsResult['airlines_pnr'],
            'gds' => $request->gds,
            'gds_unique_id' => $request->gds_unique_id,
            'traveller_name' => $request->traveller_name,
            'traveller_email' => $request->traveller_email,
            'traveller_contact' => $request->traveller_contact,
            'departure_date' => date("Y-m-d h:i:s", strtotime($request->departure_date)),
            'departure_location' => $departureAirportCode,
            'arrival_location' => $arrivalAirportCode,
            'governing_carriers' => $request->governing_carriers,
            'adult' => session('adult'),
            'child' => session('child'),
            'infant' => session('infant'),
            'base_fare_amount' => $revalidatedData['base_fare_amount'],
            'total_tax_amount' => $revalidatedData['total_tax_amount'],
            'total_fare' => $revalidatedData['total_fare'],
            'currency' => $request->currency,
            'last_ticket_datetime' => $request->last_ticket_datetime ? date("Y-m-d h:i:s", strtotime($request->last_ticket_datetime)) : null,
            'booking_response' => $gdsResult['raw_response'],
            'status' => 1,
            'payment_status' => null,
            'is_live' => $flyhubGdsInfo ? $flyhubGdsInfo->is_production : 0,
            'created_at' => Carbon::now()
        ]);

        // Save segments (onward + return)
        $this->saveFlyhubSegments($flightBookingId, $revalidatedData);

        // Save passengers
        $this->savePassengers($flightBookingId, $request);

        // Clean up session
        session()->forget(['adult', 'child', 'infant', 'revlidatedData']);

        return [
            'success' => true,
            'booking_id' => $flightBookingId,
            'error' => null,
        ];
    }

    /**
     * Extract pricing info from Sabre revalidated data.
     */
    protected function extractSabrePricing(array $revlidatedData): array
    {
        $fare = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare'];

        if ($fare['totalFare']['baseFareCurrency'] == 'USD') {
            $baseFare = $fare['totalFare']['baseFareAmount']
                * $fare['passengerInfoList'][0]['passengerInfo']['currencyConversion']['exchangeRateUsed'];
        } else {
            $baseFare = $fare['totalFare']['baseFareAmount'];
        }

        return [
            'base_fare_amount' => $baseFare,
            'total_tax_amount' => $fare['totalFare']['totalTaxAmount'],
            'total_fare' => $fare['totalFare']['totalPrice'],
        ];
    }

    /**
     * Save Sabre flight segments from revalidated data.
     */
    protected function saveSabreSegments(int $flightBookingId, array $revlidatedData): void
    {
        $segmentArray = [];
        $legsArray = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['legs'];

        foreach ($legsArray as $leg) {
            $legRef = $leg['ref'] - 1;
            $legDescription = $revlidatedData['groupedItineraryResponse']['legDescs'][$legRef];
            foreach ($legDescription['schedules'] as $schedule) {
                $scheduleRef = $schedule['ref'] - 1;
                $segmentArray[] = $revlidatedData['groupedItineraryResponse']['scheduleDescs'][$scheduleRef];
            }
        }

        foreach ($segmentArray as $segmentData) {
            FlightSegment::insert([
                'flight_booking_id' => $flightBookingId,
                'total_miles_flown' => $segmentData['totalMilesFlown'],
                'elapsed_time' => $segmentData['elapsedTime'],
                'booking_code' => null,
                'cabin_code' => null,
                'baggage_allowance' => null,
                'departure_airport_code' => $segmentData['departure']['airport'],
                'departure_city_code' => $segmentData['departure']['city'],
                'departure_country_code' => $segmentData['departure']['country'],
                'departure_time' => $segmentData['departure']['time'],
                'departure_terminal' => $segmentData['departure']['terminal'] ?? null,
                'arrival_airport_code' => $segmentData['arrival']['airport'],
                'arrival_city_code' => $segmentData['arrival']['city'],
                'arrival_country_code' => $segmentData['arrival']['country'],
                'arrival_time' => $segmentData['arrival']['time'],
                'arrival_terminal' => $segmentData['arrival']['terminal'] ?? null,
                'carrier_marketing_code' => $segmentData['carrier']['marketing'],
                'carrier_marketing_flight_number' => $segmentData['carrier']['marketingFlightNumber'],
                'carrier_operating_code' => $segmentData['carrier']['operating'],
                'carrier_operating_flight_number' => $segmentData['carrier']['operatingFlightNumber'],
                'carrier_equipment_code' => $segmentData['carrier']['equipment']['code'],
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * Save Flyhub flight segments (onward + return) from revalidated data.
     */
    protected function saveFlyhubSegments(int $flightBookingId, array $revalidatedData): void
    {
        $onwardSegmentArray[] = $revalidatedData['segments'];
        $returnSegmentArray[] = isset($revalidatedData['return_segments']) ? $revalidatedData['return_segments'] : [];

        // Onward segments
        if (count($onwardSegmentArray) > 0) {
            foreach ($onwardSegmentArray as $segmentIndex => $segmentData) {
                $this->saveFlyhubSegment($flightBookingId, $segmentData, $segmentIndex);
            }
        }

        // Return segments
        if (count($returnSegmentArray) > 0 && isset($revalidatedData['return_segments'])) {
            foreach ($returnSegmentArray as $segmentIndex => $segmentData) {
                $this->saveFlyhubSegment($flightBookingId, $segmentData, $segmentIndex);
            }
        }
    }

    /**
     * Save a single Flyhub segment.
     */
    protected function saveFlyhubSegment(int $flightBookingId, array $segmentData, int $segmentIndex): void
    {
        $departureZone = DB::table('city_airports')->where('city_name', $segmentData[$segmentIndex]['departure_city_name'])->first();
        $arrivalZone = DB::table('city_airports')->where('city_name', $segmentData[$segmentIndex]['arrival_city_name'])->first();

        FlightSegment::insert([
            'flight_booking_id' => $flightBookingId,
            'total_miles_flown' => $segmentData[$segmentIndex]['miles_flown'],
            'elapsed_time' => $segmentData[$segmentIndex]['elapsed_time'],
            'booking_code' => $segmentData[$segmentIndex]['booking_code'],
            'cabin_code' => $segmentData[$segmentIndex]['cabin_code'],
            'departure_airport_code' => $segmentData[$segmentIndex]['departure_airport_code'],
            'departure_city_code' => $departureZone ? $departureZone->city_code : null,
            'departure_country_code' => $departureZone ? $departureZone->country_code : null,
            'departure_time' => date("Y-m-d h:i:s", strtotime($segmentData[$segmentIndex]['departure_datetime'])),
            'departure_terminal' => $segmentData[$segmentIndex]['departure_terminal'],
            'arrival_airport_code' => $segmentData[$segmentIndex]['arrival_airport_code'],
            'arrival_city_code' => $arrivalZone ? $arrivalZone->city_code : null,
            'arrival_country_code' => $arrivalZone ? $arrivalZone->country_code : null,
            'arrival_time' => date("Y-m-d h:i:s", strtotime($segmentData[$segmentIndex]['arrival_datetime'])),
            'arrival_terminal' => $segmentData[$segmentIndex]['arrival_terminal'],
            'carrier_marketing_code' => $segmentData[$segmentIndex]['marketing_carrier_code'],
            'carrier_marketing_flight_number' => $segmentData[$segmentIndex]['marketing_flight_number'],
            'carrier_operating_code' => $segmentData[$segmentIndex]['operating_carrier_code'],
            'carrier_operating_flight_number' => $segmentData[$segmentIndex]['operating_flight_number'],
            'carrier_equipment_code' => null,
            'created_at' => Carbon::now()
        ]);
    }

    /**
     * Save passengers for a booking and optionally save them to the saved passengers list.
     */
    protected function savePassengers(int $flightBookingId, Request $request): void
    {
        foreach ($request->first_name as $passengerIndex => $firstName) {

            // Save passenger to saved list if requested
            if (is_array($request->save_passenger) && count($request->save_passenger) > 0 && in_array($passengerIndex, $request->save_passenger)) {
                $this->saveOrUpdatePassenger($request, $passengerIndex, $firstName);
            }

            // Always save as flight passenger
            FlightPassenger::insert([
                'flight_booking_id' => $flightBookingId,
                'passanger_type' => $request->passenger_type[$passengerIndex],
                'title' => $request->titles[$passengerIndex],
                'first_name' => $firstName,
                'last_name' => $request->last_name[$passengerIndex],
                'email' => $request->email[$passengerIndex],
                'phone' => $request->phone[$passengerIndex],
                'dob' => $request->dob[$passengerIndex],
                'age' => str_pad($request->age[$passengerIndex], 2, "0", STR_PAD_LEFT),
                'document_type' => $request->document_type[$passengerIndex],
                'document_no' => $request->document_no[$passengerIndex],
                'document_expire_date' => $request->document_expire_date[$passengerIndex],
                'document_issue_country' => $request->document_issue_country[$passengerIndex],
                'nationality' => $request->nationality[$passengerIndex],
                'frequent_flyer_no' => $request->frequent_flyer_no[$passengerIndex],
                'created_at' => Carbon::now()
            ]);
        }
    }

    /**
     * Save or update a passenger in the saved passengers table.
     */
    protected function saveOrUpdatePassenger(Request $request, int $index, string $firstName): void
    {
        $savedPassenger = SavedPassenger::where([
            ['first_name', $firstName],
            ['last_name', $request->last_name[$index]],
            ['dob', '=', $request->dob[$index]]
        ])->first();

        if (!$savedPassenger) {
            $savedPassenger = new SavedPassenger();
        }

        $savedPassenger->saved_by = Auth::user()->id;
        $savedPassenger->email = $request->email[$index];
        $savedPassenger->contact = $request->phone[$index];
        $savedPassenger->type = $request->passenger_type[$index];
        $savedPassenger->title = $request->titles[$index];
        $savedPassenger->first_name = $firstName;
        $savedPassenger->last_name = $request->last_name[$index];
        $savedPassenger->dob = $request->dob[$index];
        $savedPassenger->age = str_pad($request->age[$index], 2, "0", STR_PAD_LEFT);
        $savedPassenger->document_type = $request->document_type[$index];
        $savedPassenger->document_no = $request->document_no[$index];
        $savedPassenger->document_expire_date = $request->document_expire_date[$index];
        $savedPassenger->document_issue_country = $request->document_issue_country[$index];
        $savedPassenger->nationality = $request->nationality[$index];
        $savedPassenger->frequent_flyer_no = $request->frequent_flyer_no[$index];
        $savedPassenger->created_at = Carbon::now();
        $savedPassenger->save();
    }
}
