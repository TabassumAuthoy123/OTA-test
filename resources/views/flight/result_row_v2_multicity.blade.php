@php
    /**
     * Multicity segments come in $segmentArray with an added "step" field
     * (step = leg index). We'll group them dynamically so legs are not fixed.
     */

    $legsSegments = [];
    foreach ($segmentArray as $seg) {
        $step = $seg['step'] ?? 0;
        if (!isset($legsSegments[$step])) $legsSegments[$step] = [];
        $legsSegments[$step][] = $seg;
    }
    ksort($legsSegments);

    /**
     * Helper for total elapsed time per leg
     */
    $calcLegElapsed = function(array $segments) {
        $t = 0;
        foreach ($segments as $s) $t += ($s['elapsedTime'] ?? 0);
        return $t;
    };

    /**
     * Helper for transit time blocks (same logic you used in oneway/roundtrip)
     */
    $renderTransit = function(array $segments) {
        if (count($segments) <= 1) return;

        echo '<div class="transit-container">';
        foreach ($segments as $idx => $seg) {
            if ($idx <= 0) continue;

            $lastLandedAt    = $segments[$idx - 1]['arrival']['dateTime'];
            $willDepartureAt = $segments[$idx]['departure']['dateTime'];

            $date1 = new DateTime($lastLandedAt);
            $date2 = new DateTime($willDepartureAt);
            $differenceInMinutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;
            $totalHours   = intdiv((int)$differenceInMinutes, 60);
            $totalMinutes = (int)$differenceInMinutes % 60;

            $airport = $seg['departure']['airport'] ?? '';

            echo '
                <div class="transit text-center">
                    <span>'.$totalHours.'hr '.$totalMinutes.'min</span>
                    <h6>Transit at '.$airport.'</h6>
                </div>
            ';
        }
        echo '</div>';
    };

@endphp

