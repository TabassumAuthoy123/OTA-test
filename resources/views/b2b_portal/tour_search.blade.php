@extends('master')

@section('header_css')
<style>
.tsearch-wrap { padding: 0 0 40px; }

/* ─── Hero ─── */
.tsearch-hero {
    background: linear-gradient(135deg, #1a3a6b 0%, #0f1f3d 100%);
    padding: 36px 36px 28px;
    text-align: center;
    margin-bottom: 28px;
}
.tsearch-hero .subtitle {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    color: rgba(255,255,255,.5);
    text-transform: uppercase;
    margin-bottom: 8px;
}
.tsearch-hero h2 {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    margin: 0;
}

/* ─── Search Form ─── */
.tsearch-form-wrap {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 22px 28px;
    margin: 0 28px 28px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.tsearch-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 14px;
    align-items: end;
}
.tf-label {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.tf-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color .15s;
}
.tf-control:focus { border-color: #1a3a6b; }
.tsearch-btn {
    padding: 10px 28px;
    background: #1a3a6b;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: background .15s;
}
.tsearch-btn:hover { background: #243f73; }

/* ─── Results ─── */
.tsearch-results { padding: 0 28px; }
.tsearch-count {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 16px;
}
.tour-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    gap: 0;
    margin-bottom: 16px;
    transition: box-shadow .2s, transform .2s;
}
.tour-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.09); transform: translateY(-2px); }
.tour-card-img {
    width: 220px;
    flex-shrink: 0;
    background: linear-gradient(135deg, #e8eeff, #dbeafe);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.tour-card-img img { width: 100%; height: 100%; object-fit: cover; }
.tour-card-img-placeholder {
    font-size: 48px;
    opacity: .3;
    color: #1a3a6b;
}
.tour-card-body {
    flex: 1;
    padding: 20px 22px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
}
.tour-card-content { flex: 1; }
.tour-type-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 20px;
    margin-bottom: 8px;
}
.badge-domestic     { background: #dbeafe; color: #1e40af; }
.badge-international{ background: #dcfce7; color: #166534; }
.badge-pilgrimage   { background: #fef9c3; color: #854d0e; }
.tour-card-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f1f3d;
    margin-bottom: 6px;
    line-height: 1.3;
}
.tour-card-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 10px;
}
.tour-card-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}
.tour-meta-item {
    font-size: 12px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 5px;
}
.tour-meta-item i { color: #6b7280; font-size: 11px; }
.tour-card-price-col {
    text-align: right;
    flex-shrink: 0;
    min-width: 140px;
}
.tour-starting {
    font-size: 10px;
    color: #9ca3af;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.tour-price {
    font-size: 20px;
    font-weight: 800;
    color: #1a3a6b;
    margin-bottom: 12px;
}
.tour-price .currency { font-size: 13px; font-weight: 600; color: #6b7280; }
.tour-book-btn {
    display: block;
    padding: 9px 18px;
    background: #1a3a6b;
    color: #fff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background .15s;
}
.tour-book-btn:hover { background: #243f73; color: #fff; text-decoration: none; }

.tsearch-empty {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}
.tsearch-empty i { font-size: 48px; margin-bottom: 14px; opacity: .3; display: block; }
.tsearch-empty p { font-size: 15px; }

@media(max-width: 768px) {
    .tsearch-form-grid { grid-template-columns: 1fr 1fr; }
    .tour-card { flex-direction: column; }
    .tour-card-img { width: 100%; height: 160px; }
    .tour-card-body { flex-direction: column; }
    .tour-card-price-col { text-align: left; }
    .tsearch-form-wrap, .tsearch-results { margin: 0 12px 20px; padding: 16px; }
}
</style>
@endsection

@section('content')
<div class="tsearch-wrap">

    {{-- Hero --}}
    <div class="tsearch-hero">
        <div class="subtitle">Explore Tour Packages</div>
        <h2>Tour Search</h2>
    </div>

    {{-- Search Form --}}
    <div class="tsearch-form-wrap">
        <form method="GET" action="{{ url('tours-search') }}">
            <div class="tsearch-form-grid">
                <div>
                    <label class="tf-label">Country</label>
                    <select name="country" class="tf-control">
                        <option value="all">All Countries</option>
                        @foreach($countries as $c)
                            <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="tf-label">Visa Type</label>
                    <select name="visa_type" class="tf-control">
                        <option value="all">All Types</option>
                        @foreach($visaTypes as $vt)
                            <option value="{{ $vt }}" {{ request('visa_type') == $vt ? 'selected' : '' }}>{{ ucfirst($vt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="tf-label">Travel Date</label>
                    <input type="date" name="start_date" class="tf-control"
                        value="{{ request('start_date', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <button type="submit" class="tsearch-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="tsearch-results">
        <div class="tsearch-count">
            @if($packages->count() > 0)
                Showing {{ $packages->count() }} tour package{{ $packages->count() > 1 ? 's' : '' }}
                @if(request('country') && request('country') !== 'all')
                    in <strong>{{ request('country') }}</strong>
                @endif
            @endif
        </div>

        @forelse($packages as $pkg)
        <div class="tour-card">
            <div class="tour-card-img">
                @if($pkg->image && file_exists(public_path($pkg->image)))
                    <img src="{{ asset($pkg->image) }}" alt="{{ $pkg->title }}">
                @else
                    <i class="tour-card-img-placeholder fas fa-map-marked-alt"></i>
                @endif
            </div>
            <div class="tour-card-body">
                <div class="tour-card-content">
                    @php
                        $bClass = $pkg->tour_type === 'domestic' ? 'badge-domestic' : ($pkg->visa_type === 'pilgrimage' ? 'badge-pilgrimage' : 'badge-international');
                        $bLabel = strtoupper($pkg->tour_type);
                    @endphp
                    <span class="tour-type-badge {{ $bClass }}">{{ $bLabel }}</span>
                    <div class="tour-card-title">{{ $pkg->title }}</div>
                    <div class="tour-card-desc">{{ $pkg->description ?? 'Tour details available.' }}</div>
                    <div class="tour-card-meta">
                        @if($pkg->country)
                        <span class="tour-meta-item"><i class="fas fa-globe-asia"></i> {{ $pkg->country }}</span>
                        @endif
                        @if($pkg->duration_days)
                        <span class="tour-meta-item"><i class="fas fa-calendar-alt"></i> {{ $pkg->duration_days }} Days</span>
                        @endif
                        @if($pkg->max_travelers)
                        <span class="tour-meta-item"><i class="fas fa-users"></i> Max {{ $pkg->max_travelers }} Travelers</span>
                        @endif
                        @if($pkg->visa_type)
                        <span class="tour-meta-item"><i class="far fa-id-card"></i> {{ ucfirst($pkg->visa_type) }} Visa</span>
                        @endif
                    </div>
                </div>
                <div class="tour-card-price-col">
                    <div class="tour-starting">Starting Price</div>
                    <div class="tour-price">
                        <span class="currency">{{ $pkg->currency }}</span>
                        {{ number_format($pkg->price, 2) }}
                    </div>
                    <button class="tour-book-btn" onclick="bookTour({{ $pkg->id }}, '{{ addslashes($pkg->title) }}')">
                        View Details
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="tsearch-empty">
            <i class="fas fa-umbrella-beach"></i>
            <p>No tour packages found matching your criteria.<br>Try adjusting your search filters.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('footer_js')
<script>
function bookTour(id, title) {
    toastr.info('Enquiry for "' + title + '" — our team will contact you shortly.');
}
</script>
@endsection
