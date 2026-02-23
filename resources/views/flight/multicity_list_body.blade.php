<div class="list-body col-md-7">

    <div class="row">
        <div class="col-12">
            <h6 class="list-hidden mb-1 fs-13 font-weight-500 text-primary">Multi-City</h6>
        </div>
    </div>


    @foreach ($searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'] as $legDescriptionsIndex => $legDescription)

        @php
            $beginAirportCode = $data['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][$legDescriptionsIndex]['beginAirport'];
            $beginAirportInfo = DB::table('city_airports')->where('airport_code', $beginAirportCode)->first();
            $endAirportCode = $data['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][$legDescriptionsIndex]['endAirport'];
            $endAirportInfo = DB::table('city_airports')->where('airport_code', $endAirportCode)->first();

            $legRef = $data['legs'][$legDescriptionsIndex]['ref'];
            $schedulesRef = $searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules'][$legDescriptionsIndex]['ref'];
            $flightTiming = $searchResults['groupedItineraryResponse']['scheduleDescs'][$schedulesRef-1];
        @endphp

        <div class="row">
            <div class="col-4">
                <p class="mb-0 fs-13 font-weight-bold" style="font-weight: 600;">{{$beginAirportInfo->airport_name}}, {{$beginAirportInfo->city_name}}, {{$beginAirportInfo->country_name}} ({{$beginAirportInfo->airport_code}}) </p>
                <p class="mb-0 fs-16">
                    @php
                        $firstDepartureDate = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][$legDescriptionsIndex]['departureDate']." ".$flightTiming['departure']['time'];
                        $firstDepartureDateFormatted = new DateTime($firstDepartureDate);
                        echo $firstDepartureDateFormatted->format('h:ia, d-m-y');
                    @endphp
                </p>
            </div>
            <div class="col-4 text-center">
                <div class="two-dots m-2 text-muted position-relative border-top">
                    <span class="flight-service">
                        <span class="type-text px-2 position-relative">
                            @if(count($searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules']) > 1)
                                {{count($searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules'])}} Stops
                            @else
                                NonStop
                            @endif
                        </span>
                    </span>
                </div>
                <span class="mb-0 text-muted"></span>
            </div>
            <div class="col-4 text-right">
                <p class="mb-0 fs-13 font-weight-bold" style="font-weight: 600;">{{$endAirportInfo->airport_name}}, {{$endAirportInfo->city_name}}, {{$endAirportInfo->country_name}} ({{$endAirportInfo->airport_code}})</p>
                <p class="mb-0 fs-16">
                    @php
                        $legRef = $data['legs'][$legDescriptionsIndex]['ref'];
                        $schedulesArrayCount = count($searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules']);
                        $schedulesRef = $searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules'][$schedulesArrayCount-1]['ref'];
                        $flightTiming = $searchResults['groupedItineraryResponse']['scheduleDescs'][$schedulesRef-1];

                        $secondArrivalDate = $searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][$legDescriptionsIndex]['departureDate']." ".$flightTiming['arrival']['time'];
                        $secondArrivalDateFormatted = new DateTime($secondArrivalDate);

                        if(isset($searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules'][$schedulesArrayCount-1]['departureDateAdjustment'])){
                            $secondArrivalDateFormatted->modify('+' . $searchResults['groupedItineraryResponse']['legDescs'][$legRef-1]['schedules'][$schedulesArrayCount-1]['departureDateAdjustment'] . ' day');
                            echo $secondArrivalDateFormatted->format('h:ia, d-m-y');
                        } elseif(isset($flightTiming['arrival']['dateAdjustment']) && $flightTiming['arrival']['dateAdjustment'] > 0){
                            $secondArrivalDateFormatted->modify('+' . $flightTiming['arrival']['dateAdjustment'] . ' day');
                            echo $secondArrivalDateFormatted->format('h:ia, d-m-y');
                        } else {
                            echo $secondArrivalDateFormatted->format('h:ia, d-m-y');
                        }
                    @endphp
                </p>
            </div>
        </div>

        <hr>

    @endforeach

</div>
