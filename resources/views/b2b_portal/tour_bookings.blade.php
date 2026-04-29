@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6b);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2b-filters{background:#f8f9fa;padding:12px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.b2b-filters .fg{display:flex;flex-direction:column;gap:3px;}
.b2b-filters label{font-size:11px;font-weight:600;color:#555;margin:0;}
.b2b-filters input,.b2b-filters select{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.b2b-table th{background:#0f1f3d;color:#fff;font-size:12px;padding:10px 12px;white-space:nowrap;}
.b2b-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2b-table tr:hover td{background:#f0f4ff;}
.btn-view{background:#f0a500;color:#fff;padding:4px 12px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;}
.s-pending{background:#fff3cd;color:#856404;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:700;}
.s-confirmed{background:#d4edda;color:#155724;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:700;}
.s-cancelled{background:#f8d7da;color:#721c24;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:700;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <div>
        <h5><i class="fas fa-map-marked-alt me-2"></i>{{ $pageTitle ?? 'My Tour Bookings' }} ({{ $bookings->total() }})</h5>
        <small style="opacity:.7;">Dashboard &rsaquo; Tour Bookings</small>
      </div>
    </div>
    <form method="GET" action="{{ request()->url() }}">
      <div class="b2b-filters">
        <div class="fg"><label>Status</label>
          <select name="filter_status" style="width:130px;">
            <option value="all">All Status</option>
            <option value="0" {{ request('filter_status')=='0'?'selected':'' }}>Pending</option>
            <option value="1" {{ request('filter_status')=='1'?'selected':'' }}>Confirmed</option>
            <option value="2" {{ request('filter_status')=='2'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
        <div class="fg"><label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Booking ID / Name..." style="width:200px;">
        </div>
        <div class="fg"><label>Start Date</label>
          <input type="date" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="fg"><label>End Date</label>
          <input type="date" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="fg"><label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;"><i class="fas fa-search me-1"></i>Search</button>
        </div>
      </div>
    </form>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2b-table">
          <thead><tr>
            <th>Booking ID</th><th>Booking Reference</th>
            <th>Name</th><th>Email</th>
            <th>Tour Type</th><th>Travel Date</th><th>Amount</th>
            <th>Status</th>
          </tr></thead>
          <tbody>
            @forelse($bookings as $b)
            <tr>
              <td>{{ $b->id }}</td>
              <td>{{ $b->booking_id ?? 'N/A' }}</td>
              <td>{{ $b->name }}</td>
              <td style="font-size:12px;">{{ $b->email }}</td>
              <td>{{ ucfirst($b->tour_type) }}</td>
              <td>{{ $b->travel_date ? date('d-m-Y', strtotime($b->travel_date)) : 'N/A' }}</td>
              <td>{{ number_format($b->amount ?? 0, 2) }} BDT</td>
              <td>
                @if($b->status==1)<span class="s-confirmed">CONFIRMED</span>
                @elseif($b->status==2)<span class="s-cancelled">CANCELLED</span>
                @else<span class="s-pending">PENDING</span>@endif
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">
              <i class="fas fa-map fa-2x mb-2 d-block" style="opacity:.3;"></i>No tour bookings found.
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $bookings->firstItem()??0 }}&ndash;{{ $bookings->lastItem()??0 }} of {{ $bookings->total() }}</small>
        {{ $bookings->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
