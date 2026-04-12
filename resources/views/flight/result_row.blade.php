@php
    // ═══ Rules Engine: Blocking Check ═══
    $_blkAirline = $data['pricingInformation'][0]['fare']['validatingCarrierCode'] ?? '';
    $_blkRouteFrom = '';
    $_blkRouteTo = '';
    if (isset($searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0])) {
        $_blkRouteFrom = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['departureLocation'] ?? '';
        $_blkRouteTo = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['arrivalLocation'] ?? '';
    }
    $_blkCabin = session('cabin_class', 'Y');
    $_isBlocked = \App\Services\RulesEngineService::isBlocked('sabre', $_blkAirline, $_blkRouteFrom, $_blkRouteTo, $_blkCabin);
@endphp

@if(!$_isBlocked)
<div class="search_result">
    <div class="bg-white hox list-item mb-3 rounded position-relative demo NonStop">

        <div class="m-0 align-items-center row">

            <div class="list-item_start text-center col-md-2">
                <div class="d-flex d-md-block justify-content-center">
                    <div class="list-item_logo">

                        <img class="img-fluid" src="{{ url('airlines_logo') }}/{{ strtolower($data['pricingInformation'][0]['fare']['validatingCarrierCode']) }}.png" alt="{{ strtolower($data['pricingInformation'][0]['fare']['validatingCarrierCode']) }}">
                        <small class="d-block mt-1">
                            @php
                                $airlineInfo = DB::table('airlines')
                                    ->where('iata', $data['pricingInformation'][0]['fare']['validatingCarrierCode'])
                                    ->where('active', 'Y')
                                    ->first();
                                echo $airlineInfo ? $airlineInfo->name : '';
                            @endphp
                        </small>

                    </div>
                </div>
            </div>

            @if (session('flight_type') == 1)
                @include('flight.oneway_list_body')
            @elseif (session('flight_type') == 2)
                @include('flight.round_list_body')
            @else
                @include('flight.multicity_list_body')
            @endif

            <div class="list-item_end title hotel-right clearfix grid-hidden d-flex align-items-center d-md-block mt-2 pt-2 col-md-3 justify-content-center">
                <div class="price-area d-xl-flex align-items-xl-center justify-content-xl-center text-center">
                    <div class="purchase-price fs-18 font-weight-600" style="line-height: 22px">
                        <div class="main-price">
                            {{-- {{$data['pricingInformation'][0]['fare']['totalFare']['currency']}} --}}
                            Gross: ৳ {{ number_format($data['pricingInformation'][0]['fare']['totalFare']['totalPrice']) }}
                        </div>
                        <div class="main-price">
                            @php
                                $netPrice = $data['pricingInformation'][0]['fare']['totalFare']['totalPrice'];
                                $basePrice = $data['pricingInformation'][0]['fare']['totalFare']['equivalentAmount'];

                                // ═══ Rules Engine Commission ═══
                                $_reAirline = $data['pricingInformation'][0]['fare']['validatingCarrierCode'] ?? '';
                                $_reRouteFrom = session('segments.0.origin_airport_code', '');
                                $_reRouteTo = session('segments.0.destination_airport_code', '');
                                // Fallback route from itinerary group description
                                if (empty($_reRouteFrom) && isset($searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0])) {
                                    $_reRouteFrom = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['departureLocation'] ?? '';
                                    $_reRouteTo = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['arrivalLocation'] ?? '';
                                }
                                $_reCabin = session('cabin_class', 'Y');
                                $_reAgentId = Auth::user()->user_type == 2 ? Auth::user()->id : null;

                                $comissionAmount = \App\Services\RulesEngineService::calculateCommission(
                                    'sabre', $_reAirline, $_reRouteFrom, $_reRouteTo, $_reCabin, 'ADT', $_reAgentId, $basePrice
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
                            Net: ৳ {{ number_format($netPrice) }}
                        </div>
                    </div>
                </div>
                <div class="d-flex d-md-block gap-4 ml-auto ms-3 mt-md-2 text-center">
                    <a href="{{url('select/flight')}}/{{$index}}" id="BookNowBtn hox" class="btn btn-primary text-uppercase font-weight-600 fs-13 btn_filters_responsive disable_book_now_cls">
                        Select Flight
                    </a>
                    <a class="fli-det-link text-muted fs-14 gap-2 d-block mt-md-2 d-flex align-items-center justify-content-center mr-2 mr-md-0" data-bs-toggle="collapse" href="#collapse1_{{ $index }}" role="button" aria-expanded="false" aria-controls="collapse1">
                        Flight details
                        <i class="fas fa-chevron-circle-down ml-1 text-success"></i>
                    </a>
                </div>
            </div>
        </div>

        @include('flight.flight_details')

    </div>
</div>
@endif
