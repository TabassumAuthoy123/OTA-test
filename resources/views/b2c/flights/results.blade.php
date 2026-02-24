@extends('b2c.layouts.master')

@section('title', 'Flight Search Results - FaithTrip')

@section('content')
<style>
/* ━━━ Search Results Page ━━━ */
.results-hero {
    background: linear-gradient(135deg, #0c1829 0%, #1a365d 50%, #0f4c75 100%);
    padding: 28px 0 20px;
    color: #fff;
}
.search-summary {
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
}
.search-summary .route-info { font-size: 20px; font-weight: 700; }
.search-summary .route-arrow { color: #60a5fa; margin: 0 6px; }
.search-summary .search-meta { font-size: 13px; color: rgba(255,255,255,.7); }
.search-summary .modify-btn { margin-left: auto; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25); color: #fff; border-radius: 8px; padding: 8px 16px; font-size: 13px; cursor: pointer; transition: .2s; }
.search-summary .modify-btn:hover { background: rgba(255,255,255,.25); }

/* Results Layout */
.results-container { display: flex; gap: 20px; padding: 24px 0; min-height: 60vh; }
.results-sidebar { width: 280px; flex-shrink: 0; }
.results-main { flex: 1; }

/* Filter Card */
.filter-card { background: #fff; border-radius: 12px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 16px; }
.filter-card h6 { font-weight: 700; font-size: 14px; margin-bottom: 12px; color: #1a1a2e; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px; }
.filter-card .form-label { font-size: 12px; font-weight: 600; color: #6c757d; }
.filter-card .form-control { font-size: 13px; border-radius: 8px; }
.airline-filter-item { display: flex; align-items: center; gap: 8px; padding: 6px 0; font-size: 13px; }
.airline-filter-item label { cursor: pointer; }
.airline-logo-sm { width: 24px; height: 24px; border-radius: 4px; object-fit: contain; }

/* Flight Cards */
.flight-card {
    background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
    padding: 18px 22px; margin-bottom: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: all .25s ease;
    display: flex; align-items: center; gap: 16px;
}
.flight-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.08); border-color: #c5d5ea; transform: translateY(-2px); }

.fc-airline { display: flex; flex-direction: column; align-items: center; width: 80px; text-align: center; }
.fc-airline img { width: 40px; height: 40px; object-fit: contain; margin-bottom: 4px; }
.fc-airline-name { font-size: 11px; color: #6c757d; line-height: 1.2; }

.fc-route { flex: 1; display: flex; align-items: center; gap: 12px; min-width: 0; }
.fc-time { text-align: center; }
.fc-time .time { font-size: 18px; font-weight: 700; color: #1a1a2e; }
.fc-time .city { font-size: 11px; color: #6c757d; font-weight: 600; }
.fc-duration { flex: 1; text-align: center; position: relative; }
.fc-duration .dur { font-size: 11px; color: #6c757d; font-weight: 600; }
.fc-duration .line { height: 2px; background: linear-gradient(to right, #60a5fa, #a78bfa); margin: 4px auto; border-radius: 1px; position: relative; }
.fc-duration .line::after { content: '✈'; position: absolute; right: -4px; top: -8px; font-size: 12px; color: #60a5fa; }
.fc-duration .stops { font-size: 10px; color: #f59e0b; font-weight: 600; }

.fc-price { text-align: right; min-width: 120px; }
.fc-price .amount { font-size: 20px; font-weight: 800; color: #0f4c75; }
.fc-price .currency { font-size: 12px; color: #6c757d; }
.fc-price .per { font-size: 11px; color: #adb5bd; }
.fc-select-btn {
    display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #0f4c75, #1a365d);
    color: #fff; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;
    margin-top: 6px; transition: .2s;
}
.fc-select-btn:hover { background: linear-gradient(135deg, #1a365d, #0f4c75); transform: scale(1.03); color: #fff; }

/* Flight Details Expandable */
.fc-details-toggle { font-size: 12px; color: #60a5fa; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px; }
.fc-details-toggle:hover { color: #3b82f6; }
.fc-details-panel { display: none; background: #f8fafc; border-top: 1px solid #e9ecef; padding: 16px; margin: 0 -22px -18px; border-radius: 0 0 14px 14px; }
.fc-details-panel.show { display: block; }
.fc-segment { display: flex; gap: 12px; font-size: 13px; padding: 6px 0; }
.fc-segment + .fc-segment { border-top: 1px dashed #e0e0e0; }

/* Sorting bar */
.sort-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
.sort-bar .result-count { font-size: 14px; font-weight: 600; color: #1a1a2e; }
.sort-bar .result-count span { color: #0f4c75; }
.sort-btn { padding: 6px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 12px; cursor: pointer; background: #fff; color: #495057; transition: .2s; }
.sort-btn.active, .sort-btn:hover { background: #0f4c75; color: #fff; border-color: #0f4c75; }

/* No results */
.no-results { text-align: center; padding: 60px 20px; }
.no-results i { font-size: 48px; color: #d1d5db; margin-bottom: 16px; }
.no-results h5 { font-weight: 700; color: #374151; }
.no-results p { color: #6b7280; }

/* Mobile */
@media (max-width: 991px) {
    .results-sidebar { display: none; }
    .flight-card { flex-wrap: wrap; padding: 14px; }
    .fc-airline { width: 60px; }
    .fc-price { width: 100%; text-align: center; margin-top: 10px; border-top: 1px solid #f0f0f0; padding-top: 10px; }
}
</style>

{{-- Search Summary Hero --}}
<div class="results-hero">
    <div class="container">
        <div class="search-summary">
            <div>
                <div class="route-info">
                    {{ session('b2c_origin_city_name', 'Departure') }}
                    <span class="route-arrow">
                        @if(session('b2c_flight_type') == 2) ⇄ @else → @endif
                    </span>
                    {{ session('b2c_destination_city_name', 'Destination') }}
                </div>
                <div class="search-meta mt-1">
                    <i class="fas fa-calendar-alt me-1"></i> {{ session('b2c_departure_date') ? date('d M Y', strtotime(session('b2c_departure_date'))) : '' }}
                    @if(session('b2c_return_date'))
                        — {{ date('d M Y', strtotime(session('b2c_return_date'))) }}
                    @endif
                    &nbsp;·&nbsp;
                    <i class="fas fa-user me-1"></i>
                    {{ session('b2c_adult', 1) }} Adult{{ session('b2c_adult', 1) > 1 ? 's' : '' }}
                    @if(session('b2c_child', 0) > 0), {{ session('b2c_child') }} Child @endif
                    @if(session('b2c_infant', 0) > 0), {{ session('b2c_infant') }} Infant @endif
                </div>
            </div>
            <a href="{{ url('/') }}" class="modify-btn"><i class="fas fa-search me-1"></i> Modify Search</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="results-container">

        {{-- Sidebar Filters --}}
        <div class="results-sidebar">
            <div class="filter-card">
                <h6><i class="fas fa-filter me-1"></i> Price Range</h6>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label">Min (৳)</label>
                        <input type="number" class="form-control" id="filterMinPrice" value="{{ session('b2c_filter_min_price') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Max (৳)</label>
                        <input type="number" class="form-control" id="filterMaxPrice" value="{{ session('b2c_filter_max_price') }}">
                    </div>
                </div>
                <button class="btn btn-sm btn-primary w-100" onclick="applyPriceFilter()">Apply</button>
                @if(session('b2c_filter_min_price') || session('b2c_filter_max_price'))
                    <button class="btn btn-sm btn-link w-100 mt-1" onclick="clearPriceFilter()">Clear</button>
                @endif
            </div>

            @if(count($operatingCarriers) > 0)
            <div class="filter-card">
                <h6><i class="fas fa-plane me-1"></i> Airlines</h6>
                @foreach($operatingCarriers as $code)
                <div class="airline-filter-item">
                    <input type="checkbox" id="airline-{{ $code }}"
                        {{ session('b2c_airline_carrier_code') && in_array($code, session('b2c_airline_carrier_code')) ? 'checked' : '' }}
                        onchange="toggleAirline('{{ $code }}')">
                    <img src="{{ url('airlines_logo') }}/{{ strtolower($code) }}.png" class="airline-logo-sm" onerror="this.style.display='none'">
                    <label for="airline-{{ $code }}">{{ $airlineNames[$code] ?? $code }}</label>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Main Results --}}
        <div class="results-main">
            @if(count($searchResults ?? []) > 0)
                <div class="sort-bar">
                    <div class="result-count">
                        <span id="visibleCount">{{ count($searchResults) }}</span> flights found
                    </div>
                    <button class="sort-btn active" onclick="sortFlights('price')">💰 Cheapest</button>
                    <button class="sort-btn" onclick="sortFlights('duration')">⏱ Fastest</button>
                </div>

                <div id="flightResults">
                    @foreach($searchResults as $index => $data)
                    @php
                        $totalPrice = $data['total_fare'];
                        $minPrice = session('b2c_filter_min_price');
                        $maxPrice = session('b2c_filter_max_price');
                        $carrierFilter = session('b2c_airline_carrier_code');
                        $show = true;
                        if ($minPrice && $totalPrice < $minPrice) $show = false;
                        if ($maxPrice && $totalPrice > $maxPrice) $show = false;
                        if ($carrierFilter && !in_array($data['operating_carrier_code'], $carrierFilter)) $show = false;

                        $airlineInfo = DB::table('airlines')->where('iata', $data['operating_carrier_code'])->where('active', 'Y')->first();
                        $airlineName = $airlineInfo ? $airlineInfo->name : $data['operating_carrier_code'];

                        // Duration calc
                        $depTime = strtotime($data['departure_datetime']);
                        $arrTime = strtotime($data['arrival_datetime']);
                        $durationMin = ($arrTime - $depTime) / 60;
                        $hours = floor($durationMin / 60);
                        $mins = $durationMin % 60;

                        $stops = isset($data['stop_quantity']) ? (int)$data['stop_quantity'] : 0;
                    @endphp

                    @if($show)
                    <div class="flight-card" data-price="{{ $totalPrice }}" data-duration="{{ $durationMin }}">
                        <div class="fc-airline">
                            <img src="{{ url('airlines_logo') }}/{{ strtolower($data['operating_carrier_code']) }}.png"
                                 alt="{{ $data['operating_carrier_code'] }}"
                                 onerror="this.src='{{ url('airlines_logo/default.png') }}'">
                            <div class="fc-airline-name">{{ Str::limit($airlineName, 18) }}</div>
                        </div>

                        <div class="fc-route">
                            <div class="fc-time">
                                <div class="time">{{ date('H:i', strtotime($data['departure_datetime'])) }}</div>
                                <div class="city">{{ $data['departure_airport_code'] ?? session('b2c_origin_code') }}</div>
                            </div>
                            <div class="fc-duration">
                                <div class="dur">{{ $hours }}h {{ $mins }}m</div>
                                <div class="line"></div>
                                <div class="stops">
                                    @if($stops == 0) Non Stop
                                    @elseif($stops == 1) 1 Stop
                                    @else {{ $stops }} Stops
                                    @endif
                                </div>
                            </div>
                            <div class="fc-time">
                                <div class="time">{{ date('H:i', strtotime($data['arrival_datetime'])) }}</div>
                                <div class="city">{{ $data['arrival_airport_code'] ?? session('b2c_destination_code') }}</div>
                            </div>
                        </div>

                        <div class="fc-price">
                            <div class="currency">{{ $data['currency'] ?? 'BDT' }}</div>
                            <div class="amount">৳{{ number_format($totalPrice) }}</div>
                            <div class="per">/person</div>
                            <a href="{{ url('/flights/select/'.$index) }}" class="fc-select-btn">
                                Book Now <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            <div class="fc-details-toggle" onclick="toggleDetails({{ $index }})">
                                <i class="fas fa-info-circle"></i> Details
                            </div>
                        </div>

                        <div class="fc-details-panel" id="details-{{ $index }}">
                            <div class="fc-segment">
                                <div><strong>{{ $data['departure_airport_name'] }}</strong><br>{{ date('H:i, d M', strtotime($data['departure_datetime'])) }}</div>
                                <div style="flex:1;text-align:center;"><i class="fas fa-long-arrow-alt-right" style="font-size:20px;color:#60a5fa;"></i></div>
                                <div><strong>{{ $data['arrival_airport_name'] }}</strong><br>{{ date('H:i, d M', strtotime($data['arrival_datetime'])) }}</div>
                            </div>
                            <div style="font-size:12px;color:#6c757d;margin-top:8px;">
                                <i class="fas fa-suitcase me-1"></i> Baggage: {{ $data['baggage'] ?? 'Check with airline' }}
                                &nbsp;·&nbsp;
                                <i class="fas fa-chair me-1"></i> Class: {{ $data['cabin_class'] ?? 'Economy' }}
                                &nbsp;·&nbsp;
                                Flight: {{ $data['operating_carrier_code'] }}{{ $data['flight_number'] ?? '' }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            @else
                <div class="no-results">
                    <i class="fas fa-plane-slash d-block"></i>
                    <h5>No Flights Found</h5>
                    <p>We couldn't find any flights for your search criteria. Try different dates or destinations.</p>
                    <a href="{{ url('/') }}" class="fc-select-btn mt-3"><i class="fas fa-search me-1"></i> Search Again</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

function applyPriceFilter() {
    var min = $('#filterMinPrice').val();
    var max = $('#filterMaxPrice').val();
    if (!min && !max) { toastr.error('Enter min or max price'); return; }
    $.post('{{ url("/flights/price-filter") }}', { min_price: min, max_price: max }, function() { location.reload(); });
}

function clearPriceFilter() {
    $.post('{{ url("/flights/price-filter") }}', { min_price: '', max_price: '' }, function() { location.reload(); });
}

function toggleAirline(code) {
    var type = $('#airline-' + code).is(':checked') ? 'add' : 'remove';
    $.post('{{ url("/flights/airline-filter") }}', { airline_carrier_code: code, type: type }, function() { location.reload(); });
}

function toggleDetails(index) {
    $('#details-' + index).toggleClass('show');
}

function sortFlights(by) {
    var cards = $('.flight-card').toArray();
    cards.sort(function(a, b) {
        return parseFloat($(a).data(by)) - parseFloat($(b).data(by));
    });
    var container = $('#flightResults');
    $.each(cards, function(i, card) { container.append(card); });
    $('.sort-btn').removeClass('active');
    event.target.classList.add('active');
}
</script>
@endsection
