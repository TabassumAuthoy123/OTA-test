@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2c-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.b2c-filters .filter-group{display:flex;flex-direction:column;gap:4px;}
.b2c-filters label{font-size:11px;font-weight:600;color:#555;margin:0;}
.b2c-filters input,.b2c-filters select{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.b2c-table th{background:#1a5276;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.b2c-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2c-table tr:hover td{background:#eaf4ff;}
.btn-excel{background:#28a745;color:#fff;border:none;padding:6px 14px;border-radius:5px;font-size:13px;font-weight:600;text-decoration:none;}
.btn-view{background:#17a2b8;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:12px;}
.s-pending{background:#fff3cd;color:#856404;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-confirmed{background:#d4edda;color:#155724;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-cancelled{background:#f8d7da;color:#721c24;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <div><h5>B2C TOUR BOOKING LIST ({{ $total }})</h5>
      <small>Dashboard &rsaquo; B2c &rsaquo; Tour_bookings</small></div>
      <a href="{{ url('b2c/tour-bookings') }}?{{ http_build_query(array_merge(request()->all(),['export'=>'excel'])) }}" class="btn-excel">Export to Excel</a>
    </div>
    <form method="GET" action="{{ url('b2c/tour-bookings') }}">
      <div class="b2c-filters">
        <div class="filter-group"><label>Status</label><select name="filter_status" style="width:130px;"><option value="all">Select Status</option><option value="0" {{ request('filter_status')=='0'?'selected':'' }}>Pending</option><option value="1" {{ request('filter_status')=='1'?'selected':'' }}>Confirmed</option><option value="2" {{ request('filter_status')=='2'?'selected':'' }}>Cancelled</option></select></div>
        <div class="filter-group"><label>Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Booking ID / Name / Email..." style="width:200px;"></div>
        <div class="filter-group"><label>Start Date</label><input type="date" name="start_date" value="{{ request('start_date') }}"></div>
        <div class="filter-group"><label>End Date</label><input type="date" name="end_date" value="{{ request('end_date') }}"></div>
        <div class="filter-group"><label>&nbsp;</label><button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button></div>
      </div>
    </form>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2c-table">
          <thead><tr><th>Booking ID</th><th>Name</th><th>Booking Reference</th><th>Email</th><th>Tour Type</th><th>Travel Date</th><th>Status</th><th>View</th></tr></thead>
          <tbody>
            @forelse($bookings as $b)
            <tr>
              <td>{{ $b->id }}</td>
              <td>{{ $b->name }}</td>
              <td>{{ $b->booking_id ?? 'N/A' }}</td>
              <td>{{ $b->email }}</td>
              <td>{{ $b->tour_type }}</td>
              <td>{{ $b->travel_date ? date('m/d/Y, g:i:s A', strtotime($b->travel_date)) : 'N/A' }}</td>
              <td>@if($b->status==1)<span class="s-confirmed">CONFIRMED</span>@elseif($b->status==2)<span class="s-cancelled">CANCELLED</span>@else<span class="s-pending">PENDING</span>@endif</td>
              <td><a href="#" class="btn-view"><i class="fas fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $bookings->firstItem()??0 }}-{{ $bookings->lastItem()??0 }} of {{ $bookings->total() }} entries</small>
        {{ $bookings->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
