<div class="container search-box-container" id="mainCnt">
    <div class="search-box_row justify-content-center">
        <div class="search-box_col" style="padding-bottom: 32px;">
            <div class="search-box">
                <div class="tab-content position-relative" id="myTabContent">
                    <div class="search-tabs d-flex flex-wrap">

                        <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="1" onclick="showOnewayDate()" @if (count($searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions']) == 1) checked @endif>
                            One way
                        </label>
                        <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="2" onclick="showRoundTripDate()" @if (count($searchResults['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions']) != 1) checked @endif>
                            Round trip
                        </label>
                        {{-- <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="3" onclick="showMultiCityDate()">
                            Multi City
                        </label> --}}

                        @php
                            $originCityInfo = DB::table('city_airports')->where('id', session('departure_location_id'))->first();
                            $destinationCityInfo = DB::table('city_airports')->where('id', session('destination_location_id'))->first();
                        @endphp

                        <div class="search-content d-block w-100 pt-3" id="search-content2">
                            <form class="modify-search">
                                <input type="hidden" id="flight_type" value="{{session('flight_type')}}">
                                <div class="search-row row no-gutters position-relative mx-0 mb-4">
                                    <div class="col-lg-6 px-0">
                                        <div class="input-group rounded">
                                            <div class="form-floating flight-form">
                                                <label for="floatingInput">From</label>
                                                <select class="form-control border-bottom-0 border-right flight_from" id="flight_from" name="flight_from">
                                                    <option value="{{session('departure_location_id')}}">{{$originCityInfo->city_name}}-{{$originCityInfo->airport_name}}</option>
                                                </select>
                                            </div>
                                            <span class="input-group-text">
                                                <svg class="bi bi-arrow-left-right" id="oneway-swap"
                                                    width="1.2em" height="1.2em" viewBox="0 0 16 16"
                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10.146 7.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L12.793 11l-2.647-2.646a.5.5 0 0 1 0-.708z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2 11a.5.5 0 0 1 .5-.5H13a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 11zm3.854-9.354a.5.5 0 0 1 0 .708L3.207 5l2.647 2.646a.5.5 0 1 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2.5 5a.5.5 0 0 1 .5-.5h10.5a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <div class="form-floating flight-to">
                                                <label for="floatingInput">To</label>
                                                <select class="form-control border-bottom-0 border-right flight_to" id="flight_to" name="flight_to">
                                                    <option value="{{session('destination_location_id')}}">{{$destinationCityInfo->city_name}}-{{$destinationCityInfo->airport_name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 px-0 position-static">
                                        <div data-t-start data-t-end
                                            class="oneWay-datepicker t-datepicker t-datepicker-modal-oneway d-flex w-100 border-0 h-100 d-block"
                                            id="oneWayDatePicker">
                                            <div class="t-check-in w-100"></div>
                                        </div>

                                        <div data-t-start data-t-end
                                            class="oneWay-datepicker t-datepicker t-datepicker-modal-round d-flex w-100 border-0 d-none"
                                            id="roundDatePicker">
                                            <div class="t-check-in w-100"></div>
                                            <div class="t-check-out w-100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 px-0">
                                        <div class="dropdown travellers-dropdown" id="dropdown-oneway">
                                            <div class="form-floating" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true">
                                                @php
                                                    $totalTravellers = session('adult') + session('child') +  session('infant');
                                                @endphp
                                                <input type="text" class="form-control dropdown-toggle" id="passengers-oneway" value="{{$totalTravellers}} Travelers, {{session('cabin_class')}}" readonly />
                                                <label for="passengers">Traveler(s) cabin</label>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <div class="tab-container">
                                                    <div class="triangle abs"></div>
                                                    <ul class="m-0 p-0">
                                                        <li class="noOf d-flex justify-content-between">
                                                            <span>
                                                                <input type="text" id="oneway-adult-input" class="all-input" readonly value="{{session('adult')}}" />
                                                                <span class="fs-16 font-weight-500">Adult<span>s</span></span>
                                                            </span>
                                                            <div class="spinner d-flex">
                                                                <span id="oneway-adult-minus" class="minus">-</span>
                                                                <span id="oneway-adult-plus" class="plus">+</span>
                                                            </div>
                                                            <input hidden name="adult_members" id="adult_input_one" value="1" />
                                                        </li>
                                                        <li class="noOf d-flex justify-content-between">
                                                            <span>
                                                                <input type="text" id="oneway-child-input" class="all-input" readonly value="{{session('child')}}" />
                                                                <span class="fs-16 font-weight-500">Child</span>
                                                                <span class="cat-info fs-13">2 11 years</span>
                                                            </span>
                                                            <input hidden name="child_members" id="child_input_one" value="0" />
                                                            <div class="spinner d-flex">
                                                                <span id="oneway-child-minus" class="minus" onclick="oneWayChildDec()">-</span>
                                                                <span id="oneway-child-plus" class="plus" onclick="oneWayChildInc()">+</span>
                                                            </div>
                                                        </li>
                                                        <li class="noOf d-flex justify-content-between">
                                                            <div data-child-total="0" class="_child_age_" id="_child_age_"></div>
                                                        </li>
                                                        <li class="noOf d-flex justify-content-between">
                                                            <span>
                                                                <input type="text" id="oneway-infant-input" class="all-input" readonly value="{{session('infant')}}" />
                                                                <span class="fs-16 font-weight-500">Infant</span>
                                                                <span class="cat-info fs-13">Below 2 years</span>
                                                            </span>
                                                            <div class="spinner d-flex">
                                                                <span id="oneway-infant-minus" class="minus">-</span>
                                                                <span id="oneway-infant-plus" class="plus">+</span>
                                                            </div>
                                                            <input hidden name="infant_members" id="infant_input_one" value="0" />
                                                        </li>
                                                    </ul>
                                                    <div class="class-type mt-2">
                                                        <div class="custom-control custom-radio pl-0">
                                                            <input type="radio" id="economy1" name="cabin_class_oneway" value="economy" class="cabin_class_oneway custom-control-input economy1" @if(session('cabin_class') == 'economy') checked @endif/>
                                                            <label class="custom-control-label fs-16 font-weight-500" for="economy1">Economy</label>
                                                        </div>
                                                        <div class="custom-control custom-radio pl-0">
                                                            <input type="radio" id="premiumEconomy1" name="cabin_class_oneway" value="premium_economy" class="cabin_class_oneway custom-control-input premiumEconomy1" @if(session('cabin_class') == 'premium_economy') checked @endif/>
                                                            <label class="custom-control-label fs-16 font-weight-500" for="premiumEconomy1">Premium economy</label>
                                                        </div>
                                                        <div class="custom-control custom-radio pl-0">
                                                            <input type="radio" id="business1" name="cabin_class_oneway" value="business" class="cabin_class_oneway custom-control-input business1" @if(session('cabin_class') == 'business') checked @endif/>
                                                            <label class="custom-control-label fs-16 font-weight-500" for="business1">Business</label>
                                                        </div>
                                                        <div class="custom-control custom-radio pl-0">
                                                            <input type="radio" id="first1" name="cabin_class_oneway" value="first_class" class="cabin_class_oneway custom-control-input first1"  @if(session('cabin_class') == 'first_class') checked @endif/>
                                                            <label class="custom-control-label fs-16 font-weight-500" for="first1">First-Class</label>
                                                        </div>
                                                    </div>
                                                    <input hidden name="classType" id="class_type_one" value="Y" />
                                                    <div class="cat-sel mt-3 text-right">
                                                        <input type="button" class="btn btn-danger w-100"
                                                            onclick="oneWayTotalPassenger()" value="Confirm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="btn-hub-oneway">
                                    <button type="button" style="padding: 0.8rem 2rem;" onclick="searchForFlights()"
                                        id="btn-search-oneway" class="btn btn-primary btn-search">
                                        Search flights
                                        <i class="fas fa-plane-departure"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
