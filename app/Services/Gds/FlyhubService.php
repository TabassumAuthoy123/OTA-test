<?php

namespace App\Services\Gds;

use App\Models\FlightBooking;
use App\Models\FlyhubFlightBooking;
use App\Models\FlyhubFlightTicketIssue;
use App\Models\FlyhubGdsConfig;
use App\Models\Gds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Service class for Flyhub GDS API operations.
 * Consolidates booking, ticketing, and cancellation logic from
 * FlyhubFlightBooking and FlyhubFlightTicketIssue models.
 */
class FlyhubService implements GdsServiceInterface
{
    /**
     * Create a flight booking via Flyhub API.
     * Flyhub requires a two-step process: updateTravellers → createBooking.
     */
    public function createBooking(Request $request, array $sessionData): array
    {
        try {
            // Step 1: Update travellers
            $travellerResponse = json_decode(
                FlyhubFlightBooking::updateTravellers($request, $sessionData),
                true
            );

            if (($travellerResponse['status'] ?? '') !== 'success') {
                return [
                    'success' => false,
                    'error' => 'Failed to update traveller information',
                    'pnr_id' => null,
                    'booking_id' => null,
                    'airlines_pnr' => null,
                    'data' => $travellerResponse,
                    'raw_response' => json_encode($travellerResponse),
                ];
            }

            // Step 2: Create the booking
            $bookingResponse = json_decode(
                FlyhubFlightBooking::createBooking($request, $sessionData),
                true
            );

            $success = ($bookingResponse['status'] ?? '') === 'success';

            return [
                'success' => $success,
                'pnr_id' => null,
                'booking_id' => $success ? ($bookingResponse['general']['booking_id'] ?? null) : null,
                'airlines_pnr' => $success ? ($bookingResponse['general']['airlines_pnr'] ?? null) : null,
                'data' => $bookingResponse,
                'raw_response' => json_encode($bookingResponse, true),
            ];
        } catch (\Throwable $e) {
            Log::error('Flyhub createBooking failed: ' . $e->getMessage());
            return [
                'success' => false,
                'pnr_id' => null,
                'booking_id' => null,
                'airlines_pnr' => null,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Issue a ticket for an existing Flyhub booking.
     */
    public function issueTicket(FlightBooking $booking): array
    {
        try {
            $rawResponse = FlyhubFlightTicketIssue::issueTicket($booking);
            $response = json_decode($rawResponse, true);

            $success = ($response['status'] ?? '') === 'success';

            return [
                'success' => $success,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Flyhub issueTicket failed: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Cancel a Flyhub booking.
     */
    public function cancelBooking(FlightBooking $booking): array
    {
        try {
            $rawResponse = FlyhubFlightTicketIssue::cancelTicket($booking);
            $response = json_decode($rawResponse, true);

            $success = ($response['status'] ?? '') === 'success';

            return [
                'success' => $success,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Flyhub cancelBooking failed: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Cancel (void) issued tickets via Flyhub API.
     * Flyhub uses the same cancelTicket endpoint for both booking and ticket cancellation.
     */
    public function cancelTicket(FlightBooking $booking, array $ticketNumbers = []): array
    {
        try {
            $rawResponse = FlyhubFlightTicketIssue::cancelTicket($booking);
            $response = json_decode($rawResponse, true);

            $success = ($response['status'] ?? '') === 'success';

            return [
                'success' => $success,
                'all_voided' => $success,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Flyhub cancelTicket failed: ' . $e->getMessage());
            return [
                'success' => false,
                'all_voided' => false,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Check if Flyhub GDS is active.
     */
    public function isActive(): bool
    {
        $gds = Gds::where('code', 'flyhub')->first();
        return $gds && $gds->status == 1;
    }

    /**
     * Check if Flyhub is running in production mode.
     */
    public function isProduction(): bool
    {
        $config = FlyhubGdsConfig::where('id', 1)->first();
        return $config ? (bool) $config->is_production : false;
    }
}
