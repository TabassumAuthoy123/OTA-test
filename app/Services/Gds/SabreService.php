<?php

namespace App\Services\Gds;

use App\Models\FlightBooking;
use App\Models\Gds;
use App\Models\SabreFlightBooking;
use App\Models\SabreFlightTicketIssue;
use App\Models\SabreGdsConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Service class for Sabre GDS API operations.
 * Consolidates booking, ticketing, and cancellation logic from
 * SabreFlightBooking and SabreFlightTicketIssue models.
 */
class SabreService implements GdsServiceInterface
{
    /**
     * Create a flight booking via Sabre API.
     */
    public function createBooking(Request $request, array $sessionData): array
    {
        try {
            $response = json_decode(
                SabreFlightBooking::flightBooking(
                    $sessionData,
                    $request->traveller_contact,
                    $request->traveller_email,
                    $request->first_name,
                    $request->last_name,
                    $request->titles,
                    $request->dob,
                    $request->passenger_type,
                    $request->age,
                    $request->document_issue_country,
                    $request->nationality,
                    $request->document_no,
                    $request->document_expire_date
                ),
                true
            );

            $success = isset($response['CreatePassengerNameRecordRS']['ApplicationResults']['status'])
                && $response['CreatePassengerNameRecordRS']['ApplicationResults']['status'] == 'Complete';

            $pnrId = $success
                ? $response['CreatePassengerNameRecordRS']['ItineraryRef']['ID']
                : null;

            return [
                'success' => $success,
                'pnr_id' => $pnrId,
                'booking_id' => null,
                'airlines_pnr' => null,
                'data' => $response,
                'raw_response' => json_encode($response, true),
            ];
        } catch (\Throwable $e) {
            Log::error('Sabre createBooking failed: ' . $e->getMessage());
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
     * Issue a ticket for an existing Sabre booking.
     */
    public function issueTicket(FlightBooking $booking): array
    {
        try {
            $rawResponse = SabreFlightTicketIssue::issueTicket($booking->pnr_id);
            $response = json_decode($rawResponse, true);

            $success = isset($response['AirTicketRS']['ApplicationResults']['status'])
                && $response['AirTicketRS']['ApplicationResults']['status'] == 'Complete';

            return [
                'success' => $success,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Sabre issueTicket failed: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Cancel a Sabre booking.
     */
    public function cancelBooking(FlightBooking $booking): array
    {
        try {
            $rawResponse = SabreFlightBooking::cancelBooking($booking->booking_no);
            $response = json_decode($rawResponse, true);

            $success = isset($response['booking']['bookingId'])
                && $response['booking']['bookingId'] == $booking->pnr_id;

            return [
                'success' => $success,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Sabre cancelBooking failed: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Cancel (void) issued tickets via Sabre API.
     */
    public function cancelTicket(FlightBooking $booking, array $ticketNumbers = []): array
    {
        try {
            $rawResponse = SabreFlightTicketIssue::cancelIssuedTicket($ticketNumbers);
            $response = json_decode($rawResponse, true);

            $voidedTickets = $response['voidedTickets'] ?? [];
            $allVoided = !empty($voidedTickets) && empty(array_diff($ticketNumbers, $voidedTickets));

            return [
                'success' => isset($response['voidedTickets']),
                'all_voided' => $allVoided,
                'voided_tickets' => $voidedTickets,
                'data' => $response,
                'raw_response' => $rawResponse,
            ];
        } catch (\Throwable $e) {
            Log::error('Sabre cancelTicket failed: ' . $e->getMessage());
            return [
                'success' => false,
                'all_voided' => false,
                'voided_tickets' => [],
                'data' => [],
                'raw_response' => '',
            ];
        }
    }

    /**
     * Check if Sabre GDS is active.
     */
    public function isActive(): bool
    {
        $gds = Gds::where('code', 'sabre')->first();
        return $gds && $gds->status == 1;
    }

    /**
     * Check if Sabre is running in production mode.
     */
    public function isProduction(): bool
    {
        $config = SabreGdsConfig::where('id', 1)->first();
        return $config ? (bool) $config->is_production : false;
    }
}
