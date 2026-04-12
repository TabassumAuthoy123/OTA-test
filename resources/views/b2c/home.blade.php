@extends('b2c.layouts.master')

@section('title', 'Book Flights at Best Price')
@section('meta_description', 'Book domestic and international flights at the best price. Instant confirmation, secure payment, 24/7 support.')

@section('styles')
    {{-- Select2 (same local version as admin) --}}
    <link href="{{ url('assets') }}/admin-assets/vendor/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
    {{-- tDatePicker --}}
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/css/t-datepicker.min.css"
        rel="stylesheet" />
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/css/themes/t-datepicker-main.css"
        rel="stylesheet" />
    {{-- Search pad core styles (same as admin master loads) --}}
    <link href="{{ url('assets') }}/admin-assets/css/search.css?v=1" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/module-assets/css/booking/search_box.css?v=8" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets') }}/module-assets/css/booking/search_box_custom.min.css?v=8" rel="stylesheet"
        type="text/css" />
    {{-- Homepage overrides (select2 border-radius, input-group height, etc.) --}}
    <link href="{{ url('assets') }}/admin-assets/css/homepage.css" rel="stylesheet" />

    <style>
        /* ── B2C-only: adapt the admin search pad to dark hero context ── */

        /* Hide admin background image & heading */
        .b2c-hero .search_box_container {
            background: none;
            position: relative;
        }

        .b2c-hero .search_box_container .search_bg {
            display: none;
        }

        .b2c-hero .top_part {
            display: none;
        }

        /* Force container to take full width inside flex parent */
        .b2c-hero>.container {
            width: 100%;
        }

        /* Center the search pad and cap its width */
        .b2c-hero .search_box_container {
            max-width: 1140px;
            margin: 0 auto;
        }

        /* White card wrapper on dark hero */
        .b2c-hero .search-box .tab-content {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 30px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        /* Search button color */
        .b2c-hero .btn-search {
            background: #084277;
            border-color: #084277;
        }

        /* Ensure Select2 fills width */
        .b2c-hero .select2-container {
            width: 100% !important;
        }

        /* Traveller dropdown menu - prevent clipping */
        .b2c-hero .travellers-dropdown {
            overflow: visible !important;
        }

        /* ── Pax Dropdown (GoZayaan-style) ── */
        .pax-dropdown-menu {
            right: 0 !important;
            left: auto !important;
            min-width: 320px;
            padding: 0 !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
        }

        .pax-dropdown-body {
            padding: 16px 20px;
        }

        .pax-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .pax-row:last-of-type {
            border-bottom: none;
        }

        .pax-info {
            display: flex;
            flex-direction: column;
        }

        .pax-label {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a2e;
        }

        .pax-desc {
            font-size: 12px;
            color: #888;
            margin-top: 1px;
        }

        .pax-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pax-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1.5px solid #ccc;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            color: #555;
            transition: all 0.2s;
        }

        .pax-btn:hover {
            border-color: #084277;
            color: #084277;
        }

        .pax-count {
            width: 28px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 16px;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Class Row */
        .pax-class-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0 8px;
            border-top: 1px solid #f0f0f0;
        }

        .pax-class-label {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a2e;
            white-space: nowrap;
        }

        .pax-class-options {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pax-class-option {
            cursor: pointer;
            font-size: 13px;
            padding: 4px 12px;
            border: 1.5px solid #ddd;
            border-radius: 20px;
            color: #555;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pax-class-option input {
            display: none;
        }

        .pax-class-option:has(input:checked) {
            background: #084277;
            color: #fff;
            border-color: #084277;
        }

        /* Done Button */
        .pax-done-row {
            text-align: right;
            padding-top: 10px;
        }

        .pax-done-btn {
            background: #f5a623;
            color: #fff;
            border: none;
            padding: 8px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .pax-done-btn:hover {
            background: #e09515;
        }

        /* Add spacing between search cells (padding instead of gap to avoid wrapping) */
        .b2c-hero .search-row>[class*="col-"] {
            padding: 0 4px !important;
        }

        /* ── Date Picker Overrides (match pax dropdown style) ── */
        .b2c-hero .t-datepicker-day {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
            padding: 12px 0 !important;
        }

        .b2c-hero .t-check-in,
        .b2c-hero .t-check-out {
            border-color: transparent !important;
        }

        .b2c-hero .t-date-title {
            color: #084277 !important;
            font-weight: 700;
            font-size: 15px;
        }

        .b2c-hero .t-start,
        .b2c-hero .t-end,
        .b2c-hero .t-end-limit {
            background: #084277 !important;
            color: #fff !important;
        }

        .b2c-hero .t-range {
            background: #e8f0fe !important;
            color: #084277 !important;
        }

        .b2c-hero .t-range-limit {
            background: #cde0f7 !important;
        }

        .b2c-hero .t-range-limit.t-start,
        .b2c-hero .t-range-limit.t-end {
            background: #084277 !important;
            color: #fff !important;
        }

        .b2c-hero .t-hover-day,
        .b2c-hero .t-hover-day-content,
        .b2c-hero .t-hover-day:hover {
            background: #0a5694 !important;
            color: #fff !important;
        }

        .b2c-hero .t-hover-day::after {
            border-top-color: #0a5694 !important;
        }

        .b2c-hero .t-today {
            background: transparent !important;
            color: #084277 !important;
            font-weight: 700 !important;
            border-radius: 4px;
            position: relative;
            box-shadow: inset 0 0 0 2px #084277;
        }

        .b2c-hero .t-today .t-hover-day-content {
            display: none !important;
        }

        .b2c-hero .t-today::after {
            content: '' !important;
            width: 5px !important;
            height: 5px !important;
            background: #084277 !important;
            border: none !important;
            border-radius: 50% !important;
            position: absolute !important;
            bottom: 4px !important;
            left: calc(50% - 2.5px) !important;
            top: auto !important;
            right: auto !important;
        }

        .b2c-hero .t-today:hover {
            background: #084277 !important;
            color: #fff !important;
        }

        .b2c-hero .t-today:hover::after {
            background: #fff !important;
        }

        .b2c-hero .t-highlighted {
            color: #084277 !important;
        }

        .b2c-hero .t-day,
        .b2c-hero .t-range,
        .b2c-hero .t-start,
        .b2c-hero .t-end,
        .b2c-hero .t-disabled {
            border-color: #fff !important;
        }

        .b2c-hero .t-datepicker-day {
            background: #fff !important;
        }

        .b2c-hero .t-table-condensed th {
            color: #888;
            font-weight: 500;
            font-size: 12px;
        }

        .b2c-hero .t-next,
        .b2c-hero .t-prev {
            color: #084277;
            font-weight: 700;
        }
    </style>
@endsection

@section('content')

    {{-- ━━━ HERO SECTION ━━━ --}}
    <section class="b2c-hero">
        <div class="container">
            <div class="b2c-hero-content">
                {{-- Badge --}}
                <div class="b2c-hero-badge">
                    <i class="fas fa-star"></i>
                    {{ $siteSettings['hero_badge'] ?? 'Trusted by 10,000+ travelers' }}
                </div>

                {{-- Title --}}
                <h1 class="b2c-hero-title">
                    {!! $siteSettings['hero_title'] ?? 'Find & Book <span>Best Flights</span><br>At Unbeatable Prices' !!}
                </h1>
            </div>{{-- close b2c-hero-content (max-width 800px) --}}


            {{-- ═══ UNIFIED SEARCH PAD (full container width like admin) ═══ --}}
            <div class="search_box_container">
                <div data-airport-url="#">
                    <div class="search-box p-2">
                        <div class="tab-content position-relative">
                            <div class="search-tabs d-flex flex-wrap">

                                <label
                                    class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="1" onclick="showOnewayDate()" checked>
                                    One-Way
                                </label>
                                <label
                                    class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="2" onclick="showRoundTripDate()">
                                    Round-Trip
                                </label>
                                <label
                                    class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="3" onclick="showMultiCityDate()">
                                    Multi-City
                                </label>

                                <div class="search-content d-block w-100 pt-3" id="search-content2">
                                    <form class="modify-search">
                                        <input type="hidden" id="flight_type" value="1">
                                        <div class="search-row row no-gutters position-relative mx-0 mb-4">
                                            <div class="col-lg-5 px-0">
                                                <div class="input-group rounded">
                                                    <div class="form-floating flight-form">
                                                        <label for="flight_from">From</label>
                                                        <select
                                                            class="form-control border-bottom-0 border-right flight_from"
                                                            id="flight_from"></select>
                                                    </div>
                                                    <span class="input-group-text">
                                                        <img src="{{ url('assets') }}/admin-assets/img/arrow-symbol.png"
                                                            id="oneway-swap">
                                                    </span>
                                                    <div class="form-floating flight-to">
                                                        <label for="flight_to">To</label>
                                                        <select class="form-control border-bottom-0 border-right flight_to"
                                                            id="flight_to"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 px-0 position-static" id="departureDateCol">
                                                <div data-t-start data-t-end data-departure="Departure" data-return="Return"
                                                    class="oneWay-datepicker t-datepicker t-datepicker-modal-oneway d-flex w-100 border-0 h-100 d-block"
                                                    id="oneWayDatePicker">
                                                    <div class="t-check-in"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 px-0" id="returnDateCol">
                                                <!-- Return date placeholder for one-way mode -->
                                                <div class="return-date-placeholder h-100 d-flex flex-column justify-content-center px-3"
                                                    id="returnDatePlaceholder" onclick="switchToRoundTrip()"
                                                    style="cursor:pointer;">
                                                    <span class="fw-bold text-uppercase"
                                                        style="font-size:12px; color:#1a1a6c;">Return Date</span>
                                                    <span style="font-size:13px; color:#888;">Save more on return
                                                        flight</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 px-0 position-static d-none" id="roundDateCol">
                                                <div data-t-start data-t-end data-departure="Departure" data-return="Return"
                                                    class="oneWay-datepicker t-datepicker t-datepicker-modal-round d-flex w-100 border-0 h-100 d-block"
                                                    id="roundDatePicker">
                                                    <div class="t-check-in w-100"></div>
                                                    <div class="t-check-out w-100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 px-0">
                                                <div class="dropdown travellers-dropdown" id="dropdown-oneway">
                                                    <div class="form-floating" id="dropdownMenuButton"
                                                        data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <input type="text" class="form-control dropdown-toggle"
                                                            id="passengers-oneway" value="1 Travelers, Economy" readonly />
                                                        <label for="passengers">Traveler(s) cabin</label>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-right pax-dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <div class="pax-dropdown-body">
                                                            {{-- Adults --}}
                                                            <div class="pax-row">
                                                                <div class="pax-info">
                                                                    <span class="pax-label">Adults</span>
                                                                    <span class="pax-desc">12 years and above</span>
                                                                </div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus"
                                                                        id="oneway-adult-minus">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <input type="text" id="oneway-adult-input"
                                                                        class="pax-count" readonly value="1" />
                                                                    <button type="button" class="pax-btn pax-plus"
                                                                        id="oneway-adult-plus">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <input hidden name="adult_members" id="adult_input_one"
                                                                    value="1" />
                                                            </div>
                                                            {{-- Children --}}
                                                            <div class="pax-row">
                                                                <div class="pax-info">
                                                                    <span class="pax-label">Children</span>
                                                                    <span class="pax-desc">2–11 years</span>
                                                                </div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus"
                                                                        id="oneway-child-minus" onclick="oneWayChildDec()">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <input type="text" id="oneway-child-input"
                                                                        class="pax-count" readonly value="0" />
                                                                    <button type="button" class="pax-btn pax-plus"
                                                                        id="oneway-child-plus" onclick="oneWayChildInc()">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <input hidden name="child_members" id="child_input_one"
                                                                    value="0" />
                                                            </div>
                                                            {{-- Child ages container --}}
                                                            <div data-child-total="0" class="_child_age_" id="_child_age_">
                                                            </div>
                                                            {{-- Infant --}}
                                                            <div class="pax-row">
                                                                <div class="pax-info">
                                                                    <span class="pax-label">Infant</span>
                                                                    <span class="pax-desc">Below 2 years</span>
                                                                </div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus"
                                                                        id="oneway-infant-minus">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <input type="text" id="oneway-infant-input"
                                                                        class="pax-count" readonly value="0" />
                                                                    <button type="button" class="pax-btn pax-plus"
                                                                        id="oneway-infant-plus">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <input hidden name="infant_members" id="infant_input_one"
                                                                    value="0" />
                                                            </div>
                                                            {{-- Class Selection --}}
                                                            <div class="pax-class-row">
                                                                <span class="pax-class-label">Class</span>
                                                                <div class="pax-class-options">
                                                                    <label class="pax-class-option">
                                                                        <input type="radio" id="economy1"
                                                                            name="cabin_class_oneway" value="economy"
                                                                            class="cabin_class_oneway" checked />
                                                                        <span>Economy</span>
                                                                    </label>
                                                                    <label class="pax-class-option">
                                                                        <input type="radio" id="business1"
                                                                            name="cabin_class_oneway" value="business"
                                                                            class="cabin_class_oneway" />
                                                                        <span>Business</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <input hidden name="classType" id="class_type_one" value="Y" />
                                                            {{-- Done Button --}}
                                                            <div class="pax-done-row">
                                                                <button type="button" class="pax-done-btn"
                                                                    onclick="oneWayTotalPassenger()">Done</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 text-end">
                                                <button type="button" id="add_another_city"
                                                    class="btn btn-primary multicity-btn d-none">
                                                    <i class="far fa-plus-square"></i> Add Another City
                                                </button>
                                            </div>
                                        </div>

                                        <div id="btn-hub-oneway" class="text-center">
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

            <div class="b2c-hero-content">{{-- reopen for trust stats --}}
                {{-- Trust Stats --}}
                <div class="b2c-trust-stats">
                    <div class="b2c-stat">
                        <div class="b2c-stat-number">50+</div>
                        <div class="b2c-stat-label">Airlines</div>
                    </div>
                    <div class="b2c-stat">
                        <div class="b2c-stat-number">10K+</div>
                        <div class="b2c-stat-label">Happy Travelers</div>
                    </div>
                    <div class="b2c-stat">
                        <div class="b2c-stat-number">24/7</div>
                        <div class="b2c-stat-label">Support</div>
                    </div>
                    <div class="b2c-stat">
                        <div class="b2c-stat-number">100%</div>
                        <div class="b2c-stat-label">Secure Payments</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ━━━ AIRLINE PARTNERS LOGO STRIP ━━━ --}}
    <section class="b2c-airlines">
        <div class="container">
            <div class="b2c-airlines-header">
                <span>✈️ Trusted Airline Partners</span>
            </div>
            <div style="overflow: hidden;">
                <div class="b2c-airlines-track">
                    {{-- First set --}}
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#006747;">
                        <span>🇧🇩 Biman Bangladesh</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00487C;">
                        <span>US-Bangla Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#D71921;">
                        <span>Emirates ✈</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#5C0632;">
                        <span>Qatar Airways</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#E81932;">
                        <span>Turkish Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#1A3768;">
                        <span>Singapore Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00467F;">
                        <span>Malaysia Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#003876;">
                        <span>IndiGo</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00B2A9;">
                        <span>Air Astra</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#2B3990;">
                        <span>NovoAir</span>
                    </div>
                    {{-- Duplicate set for seamless scrolling --}}
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#006747;">
                        <span>🇧🇩 Biman Bangladesh</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00487C;">
                        <span>US-Bangla Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#D71921;">
                        <span>Emirates ✈</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#5C0632;">
                        <span>Qatar Airways</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#E81932;">
                        <span>Turkish Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#1A3768;">
                        <span>Singapore Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00467F;">
                        <span>Malaysia Airlines</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#003876;">
                        <span>IndiGo</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#00B2A9;">
                        <span>Air Astra</span>
                    </div>
                    <div class="b2c-airline-logo" style="font-weight:700; font-size:0.8rem; color:#2B3990;">
                        <span>NovoAir</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ━━━ DEALS SECTION ━━━ --}}
    @if($promotions->count() > 0)
        <section class="b2c-section" id="deals">
            <div class="container">
                <div class="b2c-section-header">
                    <span class="b2c-section-tag">🔥 Hot Deals</span>
                    <h2 class="b2c-section-title">Exclusive Offers & Discounts</h2>
                    <p class="b2c-section-subtitle">Save big on your next flight with exclusive deals</p>
                </div>

                <div class="b2c-deals-scroll">
                    @foreach($promotions as $promo)
                        <div class="b2c-deal-card">
                            <div class="b2c-deal-image"
                                style="background: {{ $promo->badge_color ?? 'var(--b2c-gradient-accent)' }}">
                                @if($promo->image)
                                    <img src="{{ $promo->image }}" alt="{{ $promo->title }}">
                                @else
                                    <i class="fas fa-percentage" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                                @endif
                                @if($promo->discount_text)
                                    <span class="b2c-deal-badge" style="background: {{ $promo->badge_color ?? '#dc3545' }}">
                                        {{ $promo->discount_text }}
                                    </span>
                                @endif
                            </div>
                            <div class="b2c-deal-body">
                                <div class="b2c-deal-title">{{ $promo->title }}</div>
                                <div class="b2c-deal-desc">{{ Str::limit($promo->description, 80) }}</div>
                                @if($promo->link)
                                    <a href="{{ $promo->link }}" class="b2c-deal-link">
                                        Learn More <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ━━━ HOW IT WORKS ━━━ --}}
    <section class="b2c-section" style="background: var(--b2c-bg);">
        <div class="container">
            <div class="b2c-section-header">
                <span class="b2c-section-tag">📋 How It Works</span>
                <h2 class="b2c-section-title">Book Your Flight in 4 Easy Steps</h2>
                <p class="b2c-section-subtitle">Simple, fast, and hassle-free booking experience</p>
            </div>

            <div class="b2c-steps-grid">
                <div class="b2c-step-card">
                    <div class="b2c-step-number" data-step="1"
                        style="background: rgba(14,165,233,0.1); color: var(--b2c-accent);">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="b2c-step-title">Search Flights</h3>
                    <p class="b2c-step-desc">Enter your origin, destination, and travel dates to find available flights.</p>
                </div>

                <div class="b2c-step-card">
                    <div class="b2c-step-number" data-step="2"
                        style="background: rgba(245,158,11,0.1); color: var(--b2c-cta);">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3 class="b2c-step-title">Compare & Select</h3>
                    <p class="b2c-step-desc">Compare prices, airlines, and timings to find the perfect flight for you.</p>
                </div>

                <div class="b2c-step-card">
                    <div class="b2c-step-number" data-step="3" style="background: rgba(99,102,241,0.1); color: #6366F1;">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="b2c-step-title">Secure Payment</h3>
                    <p class="b2c-step-desc">Pay securely with bKash, Nagad, credit card, or bank transfer.</p>
                </div>

                <div class="b2c-step-card">
                    <div class="b2c-step-number" data-step="4"
                        style="background: rgba(16,185,129,0.1); color: var(--b2c-success);">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <h3 class="b2c-step-title">Fly & Enjoy!</h3>
                    <p class="b2c-step-desc">Receive instant e-ticket confirmation and enjoy your journey.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ━━━ POPULAR ROUTES ━━━ --}}
    @if($popularRoutes->count() > 0)
        <section class="b2c-section">
            <div class="container">
                <div class="b2c-section-header">
                    <span class="b2c-section-tag">✈️ Popular Destinations</span>
                    <h2 class="b2c-section-title">Trending Flight Routes</h2>
                    <p class="b2c-section-subtitle">Most searched routes by our travelers</p>
                </div>

                <div class="b2c-routes-grid">
                    @foreach($popularRoutes as $route)
                        <a href="{{ url('/flights/search?origin=' . $route->origin_code . '&destination=' . $route->destination_code) }}"
                            class="b2c-route-card">
                            <div class="b2c-route-icon">
                                @if($route->image)
                                    <img src="{{ $route->image }}" alt="{{ $route->destination_city }}">
                                @else
                                    <i class="fas fa-plane"></i>
                                @endif
                            </div>
                            <div class="b2c-route-name">{{ $route->origin_city }} → {{ $route->destination_city }}</div>
                            <div class="b2c-route-code">{{ $route->origin_code }} → {{ $route->destination_code }}</div>
                            <div class="b2c-route-price">
                                ৳{{ number_format($route->starting_price) }}
                                <small>starting from</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ━━━ DESTINATION INSPIRATION GALLERY ━━━ --}}
    <section class="b2c-section" style="background: var(--b2c-bg);">
        <div class="container">
            <div class="b2c-section-header">
                <span class="b2c-section-tag">🗺️ Explore Destinations</span>
                <h2 class="b2c-section-title">Top Travel Destinations</h2>
                <p class="b2c-section-subtitle">Get inspired for your next adventure</p>
            </div>

            <div class="b2c-destinations-grid">
                {{-- Featured Card --}}
                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card b2c-dest-featured">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80" alt="Dubai">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: var(--b2c-cta);">Popular</span>
                        <div class="b2c-dest-name">Dubai</div>
                        <div class="b2c-dest-country">United Arab Emirates</div>
                        <div class="b2c-dest-price">৳35,000 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card">
                    <img src="https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=600&q=80" alt="Bangkok">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: var(--b2c-success);">Trending</span>
                        <div class="b2c-dest-name">Bangkok</div>
                        <div class="b2c-dest-country">Thailand</div>
                        <div class="b2c-dest-price">৳22,500 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card">
                    <img src="https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=600&q=80" alt="Singapore">
                    <div class="b2c-destination-overlay">
                        <div class="b2c-dest-name">Singapore</div>
                        <div class="b2c-dest-country">Singapore</div>
                        <div class="b2c-dest-price">৳28,000 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card">
                    <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600&q=80" alt="Kolkata">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: #EF4444;">Hot Deal</span>
                        <div class="b2c-dest-name">Kolkata</div>
                        <div class="b2c-dest-country">India</div>
                        <div class="b2c-dest-price">৳8,500 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card">
                    <img src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=600&q=80" alt="Maldives">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: #8B5CF6;">Luxury</span>
                        <div class="b2c-dest-name">Maldives</div>
                        <div class="b2c-dest-country">Maldives</div>
                        <div class="b2c-dest-price">৳45,000 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card b2c-dest-featured">
                    <img src="https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=800&q=80" alt="Kuala Lumpur">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: var(--b2c-accent);">Best Value</span>
                        <div class="b2c-dest-name">Kuala Lumpur</div>
                        <div class="b2c-dest-country">Malaysia</div>
                        <div class="b2c-dest-price">৳18,000 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>

                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
                    class="b2c-destination-card">
                    <img src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=600&q=80" alt="Chennai">
                    <div class="b2c-destination-overlay">
                        <span class="b2c-dest-badge" style="background: #F97316;">New Route</span>
                        <div class="b2c-dest-name">Chennai</div>
                        <div class="b2c-dest-country">India</div>
                        <div class="b2c-dest-price">৳12,000 <small>starting from</small></div>
                        <div class="b2c-dest-explore"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ━━━ WHY CHOOSE US ━━━ --}}
    <section class="b2c-section">
        <div class="container">
            <div class="b2c-section-header">
                <span class="b2c-section-tag">⭐ Why Us</span>
                <h2 class="b2c-section-title">Why Travelers Choose Us</h2>
                <p class="b2c-section-subtitle">We deliver the best travel experience</p>
            </div>

            <div class="b2c-features-grid">
                <div class="b2c-feature-card">
                    <div class="b2c-feature-icon" style="background: rgba(14, 165, 233, 0.1); color: var(--b2c-accent);">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="b2c-feature-title">Instant Booking</h3>
                    <p class="b2c-feature-desc">Get your flight confirmed in seconds with real-time availability.</p>
                </div>

                <div class="b2c-feature-card">
                    <div class="b2c-feature-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--b2c-success);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3 class="b2c-feature-title">Best Price Guaranteed</h3>
                    <p class="b2c-feature-desc">Compare fares from multiple airlines and get the lowest price.</p>
                </div>

                <div class="b2c-feature-card">
                    <div class="b2c-feature-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--b2c-cta);">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="b2c-feature-title">24/7 Support</h3>
                    <p class="b2c-feature-desc">Our support team is always ready to help you, day or night.</p>
                </div>

                <div class="b2c-feature-card">
                    <div class="b2c-feature-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366F1;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="b2c-feature-title">Secure Payment</h3>
                    <p class="b2c-feature-desc">Pay safely with bKash, Nagad, credit cards, and more.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ━━━ EMI / PAYMENT OPTIONS ━━━ --}}
    <section class="b2c-emi-section">
        <div class="container">
            <div class="b2c-emi-wrapper">
                <div class="b2c-emi-content">
                    <div class="b2c-emi-tag">
                        <i class="fas fa-wallet"></i> Flexible Payment
                    </div>
                    <h2 class="b2c-emi-title">Pay Your Way with <span>0% EMI</span> & More</h2>
                    <p class="b2c-emi-desc">
                        Book now, pay later! We offer flexible payment options including 0% EMI on credit cards,
                        mobile wallets, and bank transfers. Travel without financial stress.
                    </p>
                    <div class="b2c-emi-features">
                        <div class="b2c-emi-feature">
                            <i class="fas fa-check"></i>
                            <span>0% EMI available on all major credit cards</span>
                        </div>
                        <div class="b2c-emi-feature">
                            <i class="fas fa-check"></i>
                            <span>Pay with bKash, Nagad, Rocket & more</span>
                        </div>
                        <div class="b2c-emi-feature">
                            <i class="fas fa-check"></i>
                            <span>Instant payment confirmation & e-ticket</span>
                        </div>
                        <div class="b2c-emi-feature">
                            <i class="fas fa-check"></i>
                            <span>100% secure with SSL encryption</span>
                        </div>
                    </div>
                </div>

                <div class="b2c-emi-cards">
                    <div class="b2c-emi-card">
                        <div class="b2c-emi-card-icon" style="background: rgba(245,158,11,0.15);">
                            <i class="fas fa-credit-card" style="color: var(--b2c-cta);"></i>
                        </div>
                        <div class="b2c-emi-card-title">Credit Card EMI</div>
                        <div class="b2c-emi-card-sub">0% interest up to 12 months</div>
                    </div>

                    <div class="b2c-emi-card">
                        <div class="b2c-emi-card-icon" style="background: rgba(233,30,99,0.15);">
                            <span style="font-weight:800; font-size:1.1rem; color:#E2136E;">b</span>
                        </div>
                        <div class="b2c-emi-card-title">bKash</div>
                        <div class="b2c-emi-card-sub">Instant mobile payment</div>
                    </div>

                    <div class="b2c-emi-card">
                        <div class="b2c-emi-card-icon" style="background: rgba(255,107,0,0.15);">
                            <span style="font-weight:800; font-size:1.1rem; color:#F6921E;">N</span>
                        </div>
                        <div class="b2c-emi-card-title">Nagad</div>
                        <div class="b2c-emi-card-sub">Fast & secure payment</div>
                    </div>

                    <div class="b2c-emi-card">
                        <div class="b2c-emi-card-icon" style="background: rgba(14,165,233,0.15);">
                            <i class="fas fa-university" style="color: var(--b2c-accent);"></i>
                        </div>
                        <div class="b2c-emi-card-title">Bank Transfer</div>
                        <div class="b2c-emi-card-sub">Direct bank deposit</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ━━━ TESTIMONIALS ━━━ --}}
    @if($testimonials->count() > 0)
        <section class="b2c-section" style="background: var(--b2c-bg);">
            <div class="container">
                <div class="b2c-section-header">
                    <span class="b2c-section-tag">💬 Reviews</span>
                    <h2 class="b2c-section-title">What Our Travelers Say</h2>
                </div>

                <div class="b2c-testimonials-grid">
                    @foreach($testimonials as $testimonial)
                        <div class="b2c-testimonial-card">
                            <div class="b2c-testimonial-stars">
                                @for($i = 0; $i < $testimonial->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = $testimonial->rating; $i < 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                            <p class="b2c-testimonial-text">"{{ $testimonial->review }}"</p>
                            <div class="b2c-testimonial-author">
                                <div class="b2c-testimonial-avatar">
                                    @if($testimonial->customer_photo)
                                        <img src="{{ $testimonial->customer_photo }}" alt="{{ $testimonial->customer_name }}"
                                            style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($testimonial->customer_name, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="b2c-testimonial-name">{{ $testimonial->customer_name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ━━━ CONTACT & SUPPORT BANNER ━━━ --}}
    <section class="b2c-support-section">
        <div class="container">
            <div class="b2c-section-header">
                <span class="b2c-section-tag">📞 Need Help?</span>
                <h2 class="b2c-section-title">We're Here to Help You</h2>
                <p class="b2c-section-subtitle">Reach out to us anytime — our support team is available 24/7</p>
            </div>

            <div class="b2c-support-grid">
                <a href="tel:+8801XXXXXXXXX" class="b2c-support-card b2c-sc-phone">
                    <div class="b2c-support-icon" style="background: rgba(16,185,129,0.1); color: var(--b2c-success);">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="b2c-support-title">Call Us</div>
                    <div class="b2c-support-value">+880 1XXX-XXXXXX</div>
                    <div class="b2c-support-sub">Available 24/7 for your queries</div>
                </a>

                <a href="https://wa.me/8801XXXXXXXXX" target="_blank" class="b2c-support-card b2c-sc-whatsapp">
                    <div class="b2c-support-icon" style="background: rgba(37,211,102,0.1); color: #25D366;">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="b2c-support-title">WhatsApp</div>
                    <div class="b2c-support-value" style="color: #25D366;">Chat with Us</div>
                    <div class="b2c-support-sub">Quick response on WhatsApp</div>
                </a>

                <a href="mailto:support@faithtrip.net" class="b2c-support-card b2c-sc-email">
                    <div class="b2c-support-icon" style="background: rgba(14,165,233,0.1); color: var(--b2c-accent);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="b2c-support-title">Email Us</div>
                    <div class="b2c-support-value">support@faithtrip.net</div>
                    <div class="b2c-support-sub">We reply within 30 minutes</div>
                </a>
            </div>

            <div class="b2c-support-response">
                <p><i class="fas fa-headset" style="color: var(--b2c-accent); margin-right: 8px;"></i>
                    Average response time: <strong>under 5 minutes</strong> during business hours</p>
            </div>
        </div>
    </section>

    {{-- ━━━ CTA BANNER ━━━ --}}
    <section class="b2c-section" style="background: var(--b2c-gradient-hero); padding: 60px 0;">
        <div class="container text-center">
            <h2
                style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: #fff; margin-bottom: 12px;">
                Ready for Your Next Adventure?
            </h2>
            <p style="color: rgba(255,255,255,0.6); max-width: 500px; margin: 0 auto 24px;">
                Join thousands of happy travelers. Book your flight now and save big!
            </p>
            <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;" class="b2c-search-btn"
                style="display: inline-flex; width: auto; padding: 14px 40px;">
                <i class="fas fa-plane"></i> Search Flights Now
            </a>
        </div>
    </section>

    {{-- ═══ FAQ SECTION ═══ --}}
    @if(isset($faqs) && $faqs->count())
        <section style="padding: 80px 0; background: #f8f9fa;">
            <div class="container">
                <div style="text-align: center; margin-bottom: 48px;">
                    <span style="background: linear-gradient(135deg, var(--b2c-primary), var(--b2c-secondary));
                                    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                                    font-weight: 600; font-size: 14px; letter-spacing: 1px; text-transform: uppercase;">
                        FAQ
                    </span>
                    <h2
                        style="font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: var(--b2c-heading); margin-top: 8px;">
                        Frequently Asked Questions
                    </h2>
                </div>

                <div style="max-width: 800px; margin: 0 auto;">
                    @foreach($faqs as $idx => $faq)
                        <div class="b2c-faq-item" style="background: #fff; border-radius: 12px; margin-bottom: 12px;
                                            border: 1px solid #e9ecef; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);">
                            <button onclick="toggleFaq(this)"
                                style="width: 100%; padding: 18px 24px; background: none;
                                                border: none; text-align: left; cursor: pointer; display: flex; justify-content: space-between;
                                                align-items: center; font-size: 15px; font-weight: 600; color: #1a1a2e; font-family: var(--font-heading);">
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down"
                                    style="transition: transform 0.3s; font-size: 12px; color: #6c757d;"></i>
                            </button>
                            <div class="b2c-faq-answer"
                                style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease, padding 0.3s ease;">
                                <div style="padding: 0 24px 18px; color: #555; font-size: 14px; line-height: 1.7;">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@section('scripts')
    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    {{-- tDatePicker --}}
    <script
        src="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/js/t-datepicker.min.js"></script>
    {{-- Admin search_box.js — handles passenger +/- , tDatePicker init, swap, etc. --}}
    <script src="{{ url('assets') }}/module-assets/js/booking/search_box.js"></script>

    <script>

        // by default load with oneway
        document.addEventListener("DOMContentLoaded", function () {
            // force One-Way mode on page load
            document.querySelector('input[name="flight_type"][value="1"]').checked = true;
            showOnewayDate();
        });

        $('.flight_from').select2({
            placeholder: 'Departure City/Airport',
            minimumInputLength: 2,
            ajax: {
                url: '/b2c/city-airport-search',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { text: item.search_result, id: item.id }
                        })
                    };
                },
                cache: true
            }
        });

        $('.flight_to').select2({
            placeholder: 'Destination City/Airport',
            minimumInputLength: 2,
            ajax: {
                url: '/b2c/city-airport-search',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { text: item.search_result, id: item.id }
                        })
                    };
                },
                cache: true
            }
        });

        $('.preferred_airlines').select2({
            placeholder: 'Preferred Airlines',
            minimumInputLength: 2,
            ajax: {
                url: '/b2c/city-airport-search',
                dataType: 'json',
                delay: 250,
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return { text: item.search_result, id: item.id }
                        })
                    };
                },
                cache: true
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function initDatepickerForRow(row) {
            const $dp = $(row).find(".t-datepicker");

            // remove any old generated markup if cloned
            $dp.find(".t-input").remove();
            $dp.find(".t-table").remove();

            // ensure expected structure exists
            if ($dp.find(".t-check-in").length === 0) {
                $dp.html('<div class="t-check-in"></div>');
            }

            // init plugin for this row
            $dp.tDatePicker({
                autoClose: true,
                durationArrowTop: 200,
                formatDate: "dd-mm-yyyy",
                dateCheckIn: new Date(),
                dateCheckOut: new Date(),
                iconDate: "",
                titleCheckIn: $("[data-departure]").data("departure"),
                titleCheckOut: $("[data-return]").data("return"),
                limitDateRanges: 360,
                limitNextMonth: 12,
            });
        }

        let multiCityRowIndex = 1;
        function createRemovableRow(fromDiv) {
            const clone = fromDiv.cloneNode(true);
            multiCityRowIndex++;

            // Add remove button
            const toCol = clone.querySelector(".flight-to")?.closest(".col-lg-5");
            if (toCol && !toCol.querySelector(".search-row-remove")) {
                const removeBtn = document.createElement("button");
                removeBtn.innerHTML = "<i class='fas fa-times'></i>";
                removeBtn.className = "search-row-remove btn";
                removeBtn.type = "button";
                removeBtn.style.marginLeft = "10px";
                removeBtn.addEventListener("click", function () {
                    clone.remove();
                });
                toCol.appendChild(removeBtn);
            }

            // Remove Travellers + Preferred Airlines
            clone.querySelector("#dropdown-oneway")?.closest(".col-lg-2")?.remove();
            clone.querySelector(".preferred_airlines")?.closest(".col-lg-2")?.remove();

            // Remove any select2 containers inside the clone
            $(clone).find(".select2-container").remove();

            // Make IDs unique
            const fromSelect = clone.querySelector(".flight_from");
            const toSelect = clone.querySelector(".flight_to");

            if (fromSelect) {
                fromSelect.id = "flight_from_" + multiCityRowIndex;
                clone.querySelector('label[for="flight_from"]')?.setAttribute("for", fromSelect.id);
            }
            if (toSelect) {
                toSelect.id = "flight_to_" + multiCityRowIndex;
                clone.querySelector('label[for="flight_to"]')?.setAttribute("for", toSelect.id);
            }

            // Strip select2 internal state from cloned selects
            $(clone).find(".flight_from, .flight_to").each(function () {
                $(this)
                    .removeClass("select2-hidden-accessible")
                    .removeAttr("data-select2-id tabindex aria-hidden")
                    .removeData("select2");

                // also remove select2 ids from options
                $(this).find("option").removeAttr("data-select2-id");
                this.selectedIndex = -1;
            });

            // Re-init select2 ONLY on the new row's selects
            $(clone).find(".flight_from").select2({
                placeholder: "Departure City/Airport",
                minimumInputLength: 2,
                ajax: {
                    url: "/b2c/city-airport-search",
                    dataType: "json",
                    delay: 250,
                    data: function (params) { return { q: params.term }; },
                    processResults: function (data) {
                        return { results: $.map(data, item => ({ text: item.search_result, id: item.id })) };
                    },
                    cache: true
                }
            });

            $(clone).find(".flight_to").select2({
                placeholder: "Destination City/Airport",
                minimumInputLength: 2,
                ajax: {
                    url: "/b2c/city-airport-search",
                    dataType: "json",
                    delay: 250,
                    data: function (params) { return { q: params.term }; },
                    processResults: function (data) {
                        return { results: $.map(data, item => ({ text: item.search_result, id: item.id })) };
                    },
                    cache: true
                }
            });

            return clone;
        }

        document.getElementById("add_another_city").addEventListener("click", function () {
            const original = document.querySelector(".search-row"); // the first one
            const newRow = createRemovableRow(original);
            const allRows = document.querySelectorAll(".search-row");
            const lastRow = allRows[allRows.length - 1];
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            // init datepicker for the new row
            initDatepickerForRow(newRow);
        });

        function showOnewayDate() {
            $("#flight_type").val(1);

            // removing extra row of multicity search
            const allRows = document.querySelectorAll(".search-row");
            for (let i = 1; i < allRows.length; i++) {
                allRows[i].remove();
            }

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-inline-block');
            multicityBtn.classList.add('d-none');

            // show departure + return placeholder columns
            document.getElementById('departureDateCol').classList.remove('d-none');
            document.getElementById('returnDateCol').classList.remove('d-none');
            // hide round-trip combined column
            document.getElementById('roundDateCol').classList.add('d-none');
        }

        function switchToRoundTrip() {
            document.querySelector('input[name="flight_type"][value="2"]').click();
        }

        function showRoundTripDate() {
            $("#flight_type").val(2);

            // removing extra row of multicity search
            const allRows = document.querySelectorAll(".search-row");
            for (let i = 1; i < allRows.length; i++) {
                allRows[i].remove();
            }

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-inline-block');
            multicityBtn.classList.add('d-none');

            // hide departure + return placeholder columns
            document.getElementById('departureDateCol').classList.add('d-none');
            document.getElementById('returnDateCol').classList.add('d-none');
            // show round-trip combined column
            document.getElementById('roundDateCol').classList.remove('d-none');
        }

        function showMultiCityDate() {
            $("#flight_type").val(3);

            // show departure + return placeholder columns (multi-city uses one-way dates)
            document.getElementById('departureDateCol').classList.remove('d-none');
            document.getElementById('returnDateCol').classList.remove('d-none');
            // hide round-trip combined column
            document.getElementById('roundDateCol').classList.add('d-none');

            // adding row for multicity search
            const original = document.querySelector(".search-row");
            const newRow = createRemovableRow(original);
            const allRows = document.querySelectorAll(".search-row");
            const lastRow = allRows[allRows.length - 1];
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            initDatepickerForRow(newRow);

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-none');
            multicityBtn.classList.add('d-inline-block');
        }

        function searchForFlights() {

            var flightType = $("#flight_type").val(); // 1=>Oneway; 2=>Return
            let returnDate = '';

            if (flightType == 3) {
                searchMultiCityFlights();
                return false;
            }

            var departureLocationId = $("#flight_from").val();
            var destinationLocationId = $("#flight_to").val();
            var preferred_airlines = $("#preferred_airlines").val();
            var adult = Number($("#oneway-adult-input").val());
            var child = Number($("#oneway-child-input").val());
            var infant = Number($("#oneway-infant-input").val());
            var cabinClass = $('input.cabin_class_oneway:checked').val();

            if (flightType == 1) {
                var departureDate = document.querySelector('#oneWayDatePicker .t-check-in input[name="t-start"]').value;
            } else {
                var departureDate = document.querySelector('#roundDatePicker .t-check-in input[name="t-start"]').value;
                returnDate = document.querySelector('#roundDatePicker .t-check-out input[name="t-end"]').value;
            }

            if (!departureLocationId) {
                toastr.error("Departure Location is missing");
                return false;
            }
            if (!destinationLocationId) {
                toastr.error("Destination Location is missing");
                return false;
            }
            if (departureDate == '') {
                toastr.error("Departure Date is missing");
                return false;
            }
            if (flightType == 2 && returnDate == '') {
                toastr.error("Return Date is mendatory for Round Trip");
                return false;
            }
            if ((adult + child + infant) <= 0) {
                toastr.error("Please Provide Passenger Information");
                return false;
            }

            if (departureLocationId == destinationLocationId) {
                toastr.error("Departure & Destination Cannot be Same");
                return false;
            }


            $(".page-loader-wrapper").show();

            var formData = new FormData();
            formData.append("flight_type", flightType);
            formData.append("departure_location_id", departureLocationId);
            formData.append("destination_location_id", destinationLocationId);
            formData.append("departure_date", departureDate);
            formData.append("return_date", returnDate);
            formData.append("adult", adult);
            formData.append("child", child);
            formData.append("infant", infant);
            formData.append("preferred_airlines", preferred_airlines);
            formData.append("cabin_class", cabinClass);

            $.ajax({
                data: formData,
                url: "{{ url('/flights/search') }}",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".page-loader-wrapper").hide();
                    window.location.href = "/flights/results";
                },
                error: function (data) {
                    $(".page-loader-wrapper").hide();
                    toastr.error("Someting Went Wrong! Please Try Again");
                }
            });

        }

        function searchMultiCityFlights() {

            const segments = [];
            const rows = document.querySelectorAll(".search-row");

            rows.forEach((row, idx) => {
                const from = $(row).find(".flight_from").val();
                const to = $(row).find(".flight_to").val();

                // tDatePicker generates this input under .t-check-in
                const date = $(row).find('.t-check-in input[name="t-start"]').val();

                // skip completely empty rows
                if (!from && !to && !date) return;

                // basic validation
                if (!from || !to || !date) {
                    toastr.error(`Segment ${idx + 1} is missing From/To/Date`);
                    return false;
                }
                if (from === to) {
                    toastr.error(`Segment ${idx + 1}: From and To cannot be same`);
                    return false;
                }
                segments.push({ from, to, date });
            });

            var flightType = $("#flight_type").val();
            var preferred_airlines = $("#preferred_airlines").val();
            var adult = Number($("#oneway-adult-input").val());
            var child = Number($("#oneway-child-input").val());
            var infant = Number($("#oneway-infant-input").val());
            var cabinClass = $('input.cabin_class_oneway:checked').val();

            if ((adult + child + infant) <= 0) {
                toastr.error("Please Provide Passenger Information");
                return false;
            }

            $(".page-loader-wrapper").show();

            var formData = new FormData();
            formData.append("flight_type", flightType);
            segments.forEach((seg, i) => {
                formData.append(`segments[${i}][from]`, seg.from);
                formData.append(`segments[${i}][to]`, seg.to);
                formData.append(`segments[${i}][date]`, seg.date);
            });
            formData.append("adult", adult);
            formData.append("child", child);
            formData.append("infant", infant);
            formData.append("preferred_airlines", preferred_airlines);
            formData.append("cabin_class", cabinClass);

            $.ajax({
                data: formData,
                url: "{{ url('/flights/search/multi-city') }}",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".page-loader-wrapper").hide();
                    window.location.href = "/flights/results";
                },
                error: function (data) {
                    $(".page-loader-wrapper").hide();
                    toastr.error("Someting Went Wrong! Please Try Again");
                }
            });

        }

        // FAQ Accordion Toggle
        function toggleFaq(btn) {
            const answer = btn.nextElementSibling;
            const icon = btn.querySelector('i');
            const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

            // Close all
            document.querySelectorAll('.b2c-faq-answer').forEach(a => { a.style.maxHeight = '0px'; });
            document.querySelectorAll('.b2c-faq-item button i').forEach(i => { i.style.transform = 'rotate(0)'; });

            if (!isOpen) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>
@endsection