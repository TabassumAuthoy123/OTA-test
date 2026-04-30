@extends('master')

@section('header_css')
<style>
.b2b-dash-wrap { padding: 28px 28px 40px; }

/* ─── Hero Banner ─── */
.dash-hero {
    background: linear-gradient(135deg, #1a3a6b 0%, #0f1f3d 100%);
    border-radius: 14px;
    padding: 28px 32px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}
.dash-hero-left .dash-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: rgba(255,255,255,.5);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.dash-hero-left h2 {
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 6px;
}
.dash-hero-left p {
    font-size: 13px;
    color: rgba(255,255,255,.55);
    margin: 0;
}
.dash-hero-right {
    text-align: right;
    flex-shrink: 0;
}
.dash-hero-right .period-label {
    font-size: 11px;
    color: rgba(255,255,255,.5);
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.dash-hero-right .period-val {
    font-size: 12px;
    color: rgba(255,255,255,.7);
    margin-bottom: 6px;
}
.dash-cycle-badge {
    display: inline-block;
    background: #f0a500;
    color: #0f1f3d;
    font-size: 12px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 20px;
}

/* ─── Stat Cards ─── */
.dash-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.dstat {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: box-shadow .2s, transform .2s;
}
.dstat:hover { box-shadow: 0 4px 18px rgba(0,0,0,.08); transform: translateY(-2px); }
.dstat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.dstat-icon.blue   { background: #e8eeff; color: #3b5bdb; }
.dstat-icon.green  { background: #e6f9ef; color: #2f9e44; }
.dstat-icon.orange { background: #fff4e0; color: #e67700; }
.dstat-icon.red    { background: #ffe8e8; color: #c92a2a; }
.dstat-body .dstat-val  { font-size: 28px; font-weight: 800; color: #0f1f3d; line-height: 1; }
.dstat-body .dstat-title{ font-size: 12px; font-weight: 600; color: #6b7280; margin-top: 4px; letter-spacing: .3px; }
.dstat-body .dstat-sub  { font-size: 11px; color: #9ca3af; margin-top: 2px; }

/* ─── Two Column layout ─── */
.dash-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }

/* ─── Section Cards ─── */
.dsec {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 22px;
}
.dsec-title {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 8px;
}
.dsec-heading {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 20px;
}

/* ─── Chart container ─── */
.chart-wrap { position: relative; height: 240px; }

/* ─── Health Check ─── */
.health-item { margin-bottom: 20px; }
.health-item:last-child { margin-bottom: 0; }
.health-item .hi-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 3px;
}
.health-item .hi-val {
    font-size: 22px;
    font-weight: 800;
    color: #0f1f3d;
    line-height: 1;
}
.health-item .hi-sub {
    font-size: 11px;
    color: #9ca3af;
    margin-bottom: 8px;
}
.health-bar {
    height: 6px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}
.health-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width .8s ease;
}

/* ─── Quick Actions ─── */
.dash-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}
.dash-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #374151;
    transition: all .15s;
}
.dash-action-btn:hover {
    border-color: #1a3a6b;
    color: #1a3a6b;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(26,58,107,.1);
}
.dash-action-btn.primary {
    background: #1a3a6b;
    color: #fff;
    border-color: transparent;
}
.dash-action-btn.primary:hover {
    background: #243f73;
    color: #fff;
    box-shadow: 0 4px 14px rgba(26,58,107,.3);
}

@media(max-width:768px) {
    .dash-stats { grid-template-columns: repeat(2,1fr); }
    .dash-row { grid-template-columns: 1fr; }
    .b2b-dash-wrap { padding: 16px; }
    .dash-hero { flex-direction: column; align-items: flex-start; }
    .dash-hero-right { text-align: left; }
}
</style>
@endsection

@section('content')
<div class="b2b-dash-wrap">

    {{-- Quick Actions --}}
    <div class="dash-actions">
        <a href="{{ url('/home') }}" class="dash-action-btn primary">
            <i class="fas fa-plane"></i> Search Flights
        </a>
        <a href="{{ url('tours-search') }}" class="dash-action-btn">
            <i class="fas fa-umbrella-beach"></i> Tours Search
        </a>
        <a href="{{ url('visa-search') }}" class="dash-action-btn">
            <i class="far fa-id-card"></i> Visa Search
        </a>
        <a href="{{ url('my/bookings') }}" class="dash-action-btn">
            <i class="fas fa-list-alt"></i> My Bookings
        </a>
        <a href="{{ url('flight/booking/report') }}" class="dash-action-btn">
            <i class="fas fa-file-invoice"></i> Invoice
        </a>
    </div>

    {{-- Hero Banner --}}
    <div class="dash-hero">
        <div class="dash-hero-left">
            <div class="dash-label">Dashboard</div>
            <h2>Booking performance snapshot</h2>
            <p>Live data directly from the backend, updating your operational posture in real time.</p>
        </div>
        <div class="dash-hero-right">
            <div class="period-label">Active cycle</div>
            <div class="period-val">Current period</div>
            <div class="dash-cycle-badge">{{ $total }} bookings</div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="dash-stats">
        <div class="dstat">
            <div class="dstat-icon blue"><i class="fas fa-clipboard-list"></i></div>
            <div class="dstat-body">
                <div class="dstat-val">{{ $total }}</div>
                <div class="dstat-title">TOTAL REQUESTS</div>
                <div class="dstat-sub">All booking requests received</div>
            </div>
        </div>
        <div class="dstat">
            <div class="dstat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="dstat-body">
                <div class="dstat-val">{{ $issued }}</div>
                <div class="dstat-title">TICKET ISSUED</div>
                <div class="dstat-sub">Successfully issued tickets</div>
            </div>
        </div>
        <div class="dstat">
            <div class="dstat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="dstat-body">
                <div class="dstat-val">{{ $pending }}</div>
                <div class="dstat-title">PENDING DECISIONS</div>
                <div class="dstat-sub">Awaiting approval or payment</div>
            </div>
        </div>
        <div class="dstat">
            <div class="dstat-icon red"><i class="fas fa-ban"></i></div>
            <div class="dstat-body">
                <div class="dstat-val">{{ $cancelled }}</div>
                <div class="dstat-title">CANCELLED / VOID</div>
                <div class="dstat-sub">Requests that did not complete</div>
            </div>
        </div>
    </div>

    {{-- Booking Trend + Health Check --}}
    <div class="dash-row">

        {{-- Booking Trend Chart --}}
        <div class="dsec">
            <div class="dsec-title">Booking Trend</div>
            <div class="dsec-heading">Performance by month &amp; status</div>
            @if($monthly->count() > 0)
                <div class="chart-wrap">
                    <canvas id="trendChart"></canvas>
                </div>
            @else
                <div style="height:240px;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:14px;">
                    Booking activity will appear here once data is available.
                </div>
            @endif
        </div>

        {{-- Operational Insights / Health Check --}}
        <div class="dsec">
            <div class="dsec-title">Operational Insights</div>
            <div class="dsec-heading">Health check</div>

            <div class="health-item">
                <div class="hi-label">Fulfilment rate</div>
                <div class="hi-val">{{ $fulfillmentRate }}%</div>
                <div class="hi-sub">{{ $issued }} of {{ $total }} bookings issued.</div>
                <div class="health-bar">
                    <div class="health-bar-fill" style="width:{{ $fulfillmentRate }}%;background:{{ $fulfillmentRate >= 60 ? '#2f9e44' : ($fulfillmentRate >= 30 ? '#f59e0b' : '#dc2626') }};"></div>
                </div>
            </div>

            <div class="health-item">
                <div class="hi-label">Pending workload</div>
                <div class="hi-val">{{ $pendingWorkload }}%</div>
                <div class="hi-sub">{{ $pending }} bookings waiting for action.</div>
                <div class="health-bar">
                    <div class="health-bar-fill" style="width:{{ $pendingWorkload }}%;background:{{ $pendingWorkload <= 20 ? '#2f9e44' : ($pendingWorkload <= 50 ? '#f59e0b' : '#dc2626') }};"></div>
                </div>
            </div>

            <div class="health-item">
                <div class="hi-label">Cancellation ratio</div>
                <div class="hi-val">{{ $cancellationRate }}%</div>
                <div class="hi-sub">{{ $cancelled }} requests cancelled or voided.</div>
                <div class="health-bar">
                    <div class="health-bar-fill" style="width:{{ $cancellationRate }}%;background:{{ $cancellationRate <= 10 ? '#2f9e44' : ($cancellationRate <= 25 ? '#f59e0b' : '#dc2626') }};"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('footer_js')
@if($monthly->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const tCtx = document.getElementById('trendChart').getContext('2d');
const tLabels = {!! json_encode($monthly->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m->month)->format('M Y'))->values()) !!};
const tBookings  = {!! json_encode($monthly->pluck('bookings')->values()) !!};
const tIssued    = {!! json_encode($monthly->pluck('issued')->values()) !!};
const tCancelled = {!! json_encode($monthly->pluck('cancelled')->values()) !!};

new Chart(tCtx, {
    type: 'bar',
    data: {
        labels: tLabels,
        datasets: [
            {
                label: 'Total',
                data: tBookings,
                backgroundColor: 'rgba(26,58,107,0.75)',
                borderRadius: 5,
                borderSkipped: false,
            },
            {
                label: 'Issued',
                data: tIssued,
                backgroundColor: 'rgba(47,158,68,0.8)',
                borderRadius: 5,
                borderSkipped: false,
            },
            {
                label: 'Cancelled',
                data: tCancelled,
                backgroundColor: 'rgba(220,38,38,0.7)',
                borderRadius: 5,
                borderSkipped: false,
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', align: 'end', labels: { boxWidth: 10, font: { size: 11 } } },
            tooltip: { backgroundColor: 'rgba(15,31,61,.92)', padding: 10, cornerRadius: 8 }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
            y: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 }, color: '#9ca3af', stepSize: 1 }, border: { display: false } }
        },
        animation: { duration: 800, easing: 'easeOutQuart' }
    }
});
</script>
@endif
@endsection