<div class="row">
    <div class="col-lg-10">
        <div class="row">

            @foreach($legsSegments as $legIndex => $legSegments)
                @php
                    // Defensive checks
                    if (!is_array($legSegments) || count($legSegments) === 0) continue;

                    $first = $legSegments[0];
                    $last  = end($legSegments);

                    // Airline name lookup per leg (like roundtrip inbound/outbound blocks)
                    $opCarrier = DB::table('airlines')
                        ->where('iata', $first['carrier']['operating'])
                        ->where('active', 'Y')
                        ->first();

                    $legTotalMinutes = $calcLegElapsed($legSegments);

                    // For baggage + seats, multicity can vary:
                    // - baggageInformation index often aligns with leg index (0..n-1)
                    // - fareComponents index often aligns with leg index (0..n-1)
                    // We'll fallback to index 0 safely if not present.
                    $baggageIdx = $legIndex;
                    $fareCompIdx = $legIndex;
                @endphp

                {{-- Add divider between legs (except first) --}}
                @if($legIndex > 0)
                    <div class="col-12 mt-3 pt-3 border-top"></div>
                @endif

                {{-- AIRLINE --}}
                <div class="col-lg-3 flight_airlines">
                    <img class="img-fluid"
                         src="{{ url('airlines_logo') }}/{{ strtolower($first['carrier']['operating']) }}.png"
                         loading="lazy">

                    @if ($opCarrier)
                        <h5 class="text-center">{{ $opCarrier->name }}</h5>
                    @endif

                    <h6>
                        {{ $first['carrier']['operatingFlightNumber'] ?? '' }}-{{ $first['carrier']['equipment']['code'] ?? '' }}
                    </h6>
                </div>

                {{-- DEPARTURE --}}
                <div class="col-lg-2 flight_timing">
                    <h6 class="mb-1">{{ (new DateTimeImmutable($first['departure']['dateTime']))->format("jS M, y") }}</h6>
                    <h4>{{ (new DateTimeImmutable($first['departure']['dateTime']))->format("H:i") }}</h4>
                    <h6>({{ (new DateTimeImmutable($first['departure']['dateTime']))->format("h:i A") }})</h6>
                    <h5>{{ $first['departure']['airport'] }}</h5>
                    <h6 class="city_name">
                        {{ DB::table('city_airports')->where('airport_code', $first['departure']['airport'])->first()->city_name }}
                    </h6>
                </div>

                {{-- DURATION + TRANSIT --}}
                <div class="col-lg-5 flight_duration">
                    <i class="fas fa-plane"></i>
                    <span>{{ App\Models\CustomFunction::convertMinToHrMin($legTotalMinutes) }}</span>

                    @php
                        $renderTransit($legSegments);
                    @endphp

                    <button>
                        @if (count($legSegments) > 1)
                            {{ count($legSegments) - 1 }}
                        @else
                            Non
                        @endif
                        Stop
                    </button>
                </div>

                {{-- ARRIVAL --}}
                <div class="col-lg-2 flight_timing">
                    <h6 class="mb-1">{{ (new DateTimeImmutable($last['arrival']['dateTime']))->format("jS M, y") }}</h6>
                    <h4>{{ (new DateTimeImmutable($last['arrival']['dateTime']))->format("H:i") }}</h4>
                    <h6>({{ (new DateTimeImmutable($last['arrival']['dateTime']))->format("h:i A") }})</h6>
                    <h5>{{ $last['arrival']['airport'] }}</h5>
                    <h6 class="city_name">
                        {{ DB::table('city_airports')->where('airport_code', $last['arrival']['airport'])->first()->city_name }}
                    </h6>
                </div>

                {{-- BAGGAGE + SEATS (per-leg line) --}}
                <div class="col-lg-12 additional_info">
                    <h6>
                        @php
                            // -------- Baggage ----------
                            foreach ($data['pricingInformation'][0]['fare']['passengerInfoList'] as $passengerData) {
                                $bagInfo = $passengerData['passengerInfo']['baggageInformation'] ?? [];
                                $chosenBagIdx = isset($bagInfo[$baggageIdx]) ? $baggageIdx : 0;

                                if (isset($bagInfo[$chosenBagIdx]['allowance']['ref'])) {
                                    $baggageRef = $bagInfo[$chosenBagIdx]['allowance']['ref'];

                                    if (isset($searchResults['groupedItineraryResponse']['baggageAllowanceDescs'][$baggageRef - 1])) {
                                        $bagDesc = $searchResults['groupedItineraryResponse']['baggageAllowanceDescs'][$baggageRef - 1];

                                        if (isset($bagDesc['pieceCount'])) {
                                            echo 'Baggage: ' . ($bagDesc['pieceCount'] * $passengerData['passengerInfo']['passengerNumber']) . " Piece, ";
                                        }
                                        if (isset($bagDesc['weight'])) {
                                            echo "Baggage: " . ($bagDesc['weight'] * $passengerData['passengerInfo']['passengerNumber']);
                                        }
                                        if (isset($bagDesc['unit'])) {
                                            echo $bagDesc['unit'] . ", ";
                                        }
                                    }
                                }
                            }

                            // -------- Seats (first segment of the chosen fareComponent) ----------
                            foreach ($data['pricingInformation'][0]['fare']['passengerInfoList'] as $passengerData) {
                                $fareComponents = $passengerData['passengerInfo']['fareComponents'] ?? [];
                                $chosenFareCompIdx = isset($fareComponents[$fareCompIdx]) ? $fareCompIdx : 0;

                                if (isset($fareComponents[$chosenFareCompIdx]['segments'])) {
                                    foreach ($fareComponents[$chosenFareCompIdx]['segments'] as $segIdx => $segRef) {
                                        if ($segIdx == 0) {
                                            if (isset($segRef['segment']['seatsAvailable'])) {
                                                $seatsM = (int)$segRef['segment']['seatsAvailable'];
                                                echo ($seatsM > 0 && $seatsM <= 5) ? '<span style="color:#dc3545;font-weight:700">Limited</span>' : 'Available';
                                            } else {
                                                echo "Available";
                                            }
                                            break;
                                        }
                                    }
                                } else {
                                    echo "Seat: N/A";
                                }
                            }
                        @endphp
                    </h6>
                </div>

            @endforeach

        </div>
    </div>

    {{-- PRICE --}}
    <div class="col-lg-2 flight_price">
        <small>Gross:</small>
        <h5>৳ {{ number_format($data['pricingInformation'][0]['fare']['totalFare']['totalPrice']) }} </h5>
        <small>Net:</small>
        <h5>৳ {{ number_format($netPrice) }} </h5>
        <a href="{{ url('select/flight') }}/{{ $index }}">Select Flight</a>
    </div>
</div>

{{-- Footer additional info: refundable + segment breakdown (all segments) --}}
<div class="col-lg-12 additional_info mt-2 d-block">
    @php
        $refundStatus = "";
        if (isset($data['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['nonRefundable'])) {
            $refundStatus = $data['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['nonRefundable'] ? "Yes" : "No";
        }
    @endphp

    @if($refundStatus != "")
        <h6>
            Refundable:
            <span style="@if($refundStatus == "Yes") color: green; @else color: red; @endif font-weight: 600;">
                {{$refundStatus}}
            </span>
        </h6>
    @endif

    @foreach ($segmentArray as $segmentIndex => $segmentData)
        @php
            $equipCode = $segmentData['carrier']['equipment']['code'] ?? null;
            $aircraft  = aircraft_name($equipCode);
        @endphp

        <h6>
            {{ $segmentData['carrier']['operating'] ?? '' }}-{{ $segmentData['carrier']['marketingFlightNumber'] ?? '' }}
            ({{ $aircraft }}):
            From <strong>{{ $segmentData['departure']['airport'] }}</strong>
            ({{ (new DateTimeImmutable($segmentData['departure']['dateTime']))->format("d-M-y h:i A") }})
            To <strong>{{ $segmentData['arrival']['airport'] }}</strong>
            ({{ (new DateTimeImmutable($segmentData['arrival']['dateTime']))->format("d-M-y h:i A") }})
        </h6>
    @endforeach
</div>
