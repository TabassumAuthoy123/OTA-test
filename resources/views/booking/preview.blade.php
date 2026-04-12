<!DOCTYPE html>
<html>
<head>
    <title>E-Ticket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #1a1a1a; font-size: 13px; line-height: 1.4; }

        .page-wrapper { padding: 20px 30px; }

        /* Header */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; border-bottom: 3px solid #1e3a5f; padding-bottom: 12px; }
        .company-name { font-size: 20px; font-weight: 800; color: #1e3a5f; margin-bottom: 3px; }
        .company-detail { font-size: 12px; color: #555; margin-bottom: 1px; }
        .booking-ref { font-size: 12px; color: #333; margin-top: 4px; }
        .booking-ref strong { color: #1e3a5f; }

        .eticket-title { text-align: center; font-size: 22px; font-weight: 800; color: #1e3a5f; margin: 14px 0 16px 0; letter-spacing: 2px; }

        /* Section Header */
        .section-header { background: #1e3a5f; color: #fff; padding: 6px 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; margin-top: 16px; margin-bottom: 0; }
        .section-header-alt { background: #f0c040; color: #1a1a1a; padding: 6px 12px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; margin-top: 16px; margin-bottom: 0; }

        /* Tables */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table th { background: #eef2f7; color: #1e3a5f; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 7px 10px; text-align: left; border: 1px solid #d0d7e0; }
        .info-table td { padding: 7px 10px; font-size: 12.5px; border: 1px solid #d0d7e0; }

        .pnr-row th { background: #f0c040; color: #1a1a1a; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 7px 10px; text-align: left; border: 1px solid #d0d7e0; }
        .pnr-row td { padding: 7px 10px; font-size: 12.5px; font-weight: 600; border: 1px solid #d0d7e0; }

        .itinerary-table { width: 100%; border-collapse: collapse; }
        .itinerary-table th { background: #f0c040; color: #1a1a1a; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; border: 1px solid #d0d7e0; }
        .itinerary-table td { padding: 8px 10px; font-size: 12px; border: 1px solid #d0d7e0; vertical-align: top; }

        .fare-table { width: 100%; border-collapse: collapse; }
        .fare-table th { background: #f0c040; color: #1a1a1a; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 7px 8px; text-align: center; border: 1px solid #d0d7e0; }
        .fare-table td { padding: 7px 8px; font-size: 12px; text-align: center; border: 1px solid #d0d7e0; }
        .fare-total td { font-weight: 700; background: #f8f9fb; }

        /* Baggage & Terms */
        .baggage-section { margin-top: 0; padding: 10px 12px; border: 1px solid #d0d7e0; border-top: none; }
        .baggage-section .onward-label { color: #1e3a5f; font-weight: 700; font-size: 13px; margin-bottom: 4px; }
        .baggage-section p { font-size: 12px; margin-bottom: 2px; color: #333; }
        .baggage-note { font-size: 11px; color: #666; margin-top: 6px; font-style: italic; }

        .terms-section { margin-top: 0; padding: 10px 12px; border: 1px solid #d0d7e0; border-top: none; }
        .terms-section h4 { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-top: 6px; margin-bottom: 2px; }
        .terms-section p { font-size: 11px; color: #444; margin-bottom: 4px; line-height: 1.4; }

        .footer-note { text-align: center; font-size: 10px; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>
<div class="page-wrapper">

    {{-- ─── HEADER ─── --}}
    <table class="header-table" border="0">
        <tr>
            <td style="width: 65%; vertical-align: top; padding-bottom: 10px;">
                <div class="company-name">{{ $companyProfile ? $companyProfile->name : 'Company Name' }}</div>
                @if($companyProfile && $companyProfile->address)
                <div class="company-detail"><strong>ADDRESS:</strong> {{ $companyProfile->address }}</div>
                @endif
                @if($companyProfile && $companyProfile->email)
                <div class="company-detail"><strong>EMAIL:</strong> {{ $companyProfile->email }}</div>
                @endif
                @if($companyProfile && $companyProfile->phone)
                <div class="company-detail"><strong>PHONE:</strong> {{ $companyProfile->phone }}</div>
                @endif
                <div class="booking-ref"><strong>Booking Ref:</strong> {{ $flightBookingDetails->booking_no }}</div>
            </td>
            <td style="width: 35%; text-align: right; vertical-align: top; padding-bottom: 10px;">
                @if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo)))
                <img src="{{ public_path($companyProfile->logo) }}" alt="Logo" style="max-height: 60px; max-width: 180px;">
                @endif
            </td>
        </tr>
    </table>

    {{-- ─── TITLE ─── --}}
    <div class="eticket-title">
        @if($flightBookingDetails->status == 2) E-TICKET @else E-BOOKING @endif
    </div>

    {{-- ─── PASSENGER INFORMATION ─── --}}
    <div class="section-header">✈ &nbsp; PASSENGER INFORMATION</div>
    <table class="info-table">
        <thead>
            <tr>
                <th style="width: 30%;">Passenger</th>
                <th style="width: 22%;">Passport / Doc No.</th>
                <th style="width: 18%;">Doc Expiry</th>
                <th style="width: 15%;">DOB</th>
                <th style="width: 15%;">Nationality</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flightpassengers as $pax)
            <tr>
                <td style="font-weight: 600;">{{ $pax->title }} {{ $pax->first_name }} {{ $pax->last_name }} <span style="color: #888; font-weight: 400;">({{ $pax->passenger_type }})</span></td>
                <td>{{ $pax->document_no ?? 'N/A' }}</td>
                <td>{{ $pax->document_expire_date ?? 'N/A' }}</td>
                <td>{{ $pax->dob ?? 'N/A' }}</td>
                <td>{{ $pax->nationality ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PNR & Ticket Row --}}
    <table class="info-table" style="margin-top: 0;">
        <tr class="pnr-row">
            <th style="width: 25%;">GDS PNR</th>
            <th style="width: 25%;">Airline PNR</th>
            <th style="width: 25%;">Date of Issue</th>
            <th style="width: 25%;">Ticket Number</th>
        </tr>
        <tr>
            <td style="font-weight: 700; color: #1e3a5f; font-size: 14px; letter-spacing: 1px;">{{ $flightBookingDetails->pnr_id ?? 'N/A' }}</td>
            <td style="font-weight: 700; color: #c62828; font-size: 14px; letter-spacing: 1px;">{{ $flightBookingDetails->airlines_pnr ?? 'N/A' }}</td>
            <td>
                @if($flightBookingDetails->ticket_issued_at)
                    {{ date('d-M-Y, H:i:s', strtotime($flightBookingDetails->ticket_issued_at)) }}
                @else
                    N/A
                @endif
            </td>
            <td style="font-weight: 700;">
                @if($flightpassengers[0]->ticket_no)
                    {{ $flightpassengers[0]->ticket_no }}
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>

    {{-- ─── ITINERARY INFORMATION ─── --}}
    <div class="section-header">✈ &nbsp; ITINERARY INFORMATION</div>
    <table class="itinerary-table">
        <thead>
            <tr>
                <th style="width: 28%;">Flight</th>
                <th style="width: 22%;">From</th>
                <th style="width: 10%;">Duration</th>
                <th style="width: 22%;">To</th>
                <th style="width: 18%;">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flightSegments as $index => $segment)
            @php
                $airlineInfo = DB::table('airlines')->where('iata', $segment->carrier_operating_code)->where('active', 'Y')->first();
                $departureLocation = DB::table('city_airports')->where('airport_code', $segment->departure_airport_code)->first();
                $arrivalLocation = DB::table('city_airports')->where('airport_code', $segment->arrival_airport_code)->first();

                $departure = $bookingResSegs ? ($bookingResSegs[$index]['Product']['ProductDetails']['Air']['DepartureDateTime'] ?? null) : null;
                $arrival = $bookingResSegs ? ($bookingResSegs[$index]['Product']['ProductDetails']['Air']['ArrivalDateTime'] ?? null) : null;

                $depDt = $departure ? explode('T', $departure) : [null, null];
                $arrDt = $arrival ? explode('T', $arrival) : [null, null];

                $minutes = (int) $segment->elapsed_time;
                $hours = intdiv($minutes, 60);
                $remainingMinutes = $minutes % 60;

                // Airline PNR per segment
                $airlinePNR = '';
                if($flightBookingDetails->airlines_pnr) {
                    $pnrList = explode(',', $flightBookingDetails->airlines_pnr);
                    $airlinePNR = isset($pnrList[$index]) ? $pnrList[$index] : '';
                }
            @endphp
            <tr>
                <td>
                    @if($segment->carrier_operating_code && file_exists(public_path('airlines_logo/'.strtolower($segment->carrier_operating_code).'.png')))
                    <img src="{{ public_path('airlines_logo/'.strtolower($segment->carrier_operating_code).'.png') }}" alt="" style="max-height: 28px; display: block; margin-bottom: 4px;">
                    @endif
                    <strong>{{ $airlineInfo ? $airlineInfo->name : $segment->carrier_operating_code }}</strong><br>
                    <span style="color: #555;">{{ $segment->carrier_operating_code }}-{{ $segment->carrier_operating_flight_number }}</span>
                </td>
                <td>
                    <strong>{{ $departureLocation ? $departureLocation->city_code : $segment->departure_airport_code }}</strong><br>
                    @if(isset($depDt[1]))<span style="font-size: 13px; font-weight: 700;">{{ substr($depDt[1], 0, 5) }}</span><br>@endif
                    @if(isset($depDt[0]))<span style="font-size: 11px; color: #555;">{{ date('D M d Y', strtotime($depDt[0])) }}</span>@endif
                </td>
                <td style="text-align: center; font-weight: 600;">
                    {{ $hours }}h {{ $remainingMinutes }}m
                </td>
                <td>
                    <strong>{{ $arrivalLocation ? $arrivalLocation->city_code : $segment->arrival_airport_code }}</strong><br>
                    @if(isset($arrDt[1]))<span style="font-size: 13px; font-weight: 700;">{{ substr($arrDt[1], 0, 5) }}</span><br>@endif
                    @if(isset($arrDt[0]))<span style="font-size: 11px; color: #555;">{{ date('D M d Y', strtotime($arrDt[0])) }}</span>@endif
                </td>
                <td style="font-size: 11px;">
                    <strong>CLASS:</strong> {{ getCabinClass($segment->cabin_code) }}({{ $segment->booking_code }})<br>
                    <strong>DEPARTS:</strong> {{ $segment->departure_airport_code }}<br>
                    <strong>LANDS IN:</strong> {{ $segment->arrival_airport_code }}
                    @if($airlinePNR)<br><strong>AIRLINE PNR:</strong> {{ $airlinePNR }}@endif
                </td>
            </tr>

            {{-- Transit Row --}}
            @if(isset($flightSegments[$index+1]) && $flightSegments[$index+1]->departure_airport_code != $flightBookingDetails->arrival_location)
            <tr>
                <td colspan="5" style="background: #fff8e1; text-align: center; font-size: 12px; color: #8b6914; font-style: italic; padding: 6px;">
                    @php
                        if(isset($arrDt[0]) && isset($arrDt[1])) {
                            $firstArrival = new DateTime($arrDt[0].$arrDt[1]);
                            $nextDep = $bookingResSegs ? ($bookingResSegs[$index+1]['Product']['ProductDetails']['Air']['DepartureDateTime'] ?? null) : null;
                            if($nextDep) {
                                $nextDepDt = explode('T', $nextDep);
                                $secondDeparture = new DateTime($nextDepDt[0].$nextDepDt[1]);
                                $interval = $firstArrival->diff($secondDeparture);
                                echo $interval->h . " hrs " . $interval->i . " mins Transit";
                                if($arrivalLocation) echo " in " . $arrivalLocation->city_name . " (" . $arrivalLocation->airport_code . ")";
                            }
                        }
                    @endphp
                </td>
            </tr>
            @endif

            {{-- Return Route Header --}}
            @if(isset($flightSegments[$index+1]) && $flightSegments[$index+1]->departure_airport_code == $flightBookingDetails->arrival_location)
            <tr>
                <td colspan="5" style="background: #eef2f7; font-weight: 700; padding: 6px 10px; font-size: 12px; color: #1e3a5f;">
                    RETURN: {{ $flightBookingDetails->arrival_location }} → {{ $flightBookingDetails->departure_location }}
                </td>
            </tr>
            @endif

            @endforeach
        </tbody>
    </table>

    {{-- ─── FARE DETAILS ─── --}}
    <div class="section-header">💰 &nbsp; FARE DETAILS <span style="font-weight: 400; font-size: 11px;">(All prices in {{ $flightBookingDetails->currency }})</span></div>
    <table class="fare-table">
        <thead>
            <tr>
                <th>Passenger</th>
                <th>Base Fare</th>
                <th>Taxes</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($flightBookingDetails->adult) ADT({{ $flightBookingDetails->adult }}) @endif
                    @if($flightBookingDetails->child) CHD({{ $flightBookingDetails->child }}) @endif
                    @if($flightBookingDetails->infant) INF({{ $flightBookingDetails->infant }}) @endif
                </td>
                <td>{{ number_format($flightBookingDetails->base_fare_amount, 2) }}/-</td>
                <td>{{ number_format($flightBookingDetails->total_tax_amount, 2) }}/-</td>
                <td>{{ ($flightBookingDetails->adult ?? 0) + ($flightBookingDetails->child ?? 0) + ($flightBookingDetails->infant ?? 0) }}</td>
                <td style="font-weight: 700;">{{ number_format($flightBookingDetails->total_fare, 2) }}/-</td>
            </tr>
            <tr class="fare-total">
                <td colspan="4" style="text-align: right; font-size: 13px;">Total Amount</td>
                <td style="font-size: 14px; color: #1e3a5f;">{{ number_format($flightBookingDetails->total_fare, 2) }}/-</td>
            </tr>
        </tbody>
    </table>

    {{-- ─── BAGGAGE INFORMATION ─── --}}
    <div class="section-header">🧳 &nbsp; BAGGAGE INFORMATION</div>
    <div class="baggage-section">
        @foreach($flightSegments as $index => $segment)
        @php
            $depLoc = DB::table('city_airports')->where('airport_code', $segment->departure_airport_code)->first();
            $arrLoc = DB::table('city_airports')->where('airport_code', $segment->arrival_airport_code)->first();
        @endphp
        <div class="onward-label">@if($index == 0) ONWARD @else SEGMENT {{ $index + 1 }} @endif</div>
        <p><strong>Sector:</strong> {{ $segment->departure_airport_code }} - {{ $segment->arrival_airport_code }}</p>
        <p><strong>Adult Check-in:</strong> {{ $segment->baggage_allowance ?? 'As per airline policy' }}</p>
        <p><strong>In Hand:</strong> {{ $segment->cabin_baggage ?? 'Up to 7 KG' }}</p>
        @if(!$loop->last)<hr style="border: none; border-top: 1px dashed #ddd; margin: 6px 0;">@endif
        @endforeach
        <p class="baggage-note">*Check-in Cabin: Cabin hand bag up to 7 kgs and 115 cms (L+W+H), shall be allowed per customer. For contactless travel we recommend to place it under the seat in front, on board.</p>
    </div>

    {{-- ─── TERMS AND CONDITIONS ─── --}}
    <div class="section-header">📋 &nbsp; TERMS AND CONDITIONS</div>
    <div class="terms-section">
        @if($flightBookingDetails->status == 1 || $flightBookingDetails->status == 3)
        <h4>E-Booking Notice:</h4>
        <p>The terms of carriage, which are thus incorporated by reference, apply to transport and other services rendered by the carrier. You can get these terms from the issuing airline.</p>

        <h4>Passport/Visa/Health:</h4>
        <p>Please make sure to carry a valid passport and visa for your trip, as well as any relevant documents as per your location.</p>

        <h4>Important Notes:</h4>
        <p>• Please recheck the spelling of your name with travel documents (Passport/NID).</p>
        <p>• Please check Flight Itinerary (Destination/Airlines/Travel Date/Flight Number/Timings) as per your query.</p>
        <p>• Please issue the ticket or cancel the booking within the last ticketing time mentioned.</p>
        <p>• Fare can be changed without any notice by the airlines.</p>
        <p>• Tickets are non-endorsable, non-reroutable.</p>
        <p>• Fares are not confirmed until the final ticket has been issued.</p>
        @endif

        @if($flightBookingDetails->status == 2 || $flightBookingDetails->status == 4)
        <h4>Reconfirmation of Flights:</h4>
        <p>Please check out to us for reconfirmation of your flight at least 72 hours in advance. If you do not show up in case of flight changes, your reservation may be cancelled or rescheduled and you will be charged.</p>

        <h4>Insurance:</h4>
        <p>We strongly recommend to avail travel insurance. Please check the country's rules before your trip.</p>

        <h4>Rescheduling:</h4>
        <p>Applicable charges will be as per the Airline policy including the convenience fee.</p>
        <p>i.e. (Rescheduling amount = Date change fee + difference of fare if any)</p>

        <h4>Cancellation:</h4>
        <p>Applicable charges will be as per the Airline policy including the convenience fee.</p>
        <p>i.e. (Refund amount = Paid amount - Refund charges)</p>

        <h4>Travel Reminders:</h4>
        <p>• Check-in counter opens 1.5 hours before domestic and 3 hours before international departure.</p>
        <p>• Check-in counter closes 30 minutes before domestic and 60 minutes before international departure.</p>
        <p>• Boarding gate closes 20 minutes before domestic and 30 minutes before international departure.</p>
        <p>• Passengers reporting late may be refused boarding. Please bring a valid photo ID.</p>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer-note">
        This is a system generated document from {{ $companyProfile ? $companyProfile->name : 'OTA Platform' }}. For any queries, please contact us.
    </div>

</div>
</body>
</html>
