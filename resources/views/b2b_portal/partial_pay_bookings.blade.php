@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2b-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.b2b-filters label{font-size:11px;font-weight:600;color:#555;margin:0;display:block;}
.b2b-filters input,.b2b-filters select{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.b2b-table th{background:#0f1f3d;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.b2b-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2b-table tr:hover td{background:#f0f4ff;}
.s-0{background:#fff3cd;color:#856404;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-1{background:#d4edda;color:#155724;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-2{background:#cce5ff;color:#004085;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-3{background:#f8d7da;color:#721c24;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.btn-view{background:#f0a500;color:#fff;border:none;padding:4px 12px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;display:inline-block;}
.btn-view:hover{background:#d4911a;color:#fff;text-decoration:none;}
.pay-progress{background:#e9ecef;border-radius:10px;height:8px;overflow:hidden;min-width:80px;}
.pay-progress-bar{background:#f0a500;height:100%;border-radius:10px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <div>
        <h5><i class="fas fa-credit-card me-2"></i> My Partial Pay Bookings</h5>
        <small>Dashboard &rsaquo; Partial Pay Bookings</small>
      </div>
      <a href="{{ url(request()->path()) }}?{{ http_build_query(array_merge(request()->except('page'), ['export'=>'excel'])) }}"
         style="background:#1d7a4b;color:#fff;padding:6px 14px;border-radius:5px;font-size:13px;font-weight:700;text-decoration:none;">
        <i class="fas fa-file-excel me-1"></i> Export
      </a>
    </div>

    <form method="GET" action="{{ url('my/partial-pay-bookings') }}">
      <div class="b2b-filters">
        <div>
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Booking No / PNR..." style="width:240px;">
        </div>
        <div>
          <label>Status</label>
          <select name="status" style="width:140px;">
            <option value="all">All Status</option>
            <option value="0" {{ request('status')=='0'?'selected':'' }}>Booking Request</option>
            <option value="1" {{ request('status')=='1'?'selected':'' }}>Booked</option>
            <option value="2" {{ request('status')=='2'?'selected':'' }}>Issued</option>
            <option value="3" {{ request('status')=='3'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
        <div>
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;"><i class="fas fa-search me-1"></i>Search</button>
        </div>
        @if(request()->anyFilled(['search','status']))
        <div>
          <label>&nbsp;</label>
          <a href="{{ url('my/partial-pay-bookings') }}" class="btn btn-secondary btn-sm" style="height:34px;line-height:22px;">Clear</a>
        </div>
        @endif
      </div>
    </form>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2b-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Booking No</th>
              <th>PNR</th>
              <th>Route</th>
              <th>Departure</th>
              <th>Total Fare</th>
              <th>Paid Amount</th>
              <th>Due Amount</th>
              <th>Payment Progress</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bookings as $i => $b)
            @php
              $total   = (float)($b->total_fare ?? 0);
              $paid    = (float)($b->paid_amount ?? 0);
              $due     = max(0, $total - $paid);
              $pct     = $total > 0 ? min(100, round($paid / $total * 100)) : 0;
            @endphp
            <tr>
              <td>{{ $bookings->firstItem() + $i }}</td>
              <td style="font-weight:700;color:#0f1f3d;">
                <a href="{{ url('flight/booking/details/'.$b->booking_no) }}" style="color:#0f1f3d;text-decoration:none;">
                  {{ $b->booking_no }}
                </a>
              </td>
              <td>{{ $b->pnr_id ?? 'N/A' }}</td>
              <td>
                <span style="font-weight:600;">{{ $b->departure_location }}</span>
                <i class="fas fa-arrow-right mx-1" style="color:#f0a500;font-size:11px;"></i>
                <span style="font-weight:600;">{{ $b->arrival_location }}</span>
              </td>
              <td>{{ $b->departure_date ? date('d M Y', strtotime($b->departure_date)) : 'N/A' }}</td>
              <td>{{ number_format($total, 2) }}</td>
              <td style="color:#28a745;font-weight:600;">{{ number_format($paid, 2) }}</td>
              <td style="color:{{ $due > 0 ? '#dc3545' : '#28a745' }};font-weight:600;">{{ number_format($due, 2) }}</td>
              <td>
                <div class="pay-progress">
                  <div class="pay-progress-bar" style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#28a745' : '#f0a500' }};"></div>
                </div>
                <small style="font-size:10px;color:#888;">{{ $pct }}%</small>
              </td>
              <td>
                @if($b->status == 0)<span class="s-0">Booking Request</span>
                @elseif($b->status == 1)<span class="s-1">Booked</span>
                @elseif($b->status == 2)<span class="s-2">Issued</span>
                @else<span class="s-3">Cancelled</span>@endif
              </td>
              <td>
                <a href="{{ url('flight/booking/details/'.$b->booking_no) }}" class="btn-view">
                  <i class="fas fa-eye me-1"></i>View
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11" class="text-center py-5 text-muted">
                <i class="fas fa-credit-card fa-2x mb-2 d-block" style="opacity:.3;"></i>
                No partial pay bookings found.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">
          Showing {{ $bookings->firstItem() ?? 0 }}&ndash;{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
        </small>
        {{ $bookings->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
