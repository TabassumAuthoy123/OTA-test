<?php

namespace App\Services\Gds;

use App\Models\FlightBooking;
use Illuminate\Http\Request;

/**
 * Common interface for all GDS (Global Distribution System) service implementations.
 * Each GDS provider (Sabre, Flyhub, etc.) must implement this interface.
 */
interface GdsServiceInterface
{
    /**
     * Create a flight booking via the GDS API.
     *
     * @param Request $request  The incoming HTTP request with passenger data
     * @param array   $sessionData  Revalidated/session data for the booking
     * @return array  ['success' => bool, 'data' => array, 'raw_response' => string]
     */
    public function createBooking(Request $request, array $sessionData): array;

    /**
     * Issue a ticket for an existing booking.
     *
     * @param FlightBooking $booking  The booking to issue a ticket for
     * @return array  ['success' => bool, 'data' => array, 'raw_response' => string]
     */
    public function issueTicket(FlightBooking $booking): array;

    /**
     * Cancel an existing booking.
     *
     * @param FlightBooking $booking  The booking to cancel
     * @return array  ['success' => bool, 'data' => array, 'raw_response' => string]
     */
    public function cancelBooking(FlightBooking $booking): array;

    /**
     * Cancel an issued ticket (void).
     *
     * @param FlightBooking $booking  The booking whose ticket to cancel
     * @param array         $ticketNumbers  The ticket numbers to void
     * @return array  ['success' => bool, 'data' => array, 'raw_response' => string]
     */
    public function cancelTicket(FlightBooking $booking, array $ticketNumbers = []): array;

    /**
     * Check if this GDS provider is currently active/enabled.
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Check if this GDS is running in production mode.
     *
     * @return bool
     */
    public function isProduction(): bool;
}
