@extends('b2c.layouts.master')

@section('title', 'Book Flights at Best Price')
@section('meta_description', 'Book domestic and international flights at the best price. Instant confirmation, secure payment, 24/7 support.')

@section('styles')
    <link href="{{ url('assets') }}/admin-assets/vendor/select2/dist/css/select2.css" rel="stylesheet" />
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/css/t-datepicker.min.css" rel="stylesheet" />
    <link href="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/css/themes/t-datepicker-main.css" rel="stylesheet" />
    <link href="{{ url('assets') }}/admin-assets/css/search.css?v=1" rel="stylesheet" />
    <link href="{{ url('assets') }}/module-assets/css/booking/search_box.css?v=8" rel="stylesheet" />
    <link href="{{ url('assets') }}/module-assets/css/booking/search_box_custom.min.css?v=8" rel="stylesheet" />
    <link href="{{ url('assets') }}/admin-assets/css/homepage.css" rel="stylesheet" />
    <style>
        /* ── Adapt search pad to ft-search-wrapper ── */
        .ft-search-wrapper .search_box_container { background: none; }
        .ft-search-wrapper .search_bg,
        .ft-search-wrapper .top_part { display: none; }
        .ft-search-wrapper .search-box .tab-content { background: transparent; border-radius: 0; box-shadow: none; padding: 0; }
        .ft-search-wrapper .btn-search { background: #0D1B5E; border-color: #0D1B5E; }
        .ft-search-wrapper .btn-search:hover { background: #1A3A8F; }
        .ft-search-wrapper .select2-container { width: 100% !important; }
        .ft-search-wrapper .travellers-dropdown { overflow: visible !important; }

        /* Pax dropdown */
        .pax-dropdown-menu { right:0!important;left:auto!important;min-width:320px;padding:0!important;border:none!important;border-radius:12px!important;box-shadow:0 8px 30px rgba(0,0,0,.15)!important;overflow:hidden; }
        .pax-dropdown-body { padding:16px 20px; }
        .pax-row { display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0f0; }
        .pax-row:last-of-type { border-bottom:none; }
        .pax-info { display:flex;flex-direction:column; }
        .pax-label { font-weight:600;font-size:14px;color:#1a1a2e; }
        .pax-desc { font-size:12px;color:#888;margin-top:1px; }
        .pax-age-notes { padding:10px 0 4px;border-top:1px solid #f0f0f0; }
        .pax-age-notes p { font-size:11px;color:#888;margin:0 0 6px;line-height:1.5; }
        .pax-controls { display:flex;align-items:center;gap:10px; }
        .pax-btn { width:30px;height:30px;border-radius:50%;border:1.5px solid #ccc;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;color:#555;transition:all .2s; }
        .pax-btn:hover { border-color:#0D1B5E;color:#0D1B5E; }
        .pax-count { width:28px;text-align:center;border:none;background:transparent;font-size:16px;font-weight:600;color:#1a1a2e; }
        .pax-class-row { display:flex;align-items:center;gap:12px;padding:12px 0 8px;border-top:1px solid #f0f0f0; }
        .pax-class-label { font-weight:600;font-size:14px;color:#1a1a2e;white-space:nowrap; }
        .pax-class-options { display:flex;gap:6px;flex-wrap:wrap; }
        .pax-class-option { cursor:pointer;font-size:13px;padding:4px 12px;border:1.5px solid #ddd;border-radius:20px;color:#555;transition:all .2s;display:flex;align-items:center;gap:4px; }
        .pax-class-option input { display:none; }
        .pax-class-option:has(input:checked) { background:#0D1B5E;color:#fff;border-color:#0D1B5E; }
        .pax-done-row { text-align:right;padding-top:10px; }
        .pax-done-btn { background:#F5A623;color:#fff;border:none;padding:8px 32px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer; }
        .pax-done-btn:hover { background:#E09515; }

        /* Datepicker colours */
        .ft-search-wrapper .t-start,
        .ft-search-wrapper .t-end,
        .ft-search-wrapper .t-end-limit { background:#0D1B5E!important;color:#fff!important; }
        .ft-search-wrapper .t-range { background:#e8f0fe!important;color:#0D1B5E!important; }
        .ft-search-wrapper .t-date-title { color:#0D1B5E!important;font-weight:700; }

        /* Return date placeholder */
        .return-date-placeholder { cursor:pointer; }
    </style>
@endsection

@section('content')

{{-- ━━━ HERO ━━━ --}}
@php
    $heroBgImg = ($heroBanners->count() && $heroBanners->first()->photo) ? asset($heroBanners->first()->photo) : null;
@endphp
<section class="ft-b2c-hero"
    @if($heroBgImg)
    style="background-image:linear-gradient(160deg,rgba(13,27,94,.78) 0%,rgba(10,53,114,.65) 100%),url('{{ $heroBgImg }}');background-size:cover;background-position:center;"
    @endif>

    {{-- Hero title --}}
    <div class="container">
        <div class="ft-hero-text">
            <h1 class="ft-hero-title">
                {!! $siteSettings['hero_title'] ?? 'Welcome to <span>FaithTrip!</span>' !!}
            </h1>
            <p class="ft-hero-subtitle">{{ $siteSettings['hero_subtitle'] ?? 'Find Flights, Hotels, Visa & Holidays' }}</p>
        </div>

        {{-- Service Tabs --}}
        <div class="ft-service-tabs">
            <button class="ft-service-tab active" data-service="flight">
                <span class="ft-service-tab-icon"><i class="fas fa-plane"></i></span>
                <span>Flight</span>
            </button>
            <button class="ft-service-tab" data-service="visa">
                <span class="ft-service-tab-icon"><i class="fas fa-passport"></i></span>
                <span>Visa</span>
            </button>
            <button class="ft-service-tab" data-service="holiday">
                <span class="ft-service-tab-icon"><i class="fas fa-umbrella-beach"></i></span>
                <span>Holiday</span>
            </button>
            <button class="ft-service-tab" data-service="hotel">
                <span class="ft-service-tab-icon"><i class="fas fa-hotel"></i></span>
                <span>Hotel</span>
            </button>
            <button class="ft-service-tab" data-service="medical">
                <span class="ft-service-tab-icon"><i class="fas fa-stethoscope"></i></span>
                <span>Medical</span>
            </button>
        </div>

        {{-- ══ FLIGHT SEARCH WIDGET ══ --}}
        <div class="ft-search-wrapper">
            <div class="search_box_container">
                <div data-airport-url="#">
                    <div class="search-box p-0">
                        <div class="tab-content position-relative">
                            <div class="search-tabs d-flex flex-wrap">

                                <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="1" onclick="showOnewayDate()" checked>
                                    One Way
                                </label>
                                <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="2" onclick="showRoundTripDate()">
                                    Round Trip
                                </label>
                                <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                                    <input type="radio" name="flight_type" value="3" onclick="showMultiCityDate()">
                                    Multi City
                                </label>

                                <div class="search-content d-block w-100 pt-3" id="search-content2">
                                    <form class="modify-search">
                                        <input type="hidden" id="flight_type" value="1">
                                        <div class="search-row row no-gutters position-relative mx-0 mb-3">
                                            <div class="col-lg-5 px-0">
                                                <div class="input-group rounded">
                                                    <div class="form-floating flight-form">
                                                        <label for="flight_from">From</label>
                                                        <select class="form-control border-bottom-0 border-right flight_from" id="flight_from"></select>
                                                    </div>
                                                    <span class="input-group-text">
                                                        <img src="{{ url('assets') }}/admin-assets/img/arrow-symbol.png" id="oneway-swap">
                                                    </span>
                                                    <div class="form-floating flight-to">
                                                        <label for="flight_to">To</label>
                                                        <select class="form-control border-bottom-0 border-right flight_to" id="flight_to"></select>
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
                                                <div class="return-date-placeholder h-100 d-flex flex-column justify-content-center px-3"
                                                    id="returnDatePlaceholder" onclick="switchToRoundTrip()" style="cursor:pointer;">
                                                    <span class="fw-bold text-uppercase" style="font-size:12px;color:#1a1a6c;">Return Date</span>
                                                    <span style="font-size:13px;color:#888;">Save more on return flight</span>
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
                                                    <div class="form-floating" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <input type="text" class="form-control dropdown-toggle" id="passengers-oneway" value="1 Travelers, Economy" readonly />
                                                        <label for="passengers">Traveler(s) cabin</label>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-right pax-dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <div class="pax-dropdown-body">
                                                            <div class="pax-row">
                                                                <div class="pax-info"><span class="pax-label">Adults</span><span class="pax-desc">Aged 12+</span></div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus" id="oneway-adult-minus"><i class="fas fa-minus"></i></button>
                                                                    <input type="text" id="oneway-adult-input" class="pax-count" readonly value="1" />
                                                                    <button type="button" class="pax-btn pax-plus" id="oneway-adult-plus"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                                <input hidden name="adult_members" id="adult_input_one" value="1" />
                                                            </div>
                                                            <div class="pax-row">
                                                                <div class="pax-info"><span class="pax-label">Children</span><span class="pax-desc">Aged 2 to 11</span></div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus" id="oneway-child-minus" onclick="oneWayChildDec()"><i class="fas fa-minus"></i></button>
                                                                    <input type="text" id="oneway-child-input" class="pax-count" readonly value="0" />
                                                                    <button type="button" class="pax-btn pax-plus" id="oneway-child-plus" onclick="oneWayChildInc()"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                                <input hidden name="child_members" id="child_input_one" value="0" />
                                                            </div>
                                                            <div data-child-total="0" class="_child_age_" id="_child_age_"></div>
                                                            <div class="pax-row">
                                                                <div class="pax-info"><span class="pax-label">Infant</span><span class="pax-desc">Under 2 years</span></div>
                                                                <div class="pax-controls">
                                                                    <button type="button" class="pax-btn pax-minus" id="oneway-infant-minus"><i class="fas fa-minus"></i></button>
                                                                    <input type="text" id="oneway-infant-input" class="pax-count" readonly value="0" />
                                                                    <button type="button" class="pax-btn pax-plus" id="oneway-infant-plus"><i class="fas fa-plus"></i></button>
                                                                </div>
                                                                <input hidden name="infant_members" id="infant_input_one" value="0" />
                                                            </div>
                                                            <div class="pax-age-notes">
                                                                <p>Your age at time of travel must be valid for the age category booked.</p>
                                                                <p>Age limits and policies for travelling with children may vary so please check with the airline before booking.</p>
                                                            </div>
                                                            <div class="pax-class-row">
                                                                <span class="pax-class-label">Class</span>
                                                                <div class="pax-class-options">
                                                                    <label class="pax-class-option"><input type="radio" id="economy1" name="cabin_class_oneway" value="economy" class="cabin_class_oneway" checked /><span>Economy</span></label>
                                                                    <label class="pax-class-option"><input type="radio" id="business1" name="cabin_class_oneway" value="business" class="cabin_class_oneway" /><span>Business</span></label>
                                                                </div>
                                                            </div>
                                                            <input hidden name="classType" id="class_type_one" value="Y" />
                                                            <div class="pax-done-row">
                                                                <button type="button" class="pax-done-btn" onclick="oneWayTotalPassenger()">Done</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 text-end">
                                                <button type="button" id="add_another_city" class="btn btn-primary multicity-btn d-none">
                                                    <i class="far fa-plus-square"></i> Add Another City
                                                </button>
                                            </div>
                                        </div>

                                        <div id="btn-hub-oneway" class="text-center mt-2">
                                            <button type="button" style="padding:.8rem 2.5rem;" onclick="searchForFlights()"
                                                id="btn-search-oneway" class="btn btn-primary btn-search">
                                                <i class="fas fa-search me-2"></i>Search flights
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fare Types --}}
            <div class="ft-fare-types">
                <label class="ft-fare-type ft-active">
                    <input type="radio" name="fare_type" value="regular" checked> Regular Fare
                </label>
                <label class="ft-fare-type">
                    <input type="radio" name="fare_type" value="umrah"> Umrah Fare
                </label>
                <label class="ft-fare-type">
                    <input type="radio" name="fare_type" value="student"> Student Fare
                </label>
                <label class="ft-fare-type">
                    <input type="radio" name="fare_type" value="senior"> Senior Citizen
                </label>
            </div>
        </div>{{-- /ft-search-wrapper --}}
    </div>{{-- /container --}}
</section>

{{-- ━━━ HERO BANNER CAROUSEL ━━━ --}}
@if($heroBanners->count() > 1)
<section style="background:#0d1b3e;padding:0;">
    <div id="heroBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach($heroBanners as $idx => $hb)
            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                @if($hb->photo)
                    <img src="{{ asset($hb->photo) }}" class="d-block w-100" style="max-height:420px;object-fit:cover;" alt="{{ $hb->title ?? 'Banner' }}">
                @endif
                @if($hb->title || $hb->link)
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center"
                     style="background:rgba(0,0,0,.3);inset:0;border-radius:0;padding:24px;">
                    @if($hb->title)<h3 style="font-size:clamp(1rem,3vw,2rem);font-weight:700;color:#fff;">{{ $hb->title }}</h3>@endif
                    @if($hb->link)<a href="{{ $hb->link }}" class="btn btn-warning btn-sm mt-2 fw-bold px-4">Explore Now</a>@endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        <div class="carousel-indicators" style="bottom:8px;">
            @foreach($heroBanners as $idx => $hb)
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ HOT DEALS ━━━ --}}
@php $dealsToShow = ($hotDeals->count()) ? $hotDeals : $promotions; @endphp
@if($dealsToShow->count())
<section class="ft-section">
    <div class="container">
        <div class="ft-section-head">
            <h2 class="ft-section-title">Hot Deals</h2>
            <div class="ft-section-underline"></div>
        </div>
        <div class="ft-deals-scroll">
            @foreach($dealsToShow as $deal)
            <div class="ft-deal-card">
                @if(($deal->photo ?? null) || ($deal->image ?? null))
                    <img src="{{ asset($deal->photo ?? $deal->image) }}" alt="{{ $deal->title ?? 'Hot Deal' }}">
                @else
                    <div style="height:210px;background:linear-gradient(135deg,#0D1B5E,#1A3A8F);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-fire" style="font-size:3rem;color:rgba(255,255,255,.3);"></i>
                    </div>
                @endif
                @if($deal->title ?? null)
                <div style="padding:12px 14px;background:#fff;">
                    <div style="font-weight:700;color:#0D1B5E;font-size:.9rem;">{{ $deal->title }}</div>
                    @if($deal->description ?? null)
                    <div style="font-size:.8rem;color:#777;margin-top:4px;">{{ Str::limit($deal->description,80) }}</div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ SPECIAL OFFERS (filter: All / Flight / Hotel / Other) ━━━ --}}
@if(isset($specialOffers) && $specialOffers->count())
<section class="ft-section ft-section-alt">
    <div class="container">
        <div class="ft-offers-head">
            <div>
                <h2 class="ft-section-title">Special Offers</h2>
                <div class="ft-section-underline"></div>
            </div>
            <div class="ft-offers-right">
                <button class="ft-filter-btn active" onclick="filterOffers('all',this)">All</button>
                <button class="ft-filter-btn" onclick="filterOffers('flight',this)">Flight</button>
                <button class="ft-filter-btn" onclick="filterOffers('hotel',this)">Hotel</button>
                <button class="ft-filter-btn" onclick="filterOffers('other',this)">Other</button>
                <button class="ft-nav-arrow" onclick="shiftOffers(-1)"><i class="fas fa-arrow-left"></i></button>
                <button class="ft-nav-arrow" onclick="shiftOffers(1)"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="ft-offers-grid" id="offersGrid">
            @foreach($specialOffers as $offer)
            <div class="ft-offer-card ft-visible" data-type="{{ $offer->type ?? 'other' }}">
                @if($offer->photo ?? null)
                    <img src="{{ asset($offer->photo) }}" alt="{{ $offer->title ?? '' }}">
                @else
                    <div style="height:195px;background:linear-gradient(135deg,#0D1B5E,#1A3A8F);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-tag" style="font-size:2.5rem;color:rgba(255,255,255,.3);"></i>
                    </div>
                @endif
                <div class="ft-offer-body">
                    <div class="ft-offer-title">{{ $offer->title ?? 'Special Offer' }}</div>
                    @if($offer->description ?? null)
                    <div class="ft-offer-desc">{{ Str::limit($offer->description,90) }}</div>
                    @endif
                    @if($offer->link ?? null)
                    <a href="{{ $offer->link }}" class="ft-offer-link">LEARN MORE <i class="fas fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ AD BANNER CAROUSEL ━━━ --}}
@if($adBanners->count())
<section style="padding:0;background:#f0f4f8;">
    @if($adBanners->count() === 1)
        @if($adBanners->first()->photo)
        <div style="position:relative;">
            @if($adBanners->first()->link)<a href="{{ $adBanners->first()->link }}" target="_blank">@endif
            <img src="{{ asset($adBanners->first()->photo) }}" style="width:100%;max-height:240px;object-fit:cover;display:block;" alt="{{ $adBanners->first()->title ?? 'AD' }}">
            <span style="position:absolute;top:12px;right:16px;background:rgba(0,0,0,.5);color:#fff;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:4px;">AD</span>
            @if($adBanners->first()->link)</a>@endif
        </div>
        @endif
    @else
    <div id="adBannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @foreach($adBanners as $idx => $ad)
            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                @if($ad->link)<a href="{{ $ad->link }}" target="_blank">@endif
                @if($ad->photo)<img src="{{ asset($ad->photo) }}" class="d-block w-100" style="max-height:240px;object-fit:cover;" alt="{{ $ad->title ?? 'AD' }}">@endif
                @if($ad->link)</a>@endif
                <span style="position:absolute;top:12px;right:16px;background:rgba(0,0,0,.5);color:#fff;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:4px;">AD</span>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#adBannerCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#adBannerCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
    @endif
</section>
@endif

{{-- ━━━ SEARCH TOP AIRLINES ━━━ --}}
<section class="ft-section">
    <div class="container">
        <div class="ft-section-center" style="margin-bottom:28px;">
            <h2 class="ft-section-title" style="text-align:center;">Search Top Airlines</h2>
            <div class="ft-section-underline" style="margin:8px auto 0;"></div>
            <p class="ft-section-subtitle">FaithTrip's user-friendly platform connects you to top airlines instantly. Enjoy a comfortable and hassle-free journey on any destination and get tickets of top airlines easily.</p>
        </div>
        @php
        $airlines = [
            ['name'=>'Biman Bangladesh Airlines','code'=>'BG','color'=>'#006747'],
            ['name'=>'US-Bangla Airlines','code'=>'BS','color'=>'#00487C'],
            ['name'=>'NOVOAIR','code'=>'VQ','color'=>'#2B3990'],
            ['name'=>'Air Astra','code'=>'2A','color'=>'#00B2A9'],
            ['name'=>'Emirates','code'=>'EK','color'=>'#D71921'],
            ['name'=>'Singapore Airlines','code'=>'SQ','color'=>'#1A3768'],
            ['name'=>'Malaysia Airlines','code'=>'MH','color'=>'#00467F'],
            ['name'=>'Qatar Airways','code'=>'QR','color'=>'#5C0632'],
            ['name'=>'Saudia Airlines','code'=>'SV','color'=>'#00843D'],
            ['name'=>'Air India','code'=>'AI','color'=>'#E4002B'],
            ['name'=>'Gulf Air','code'=>'GF','color'=>'#C8102E'],
            ['name'=>'Turkish Airlines','code'=>'TK','color'=>'#E81932'],
            ['name'=>'Thai Airways International','code'=>'TG','color'=>'#4B0082'],
            ['name'=>'Cathay Pacific Airways','code'=>'CX','color'=>'#006564'],
            ['name'=>'China Southern Airlines','code'=>'CZ','color'=>'#3366CC'],
            ['name'=>'SriLankan Airlines','code'=>'UL','color'=>'#1B4F8A'],
            ['name'=>'AirAsia','code'=>'AK','color'=>'#C8102E'],
            ['name'=>'Batik Air','code'=>'ID','color'=>'#E4002B'],
            ['name'=>'IndiGo','code'=>'6E','color'=>'#003876'],
            ['name'=>'Air Arabia','code'=>'G9','color'=>'#EE2E24'],
        ];
        @endphp
        <div class="ft-airlines-grid">
            @foreach($airlines as $airline)
            <a href="#" class="ft-airline-row" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">
                <div class="ft-airline-logo-wrap">
                    <span style="font-family:'Poppins',sans-serif;font-weight:800;font-size:.75rem;color:{{ $airline['color'] }};text-align:center;line-height:1.1;">{{ $airline['code'] }}</span>
                </div>
                <span class="ft-airline-name">{{ $airline['name'] }}</span>
                <i class="fas fa-chevron-right ft-airline-chevron"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ━━━ OUR TOUR PACKAGES ━━━ --}}
@if($popularDestinations->count())
<section class="ft-section ft-section-alt">
    <div class="container">
        <div class="ft-section-head">
            <h2 class="ft-section-title">Our Tour Packages for You</h2>
            <div class="ft-section-underline"></div>
            <p style="color:#777;font-size:.92rem;margin-top:8px;">Discover amazing destinations with FaithTrip. Choose from a wide range of tour packages and get the best offers on international trips.</p>
        </div>
        <div class="ft-tours-grid">
            @foreach($popularDestinations->take(6) as $dest)
            <a href="#" class="ft-tour-card" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">
                @if($dest->image)
                    <img class="ft-tour-img" src="{{ asset($dest->image) }}" alt="{{ $dest->name }}">
                @else
                    <div class="ft-tour-img-ph">
                        <i class="fas fa-map-marker-alt" style="font-size:2.5rem;color:rgba(255,255,255,.3);"></i>
                    </div>
                @endif
                <div class="ft-tour-body">
                    <div class="ft-tour-name">{{ $dest->name }}</div>
                    <div class="ft-tour-stars">
                        <i class="fas fa-star"></i>
                        <span style="color:#777;font-size:.8rem;"> (9 reviews)</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ TOP POPULAR DESTINATIONS CAROUSEL ━━━ --}}
@php
$destFallback = [
    ['name'=>'Iceland','img'=>'https://images.unsplash.com/photo-1531168556467-80aace0d0144?w=600&q=80'],
    ['name'=>'Japan','img'=>'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=600&q=80'],
    ['name'=>'Peru','img'=>'https://images.unsplash.com/photo-1526392060635-9d6019884377?w=600&q=80'],
    ['name'=>'Estonia','img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
    ['name'=>'Maldives','img'=>'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=600&q=80'],
    ['name'=>'Thailand','img'=>'https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=600&q=80'],
    ['name'=>'Singapore','img'=>'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=600&q=80'],
    ['name'=>'Dubai','img'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=600&q=80'],
];
@endphp
<section class="ft-section">
    <div class="container">
        <div class="ft-section-head">
            <h2 class="ft-section-title">Top Popular Destinations</h2>
            <div class="ft-section-underline"></div>
            <p style="color:#777;font-size:.92rem;margin-top:8px;">Discover amazing destinations with FaithTrip. Choose from a wide range of destinations and get the best offers on international trips.</p>
        </div>
        <div class="ft-dest-outer">
            <div class="ft-dest-track" id="destTrack">
                @if($popularDestinations->count())
                    @foreach($popularDestinations as $dest)
                    <div class="ft-dest-card">
                        @if($dest->image)
                            <img src="{{ asset($dest->image) }}" alt="{{ $dest->name }}">
                        @else
                            <div style="width:100%;height:240px;background:linear-gradient(135deg,#0D1B5E,#1A3A8F);"></div>
                        @endif
                        <div class="ft-dest-overlay">
                            <div class="ft-dest-name">{{ $dest->name }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    @foreach($destFallback as $dest)
                    <div class="ft-dest-card">
                        <img src="{{ $dest['img'] }}" alt="{{ $dest['name'] }}">
                        <div class="ft-dest-overlay"><div class="ft-dest-name">{{ $dest['name'] }}</div></div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="ft-dest-dots" id="destDots"></div>
    </div>
</section>

{{-- ━━━ BUILD AND GROW CTA ━━━ --}}
<section class="ft-partner-cta">
    <div class="container">
        <div class="ft-partner-inner">
            <div>
                <h2 class="ft-partner-title">Build and Grow with FaithTrip</h2>
                <p class="ft-partner-desc">Everything you need to reach more customers and grow your business in one place.</p>
            </div>
            <a href="#" class="ft-partner-btn">Become a Partner</a>
        </div>
    </div>
</section>

{{-- ━━━ TOP DOMESTIC & INTERNATIONAL ROUTES ━━━ --}}
<section class="ft-section">
    <div class="container">
        <div class="ft-section-center" style="text-align:center;margin-bottom:28px;">
            <h2 class="ft-section-title" style="text-align:center;">Top Domestic &amp; International Routes</h2>
            <div class="ft-section-underline" style="margin:8px auto 0;"></div>
            <p class="ft-section-subtitle">Travel your way with FaithTrip. Choose business or economy class flights on domestic and international routes from hundreds of airlines.</p>
        </div>
        <div class="ft-routes-tabs">
            <button class="ft-routes-tab active" id="tabDomestic" onclick="switchRoutes('domestic')">Domestic</button>
            <button class="ft-routes-tab" id="tabInternational" onclick="switchRoutes('international')">International</button>
        </div>
        @php
        $domesticFallback = [
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Chittagong','to_code'=>'CGP','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport'=>'Chittagong - Shah Amanat Int.'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Sylhet','to_code'=>'ZYL','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport'=>'Sylhet - Osmani Int.'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>"Cox's Bazar",'to_code'=>'CXB','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport',"Cox's Bazar Airport"],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Jessore','to_code'=>'JSR','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport'=>'Jessore Airport'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Rajshahi','to_code'=>'RJH','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport'=>'Rajshahi Airport'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Saidpur','to_code'=>'SPD','from_airport'=>'Dhaka - Hazrat Shahjalal Int.','to_airport'=>'Saidpur - Saidpur Airport'],
        ];
        @endphp
        <div id="routesDomestic">
            <div class="ft-routes-grid">
                @if(isset($domesticRoutes) && $domesticRoutes->count())
                    @foreach($domesticRoutes as $r)
                    <a href="{{ url('/flights/search?origin='.$r->origin_code.'&destination='.$r->destination_code) }}" class="ft-route-pill">
                        <i class="fas fa-plane ft-route-icon"></i>
                        <div>
                            <div class="ft-route-airports">{{ $r->origin_city }} – {{ $r->destination_city }}</div>
                            <div class="ft-route-names">{{ $r->origin_code }} → {{ $r->destination_code }}</div>
                        </div>
                    </a>
                    @endforeach
                @else
                    @foreach($domesticFallback as $r)
                    <a href="#" class="ft-route-pill">
                        <i class="fas fa-plane ft-route-icon"></i>
                        <div>
                            <div class="ft-route-airports">{{ $r['from'] }} – {{ $r['to'] }}</div>
                            <div class="ft-route-names">{{ $r['from_airport'] ?? '' }}</div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
        @php
        $intlFallback = [
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Dubai','to_code'=>'DXB'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Singapore','to_code'=>'SIN'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Bangkok','to_code'=>'BKK'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Kuala Lumpur','to_code'=>'KUL'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Doha','to_code'=>'DOH'],
            ['from'=>'Dhaka','from_code'=>'DAC','to'=>'Riyadh','to_code'=>'RUH'],
        ];
        @endphp
        <div id="routesInternational" style="display:none;">
            <div class="ft-routes-grid">
                @if(isset($internationalRoutes) && $internationalRoutes->count())
                    @foreach($internationalRoutes as $r)
                    <a href="{{ url('/flights/search?origin='.$r->origin_code.'&destination='.$r->destination_code) }}" class="ft-route-pill">
                        <i class="fas fa-plane ft-route-icon"></i>
                        <div>
                            <div class="ft-route-airports">{{ $r->origin_city }} – {{ $r->destination_city }}</div>
                            <div class="ft-route-names">{{ $r->origin_code }} → {{ $r->destination_code }}</div>
                        </div>
                    </a>
                    @endforeach
                @else
                    @foreach($intlFallback as $r)
                    <a href="#" class="ft-route-pill">
                        <i class="fas fa-plane ft-route-icon"></i>
                        <div>
                            <div class="ft-route-airports">{{ $r['from'] }} – {{ $r['to'] }}</div>
                            <div class="ft-route-names">{{ $r['from_code'] }} → {{ $r['to_code'] }}</div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ━━━ APP SHOWCASE (FanamTrip style) ━━━ --}}
<section class="ft-app-showcase">
    <div class="container">
        <div class="ft-showcase-wrap">

            {{-- Left: text + download --}}
            <div class="ft-showcase-text">
                <div class="ft-showcase-badge">
                    <i class="fas fa-mobile-alt"></i> Mobile App
                </div>
                <h2 class="ft-showcase-heading">Your Complete<br><span>Travel Companion</span></h2>
                <p class="ft-showcase-sub">Book flights, hotels, holiday packages, and visa services effortlessly. Real-time tracking, smart schedules and the best fares — all in one app.</p>

                <ul class="ft-showcase-features">
                    <li><i class="fas fa-check-circle"></i> One-tap flight search & booking</li>
                    <li><i class="fas fa-check-circle"></i> Live PNR tracking & reminders</li>
                    <li><i class="fas fa-check-circle"></i> Exclusive app-only deals</li>
                    <li><i class="fas fa-check-circle"></i> 24/7 customer support</li>
                </ul>

                <div class="ft-showcase-downloads">
                    <a href="{{ $siteSettings['app_store_url'] ?? '#' }}" target="_blank" class="ft-dl-btn ft-dl-apple">
                        <i class="fab fa-apple"></i>
                        <div><span>Download on the</span><strong>App Store</strong></div>
                    </a>
                    <a href="{{ $siteSettings['play_store_url'] ?? '#' }}" target="_blank" class="ft-dl-btn ft-dl-google">
                        <i class="fab fa-google-play"></i>
                        <div><span>Get it on</span><strong>Google Play</strong></div>
                    </a>
                </div>

                @if($siteSettings['app_qr'] ?? null)
                <div class="ft-showcase-qr">
                    <img src="{{ asset($siteSettings['app_qr']) }}" alt="Scan to Download">
                    <span>Scan to download</span>
                </div>
                @endif
            </div>

            {{-- Right: phone mockups --}}
            <div class="ft-showcase-phones">

                {{-- Phone Left --}}
                <div class="ft-mock-phone ft-mock-left">
                    <div class="ft-mock-notch"></div>
                    <div class="ft-mock-screen">
                        <div class="ft-mock-header" style="background:linear-gradient(135deg,#C62828,#e53935);">
                            <div class="ft-mock-logo-small"><span style="color:#fff;font-weight:800;font-size:10px;">Faith<span style="color:#F5A623;">Trip</span></span></div>
                            <div class="ft-mock-title" style="color:#fff;font-size:10px;font-weight:700;margin-top:4px;">Flight Search</div>
                        </div>
                        <div style="padding:8px;">
                            <div class="ft-mock-input-row"><i class="fas fa-plane-departure" style="color:#0D1B5E;font-size:8px;"></i><span>Dhaka (DAC)</span></div>
                            <div class="ft-mock-swap"><i class="fas fa-exchange-alt" style="color:#F5A623;font-size:8px;"></i></div>
                            <div class="ft-mock-input-row"><i class="fas fa-plane-arrival" style="color:#0D1B5E;font-size:8px;"></i><span>Dubai (DXB)</span></div>
                            <div style="display:flex;gap:4px;margin-top:6px;">
                                <div class="ft-mock-date-box"><div style="font-size:7px;color:#888;">Depart</div><div style="font-size:9px;font-weight:700;color:#0D1B5E;">15 Jun</div></div>
                                <div class="ft-mock-date-box"><div style="font-size:7px;color:#888;">Return</div><div style="font-size:9px;font-weight:700;color:#0D1B5E;">22 Jun</div></div>
                            </div>
                            <div class="ft-mock-search-btn"><i class="fas fa-search" style="font-size:8px;"></i> Search Flights</div>
                        </div>
                        <div style="padding:0 8px;">
                            <div style="font-size:8px;font-weight:700;color:#0D1B5E;margin-bottom:4px;">Best Fares</div>
                            @foreach([['Emirates','EK','42,500'],['Qatar','QR','38,900'],['Biman','BG','28,200']] as $af)
                            <div class="ft-mock-flight-row">
                                <span style="font-size:8px;font-weight:700;color:#0D1B5E;">{{ $af[1] }}</span>
                                <span style="font-size:7px;color:#555;flex:1;margin:0 4px;">{{ $af[0] }}</span>
                                <span style="font-size:8px;font-weight:800;color:#C62828;">৳{{ $af[2] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="ft-mock-home-bar"></div>
                </div>

                {{-- Phone Center (most prominent) --}}
                <div class="ft-mock-phone ft-mock-center">
                    <div class="ft-mock-notch"></div>
                    <div class="ft-mock-screen">
                        <div class="ft-mock-header" style="background:linear-gradient(135deg,#0D1B5E,#1A3A8F);">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:#F5A623;font-weight:800;font-size:11px;">Faith<span style="color:#fff;">Trip</span></span>
                                <div style="width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-user" style="color:#fff;font-size:9px;"></i>
                                </div>
                            </div>
                            <div style="color:rgba(255,255,255,.85);font-size:9px;margin-top:6px;">Welcome back! 👋</div>
                            <div style="color:#fff;font-weight:700;font-size:11px;">Where to next?</div>
                        </div>
                        <div style="padding:8px 10px;">
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:8px;">
                                @foreach([['fa-plane','Flight','#0D1B5E'],['fa-hotel','Hotel','#C62828'],['fa-umbrella-beach','Holiday','#2e7d32'],['fa-passport','Visa','#5c35cc']] as $svc)
                                <div style="text-align:center;">
                                    <div style="width:30px;height:30px;border-radius:10px;background:{{ $svc[2] }}18;display:flex;align-items:center;justify-content:center;margin:0 auto 3px;">
                                        <i class="fas {{ $svc[0] }}" style="color:{{ $svc[2] }};font-size:10px;"></i>
                                    </div>
                                    <span style="font-size:7.5px;font-weight:600;color:#333;">{{ $svc[1] }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:8px;">
                                @foreach([['fa-bus','Transport','#e65100'],['fa-sim-card','E-Sim','#00838f'],['fa-stethoscope','Medical','#c62828'],['fa-tag','Deals','#F5A623']] as $svc)
                                <div style="text-align:center;">
                                    <div style="width:30px;height:30px;border-radius:10px;background:{{ $svc[2] }}18;display:flex;align-items:center;justify-content:center;margin:0 auto 3px;">
                                        <i class="fas {{ $svc[0] }}" style="color:{{ $svc[2] }};font-size:10px;"></i>
                                    </div>
                                    <span style="font-size:7.5px;font-weight:600;color:#333;">{{ $svc[1] }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div style="background:#f8f9fa;border-radius:8px;padding:7px 8px;margin-bottom:6px;">
                                <div style="font-size:8px;font-weight:700;color:#0D1B5E;margin-bottom:4px;">🔥 Hot Deal</div>
                                <div style="font-size:9px;color:#333;font-weight:600;">Dhaka → Dubai</div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:3px;">
                                    <span style="font-size:8px;color:#777;">Emirates · Economy</span>
                                    <span style="font-size:10px;font-weight:800;color:#C62828;">৳38,900</span>
                                </div>
                            </div>
                            <div style="background:linear-gradient(135deg,#0D1B5E,#1A3A8F);border-radius:8px;padding:7px 8px;">
                                <div style="color:#F5A623;font-size:7px;font-weight:700;margin-bottom:2px;">UPCOMING FLIGHT</div>
                                <div style="color:#fff;font-size:9px;font-weight:700;">BG205 · DAC–CGP</div>
                                <div style="color:rgba(255,255,255,.7);font-size:7.5px;margin-top:2px;">15 Jun · 08:30 AM</div>
                            </div>
                        </div>
                    </div>
                    <div class="ft-mock-home-bar"></div>
                </div>

                {{-- Phone Right --}}
                <div class="ft-mock-phone ft-mock-right">
                    <div class="ft-mock-notch"></div>
                    <div class="ft-mock-screen">
                        <div class="ft-mock-header" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
                            <div class="ft-mock-logo-small"><span style="color:#fff;font-weight:800;font-size:10px;">Faith<span style="color:#F5A623;">Trip</span></span></div>
                            <div class="ft-mock-title" style="color:#fff;font-size:10px;font-weight:700;margin-top:4px;">Tour Packages</div>
                        </div>
                        <div style="padding:8px;">
                            @foreach([['Maldives','5D/4N','৳45,000','🌊'],['Bangkok','4D/3N','৳32,500','🏯'],['Dubai','7D/6N','৳68,000','🏙️']] as $tour)
                            <div class="ft-mock-tour-card">
                                <span style="font-size:14px;">{{ $tour[3] }}</span>
                                <div style="flex:1;">
                                    <div style="font-size:9px;font-weight:700;color:#0D1B5E;">{{ $tour[0] }}</div>
                                    <div style="font-size:7.5px;color:#777;">{{ $tour[1] }}</div>
                                </div>
                                <div style="font-size:9px;font-weight:800;color:#2e7d32;">{{ $tour[2] }}</div>
                            </div>
                            @endforeach
                            <div style="background:#f0fdf4;border-radius:6px;padding:6px 8px;margin-top:4px;border:1px solid #c6efce;">
                                <div style="font-size:7.5px;color:#2e7d32;font-weight:700;">✅ Instant Confirmation</div>
                                <div style="font-size:7px;color:#555;margin-top:2px;">Tickets issued within minutes</div>
                            </div>
                        </div>
                    </div>
                    <div class="ft-mock-home-bar"></div>
                </div>

            </div>{{-- /phones --}}
        </div>{{-- /showcase-wrap --}}
    </div>
</section>

{{-- ━━━ GALLERY ━━━ --}}
@if($galleryItems->count())
<section class="ft-section">
    <div class="container">
        <div class="ft-section-head">
            <h2 class="ft-section-title">Our Gallery</h2>
            <div class="ft-section-underline"></div>
        </div>
        <div class="row g-3">
            @foreach($galleryItems as $item)
            @if($item->media_type === 'image')
            <div class="col-6 col-md-4 col-lg-3">
                <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.07);aspect-ratio:4/3;background:#ddd;">
                    <img src="{{ asset($item->file_path) }}" alt="{{ $item->title ?? '' }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ TESTIMONIALS ━━━ --}}
@if($testimonials->count())
<section class="ft-section ft-section-alt">
    <div class="container">
        <div class="ft-section-head">
            <h2 class="ft-section-title">What Our Travelers Say</h2>
            <div class="ft-section-underline"></div>
        </div>
        <div class="b2c-testimonials-grid">
            @foreach($testimonials as $t)
            <div class="b2c-testimonial-card">
                <div class="b2c-testimonial-stars">
                    @for($i=0;$i<$t->rating;$i++)<i class="fas fa-star"></i>@endfor
                    @for($i=$t->rating;$i<5;$i++)<i class="far fa-star"></i>@endfor
                </div>
                <p class="b2c-testimonial-text">"{{ $t->review }}"</p>
                <div class="b2c-testimonial-author">
                    <div class="b2c-testimonial-avatar">
                        @if($t->customer_photo)
                            <img src="{{ $t->customer_photo }}" alt="{{ $t->customer_name }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($t->customer_name,0,1)) }}
                        @endif
                    </div>
                    <span class="b2c-testimonial-name">{{ $t->customer_name }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ━━━ FAQ ━━━ --}}
@if($faqs->count())
<section class="ft-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <h2 class="ft-section-title" style="text-align:center;">Frequently Asked Questions</h2>
            <div class="ft-section-underline" style="margin:8px auto 0;"></div>
        </div>
        <div style="max-width:800px;margin:0 auto;">
            @foreach($faqs as $faq)
            <div class="b2c-faq-item" style="background:#fff;border-radius:12px;margin-bottom:12px;border:1px solid #e9ecef;overflow:hidden;">
                <button onclick="toggleFaq(this)" style="width:100%;padding:16px 22px;background:none;border:none;text-align:left;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-size:15px;font-weight:600;color:#0D1B5E;">
                    <span>{{ $faq->question }}</span>
                    <i class="fas fa-chevron-down" style="transition:transform .3s;font-size:12px;color:#6c757d;"></i>
                </button>
                <div class="b2c-faq-answer" style="max-height:0;overflow:hidden;transition:max-height .3s ease,padding .3s ease;">
                    <div style="padding:0 22px 16px;color:#555;font-size:14px;line-height:1.7;">{!! nl2br(e($faq->answer)) !!}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ url('assets') }}/nanopkg-assets/vendor/t-datepicker-master/public/theme/js/t-datepicker.min.js"></script>
    <script src="{{ url('assets') }}/module-assets/js/booking/search_box.js"></script>

    <script>
    // ── Init on DOM ready ──
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector('input[name="flight_type"][value="1"]').checked = true;
        showOnewayDate();
        initDestCarousel();
        renderDestDots();
    });

    // ── Service tabs (visual only for now) ──
    document.querySelectorAll('.ft-service-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.ft-service-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ── Fare type highlight ──
    document.querySelectorAll('.ft-fare-type input').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.ft-fare-type').forEach(l => l.classList.remove('ft-active'));
            this.closest('.ft-fare-type').classList.add('ft-active');
        });
    });

    // ── Flight search selects ──
    $('.flight_from').select2({ placeholder:'Departure City/Airport', minimumInputLength:2, ajax:{ url:'/b2c/city-airport-search', dataType:'json', delay:250, data:function(p){return{q:p.term};}, processResults:function(d){return{results:$.map(d,function(i){return{text:i.search_result,id:i.id}})};}, cache:true } });
    $('.flight_to').select2({   placeholder:'Destination City/Airport', minimumInputLength:2, ajax:{ url:'/b2c/city-airport-search', dataType:'json', delay:250, data:function(p){return{q:p.term};}, processResults:function(d){return{results:$.map(d,function(i){return{text:i.search_result,id:i.id}})};}, cache:true } });

    $.ajaxSetup({ headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')} });

    // ── Date / trip type ──
    function showOnewayDate() {
        $("#flight_type").val(1);
        var rows = document.querySelectorAll(".search-row");
        for (var i=1;i<rows.length;i++) rows[i].remove();
        document.querySelector('.multicity-btn').classList.replace('d-inline-block','d-none');
        document.getElementById('departureDateCol').classList.remove('d-none');
        document.getElementById('returnDateCol').classList.remove('d-none');
        document.getElementById('roundDateCol').classList.add('d-none');
    }
    function switchToRoundTrip() { document.querySelector('input[name="flight_type"][value="2"]').click(); }
    function showRoundTripDate() {
        $("#flight_type").val(2);
        var rows = document.querySelectorAll(".search-row");
        for (var i=1;i<rows.length;i++) rows[i].remove();
        document.querySelector('.multicity-btn').classList.replace('d-inline-block','d-none');
        document.getElementById('departureDateCol').classList.add('d-none');
        document.getElementById('returnDateCol').classList.add('d-none');
        document.getElementById('roundDateCol').classList.remove('d-none');
    }
    function showMultiCityDate() {
        $("#flight_type").val(3);
        document.getElementById('departureDateCol').classList.remove('d-none');
        document.getElementById('returnDateCol').classList.remove('d-none');
        document.getElementById('roundDateCol').classList.add('d-none');
        var original = document.querySelector(".search-row");
        var newRow = createRemovableRow(original);
        var allRows = document.querySelectorAll(".search-row");
        var lastRow = allRows[allRows.length-1];
        lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
        initDatepickerForRow(newRow);
        document.querySelector('.multicity-btn').classList.replace('d-none','d-inline-block');
    }

    function initDatepickerForRow(row) {
        var $dp = $(row).find(".t-datepicker");
        $dp.find(".t-input").remove(); $dp.find(".t-table").remove();
        if ($dp.find(".t-check-in").length === 0) $dp.html('<div class="t-check-in"></div>');
        $dp.tDatePicker({ autoClose:true, durationArrowTop:200, formatDate:"dd-mm-yyyy", dateCheckIn:new Date(), dateCheckOut:new Date(), iconDate:"", titleCheckIn:$("[data-departure]").data("departure"), titleCheckOut:$("[data-return]").data("return"), limitDateRanges:360, limitNextMonth:12 });
    }

    var multiCityRowIndex = 1;
    function createRemovableRow(fromDiv) {
        var clone = fromDiv.cloneNode(true);
        multiCityRowIndex++;
        var toCol = clone.querySelector(".flight-to")?.closest(".col-lg-5");
        if (toCol && !toCol.querySelector(".search-row-remove")) {
            var rb = document.createElement("button");
            rb.innerHTML = "<i class='fas fa-times'></i>"; rb.className = "search-row-remove btn"; rb.type = "button"; rb.style.marginLeft = "10px";
            rb.addEventListener("click", function(){clone.remove();});
            toCol.appendChild(rb);
        }
        clone.querySelector("#dropdown-oneway")?.closest(".col-lg-2")?.remove();
        clone.querySelector(".preferred_airlines")?.closest(".col-lg-2")?.remove();
        $(clone).find(".select2-container").remove();
        var fromSelect = clone.querySelector(".flight_from");
        var toSelect = clone.querySelector(".flight_to");
        if (fromSelect) { fromSelect.id = "flight_from_"+multiCityRowIndex; clone.querySelector('label[for="flight_from"]')?.setAttribute("for",fromSelect.id); }
        if (toSelect)   { toSelect.id   = "flight_to_"+multiCityRowIndex;   clone.querySelector('label[for="flight_to"]')?.setAttribute("for",toSelect.id); }
        $(clone).find(".flight_from,.flight_to").each(function(){ $(this).removeClass("select2-hidden-accessible").removeAttr("data-select2-id tabindex aria-hidden").removeData("select2"); $(this).find("option").removeAttr("data-select2-id"); this.selectedIndex=-1; });
        $(clone).find(".flight_from").select2({ placeholder:"Departure City/Airport", minimumInputLength:2, ajax:{ url:"/b2c/city-airport-search", dataType:"json", delay:250, data:function(p){return{q:p.term};}, processResults:function(d){return{results:$.map(d,i=>({text:i.search_result,id:i.id}))};}, cache:true } });
        $(clone).find(".flight_to").select2({   placeholder:"Destination City/Airport", minimumInputLength:2, ajax:{ url:"/b2c/city-airport-search", dataType:"json", delay:250, data:function(p){return{q:p.term};}, processResults:function(d){return{results:$.map(d,i=>({text:i.search_result,id:i.id}))};}, cache:true } });
        return clone;
    }

    document.getElementById("add_another_city").addEventListener("click", function(){
        var original = document.querySelector(".search-row");
        var newRow = createRemovableRow(original);
        var allRows = document.querySelectorAll(".search-row");
        allRows[allRows.length-1].parentNode.insertBefore(newRow, allRows[allRows.length-1].nextSibling);
        initDatepickerForRow(newRow);
    });

    function searchForFlights() {
        var flightType = $("#flight_type").val();
        if (flightType == 3) { searchMultiCityFlights(); return; }
        var dep = $("#flight_from").val(), dst = $("#flight_to").val();
        var adult = +$("#oneway-adult-input").val(), child = +$("#oneway-child-input").val(), infant = +$("#oneway-infant-input").val();
        var cabinClass = $('input.cabin_class_oneway:checked').val();
        var depDate = '', retDate = '';
        if (flightType==1) depDate = document.querySelector('#oneWayDatePicker .t-check-in input[name="t-start"]')?.value||'';
        else { depDate = document.querySelector('#roundDatePicker .t-check-in input[name="t-start"]')?.value||''; retDate = document.querySelector('#roundDatePicker .t-check-out input[name="t-end"]')?.value||''; }
        if (!dep) { toastr.error("Departure Location is missing"); return; }
        if (!dst) { toastr.error("Destination Location is missing"); return; }
        if (!depDate) { toastr.error("Departure Date is missing"); return; }
        if (flightType==2 && !retDate) { toastr.error("Return Date is mandatory for Round Trip"); return; }
        if (dep === dst) { toastr.error("Departure & Destination Cannot be Same"); return; }
        $(".page-loader-wrapper").show();
        var fd = new FormData();
        fd.append("flight_type",flightType); fd.append("departure_location_id",dep); fd.append("destination_location_id",dst);
        fd.append("departure_date",depDate); fd.append("return_date",retDate);
        fd.append("adult",adult); fd.append("child",child); fd.append("infant",infant);
        fd.append("preferred_airlines",""); fd.append("cabin_class",cabinClass);
        $.ajax({ data:fd, url:"{{ url('/flights/search') }}", type:"POST", cache:false, contentType:false, processData:false,
            success:function(){ $(".page-loader-wrapper").hide(); window.location.href="/flights/results"; },
            error:function(){ $(".page-loader-wrapper").hide(); toastr.error("Something went wrong! Please try again."); }
        });
    }

    function searchMultiCityFlights() {
        var segments = []; var valid = true;
        document.querySelectorAll(".search-row").forEach(function(row,idx){
            var from=$(row).find(".flight_from").val(), to=$(row).find(".flight_to").val(), date=$(row).find('.t-check-in input[name="t-start"]').val();
            if (!from&&!to&&!date) return;
            if (!from||!to||!date){ toastr.error("Segment "+(idx+1)+" is missing From/To/Date"); valid=false; return; }
            if (from===to){ toastr.error("Segment "+(idx+1)+": From and To cannot be same"); valid=false; return; }
            segments.push({from,to,date});
        });
        if (!valid) return;
        var adult=+$("#oneway-adult-input").val(), child=+$("#oneway-child-input").val(), infant=+$("#oneway-infant-input").val();
        $(".page-loader-wrapper").show();
        var fd=new FormData(); fd.append("flight_type",3);
        segments.forEach(function(s,i){ fd.append("segments["+i+"][from]",s.from); fd.append("segments["+i+"][to]",s.to); fd.append("segments["+i+"][date]",s.date); });
        fd.append("adult",adult); fd.append("child",child); fd.append("infant",infant); fd.append("cabin_class",$('input.cabin_class_oneway:checked').val());
        $.ajax({ data:fd, url:"{{ url('/flights/search/multi-city') }}", type:"POST", cache:false, contentType:false, processData:false,
            success:function(){ $(".page-loader-wrapper").hide(); window.location.href="/flights/results"; },
            error:function(){ $(".page-loader-wrapper").hide(); toastr.error("Something went wrong! Please try again."); }
        });
    }

    // ── Special Offers Filter ──
    var offersPage = 0;
    var offersPerPage = 6;
    var currentFilter = 'all';

    function filterOffers(type, btn) {
        currentFilter = type; offersPage = 0;
        document.querySelectorAll('.ft-filter-btn').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        updateOffersVisibility();
    }

    function updateOffersVisibility() {
        var cards = document.querySelectorAll('.ft-offer-card');
        var filtered = [];
        cards.forEach(function(c){ c.classList.remove('ft-visible'); var t=c.dataset.type||'other'; if (currentFilter==='all'||t===currentFilter||t==='hot_deal') filtered.push(c); });
        var start = offersPage * offersPerPage;
        filtered.slice(start, start+offersPerPage).forEach(function(c){ c.classList.add('ft-visible'); });
    }

    function shiftOffers(dir) { offersPage = Math.max(0, offersPage+dir); updateOffersVisibility(); }

    // ── Destinations Carousel ──
    var destIndex = 0;
    var destCards = 4;
    var destTotal = 0;

    function initDestCarousel() {
        var track = document.getElementById('destTrack');
        if (!track) return;
        destTotal = track.children.length;
        var w = track.parentElement.offsetWidth;
        destCards = w < 600 ? 1 : (w < 900 ? 2 : 4);
    }

    function renderDestDots() {
        var dots = document.getElementById('destDots');
        if (!dots) return;
        var track = document.getElementById('destTrack');
        if (!track) return;
        destTotal = track.children.length;
        var pages = Math.ceil(destTotal / destCards) || 1;
        dots.innerHTML = '';
        for (var i=0; i<pages; i++) {
            var btn = document.createElement('button');
            btn.className = 'ft-dest-dot' + (i===0 ? ' active' : '');
            btn.dataset.page = i;
            btn.addEventListener('click', function(){ goDestPage(+this.dataset.page); });
            dots.appendChild(btn);
        }
    }

    function goDestPage(page) {
        var track = document.getElementById('destTrack');
        if (!track) return;
        destIndex = page;
        var cardWidth = track.children[0]?.offsetWidth || 0;
        var gap = 20;
        track.style.transform = 'translateX(-' + (page * destCards * (cardWidth+gap)) + 'px)';
        document.querySelectorAll('.ft-dest-dot').forEach(function(d,i){ d.classList.toggle('active', i===page); });
    }

    // ── Routes Tab ──
    function switchRoutes(type) {
        document.getElementById('routesDomestic').style.display    = type==='domestic'      ? 'block' : 'none';
        document.getElementById('routesInternational').style.display = type==='international' ? 'block' : 'none';
        document.getElementById('tabDomestic').classList.toggle('active',      type==='domestic');
        document.getElementById('tabInternational').classList.toggle('active', type==='international');
    }

    // ── FAQ Toggle ──
    function toggleFaq(btn) {
        var answer = btn.nextElementSibling;
        var icon = btn.querySelector('i');
        var isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
        document.querySelectorAll('.b2c-faq-answer').forEach(function(a){ a.style.maxHeight='0px'; });
        document.querySelectorAll('.b2c-faq-item button i').forEach(function(i){ i.style.transform='rotate(0)'; });
        if (!isOpen) { answer.style.maxHeight = answer.scrollHeight+'px'; icon.style.transform='rotate(180deg)'; }
    }
    </script>
@endsection
