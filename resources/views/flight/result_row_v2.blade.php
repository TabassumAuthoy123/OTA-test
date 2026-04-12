@php
    $segmentArray = [];
    $totalFlightTiming = 0;
    $legsArray = $data['legs'];
    foreach ($legsArray as $key => $leg) {

        $legRef = $leg['ref'] - 1;
        $legDescription = $searchResults['groupedItineraryResponse']['legDescs'][$legRef];
        $schedulesArray = $legDescription['schedules'];

        foreach ($schedulesArray as $schedule) {
            $scheduleRef = $schedule['ref'] - 1;
            $scheduleData = $searchResults['groupedItineraryResponse']['scheduleDescs'][$scheduleRef];
            $searchQueryDepartureDate = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][$key]['departureDate'];
            $totalFlightTiming += $scheduleData['elapsedTime'];

            $daysToBeAdded = 0;
            if (isset($schedule['departureDateAdjustment'])) {
                $daysToBeAdded = $schedule['departureDateAdjustment'];
                $scheduleData['departure']['dateTime'] = date("Y-m-d", strtotime("+".$daysToBeAdded." day", strtotime($searchQueryDepartureDate)))." ".$scheduleData['departure']['time'];
                $scheduleData['arrival']['dateTime'] = date("Y-m-d", strtotime("+".$daysToBeAdded." day", strtotime($searchQueryDepartureDate)))." ".$scheduleData['arrival']['time'];
            } else {
                $scheduleData['departure']['dateTime'] = $searchQueryDepartureDate." ".$scheduleData['departure']['time'];
                $scheduleData['arrival']['dateTime'] = $searchQueryDepartureDate." ".$scheduleData['arrival']['time'];
            }

            if (isset($scheduleData['arrival']['dateAdjustment'])) {
                $daysToBeAdded = $daysToBeAdded + $scheduleData['arrival']['dateAdjustment'];
                $scheduleData['arrival']['dateTime'] = date("Y-m-d", strtotime("+".$daysToBeAdded." day", strtotime($searchQueryDepartureDate)))." ".$scheduleData['arrival']['time'];
            }

            // extra field
            $scheduleData['step'] = $key; // to understand oneway/roundtrip/multicity

            $segmentArray[] = $scheduleData;
        }
    }

    // ═══ Rules Engine: Blocking Check ═══
    $_reAirline = $segmentArray[0]['carrier']['operating'] ?? '';
    $_reRouteFrom = $segmentArray[0]['departure']['airport'] ?? '';
    $_reRouteTo = end($segmentArray)['arrival']['airport'] ?? '';
    $_reCabin = session('cabin_class', 'Y');
    $_isBlocked = \App\Services\RulesEngineService::isBlocked('sabre', $_reAirline, $_reRouteFrom, $_reRouteTo, $_reCabin);

    // price related calculation
    $airlineInfo = DB::table('airlines')
                    ->where('iata', $segmentArray[0]['carrier']['operating'])
                    ->where('active', 'Y')
                    ->first();

    $netPrice = $data['pricingInformation'][0]['fare']['totalFare']['totalPrice'];
    $basePrice = $data['pricingInformation'][0]['fare']['totalFare']['equivalentAmount'];

    // ═══ Rules Engine Commission ═══
    $_reAgentId = Auth::user()->user_type == 2 ? Auth::user()->id : null;

    $comissionAmount = \App\Services\RulesEngineService::calculateCommission(
        'sabre', $_reAirline, $_reRouteFrom, $_reRouteTo, $_reCabin, 'ADT', $_reAgentId, $basePrice
    );

    // Fallback: legacy commission if no rules engine rule matched
    if ($comissionAmount <= 0) {
        if (Auth::user()->user_type == 2) {
            if ($airlineInfo && $airlineInfo->comission > 0) {
                $b2bUsersComission = Auth::user()->comission;
                if (!empty($b2bUsersComission) && is_numeric($b2bUsersComission) && $b2bUsersComission > 0) {
                    $comissionAmount = round(($basePrice * $b2bUsersComission) / 100, 2);
                }
            }
        } else {
            if ($airlineInfo && $airlineInfo->comission > 0) {
                $comissionAmount = round(($basePrice * 7) / 100, 2);
            }
        }
    }
    $netPrice -= $comissionAmount;
@endphp

@if(!$_isBlocked)
<div class="row flight_card">
    @if(session('flight_type') == 1)
        @include('flight.result_row_v2_oneway')
    @elseif (session('flight_type') == 2)
        @include('flight.result_row_v2_roundtrip')
    @else
        @include('flight.result_row_v2_multicity')
    @endif
</div>
@endif
