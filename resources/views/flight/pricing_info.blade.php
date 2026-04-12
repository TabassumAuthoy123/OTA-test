<div class="card-body">
    <h3 class="fs-17 mb-0">Fare summary</h3>
    <p class="fs-14">Travellers :
        @foreach ($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['passengerInfoList'] as $passengerData)
            <b>
                {{ $passengerData['passengerInfo']['passengerNumber'] }}
                {{ $passengerData['passengerInfo']['passengerType'] }}
            </b>&nbsp;
        @endforeach
    </p>
    <hr>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="summary-text">
            <div class="font-weight-500">Base Fare</div>
        </div>
        <div class="fs-16 font-weight-500" style="font-weight: 600;">
            ({{ $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['currency'] }})

            @php $basePrice = 0; @endphp
            @if ($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare'
                ]['totalFare']['baseFareCurrency'] == 'USD')
                @php
                    $basePrice = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['baseFareAmount'] * $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['passengerInfoList'][0]['passengerInfo']['currencyConversion']['exchangeRateUsed'];
                @endphp
                {{ number_format($basePrice) }}
            @else
                @php
                    $basePrice = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['baseFareAmount'];
                @endphp
                {{ number_format($basePrice) }}
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="summary-text">
            <div class="font-weight-500"> Total Tax Amount </div>
        </div>
        <div class="fs-16 font-weight-500" style="font-weight: 600;">
            ({{ $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['currency'] }})
            {{ number_format($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['totalTaxAmount']) }}
        </div>
    </div>
    <hr>
    <div class="d-flex align-items-center justify-content-between">
        <div class="fs-14 font-weight-300">Total Net Amount</div>
        <div class="fs-16 font-weight-500">
            @php
                $validatingCarrierCode = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['validatingCarrierCode'];
                $airlineInfo = DB::table('airlines')
                                ->where('iata', $validatingCarrierCode)
                                ->where('active', 'Y')
                                ->first();

                $netPrice = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['totalPrice'];

                // ═══ Rules Engine Commission ═══
                $_reRouteFrom = '';
                $_reRouteTo = '';
                if (isset($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0])) {
                    $_reRouteFrom = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['departureLocation'] ?? '';
                    $_reRouteTo = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['arrivalLocation'] ?? '';
                }
                $_reCabin = session('cabin_class', 'Y');
                $_reAgentId = Auth::user()->user_type == 2 ? Auth::user()->id : null;

                $comissionAmount = \App\Services\RulesEngineService::calculateCommission(
                    'sabre', $validatingCarrierCode, $_reRouteFrom, $_reRouteTo, $_reCabin, 'ADT', $_reAgentId, $basePrice
                );

                // Fallback: legacy commission if no rules engine rule matched
                if ($comissionAmount <= 0) {
                    if(Auth::user()->user_type == 2) {
                        if($airlineInfo && $airlineInfo->comission > 0){
                            $b2bUsersComission = Auth::user()->comission;
                            if(!empty($b2bUsersComission) && is_numeric($b2bUsersComission) && $b2bUsersComission > 0) {
                                $comissionAmount = round(($basePrice * $b2bUsersComission) / 100, 2);
                            }
                        }
                    } else {
                        if($airlineInfo && $airlineInfo->comission > 0){
                            $comissionAmount = round(($basePrice * 7) / 100, 2);
                        }
                    }
                }
                $netPrice -= $comissionAmount;
            @endphp
            <span class="ml-2 text-primary" style="font-weight: 600;">
                ({{ $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['currency'] }})
                {{number_format($netPrice)}}
            </span>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <div class="fs-14 font-weight-300">Total Gross Amount</div>
        <div class="fs-16 font-weight-500">
            <span class="ml-2 text-primary" style="font-weight: 600;">
                ({{ $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['currency'] }})
                {{ number_format($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][count($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'])-1]['fare']['totalFare']['totalPrice']) }}
            </span>
        </div>
    </div>
</div>
