@extends('master')

@section('header_css')
<style>
.b2b-page-header { background:linear-gradient(135deg,#1e3a5f,#2d5f8a); color:#fff; padding:16px 24px; border-radius:8px 8px 0 0; display:flex; justify-content:space-between; align-items:center; }
.b2b-page-header h5 { margin:0; font-size:18px; font-weight:700; }
.b2b-filters { background:#f8f9fa; padding:14px 16px; border-bottom:1px solid #dee2e6; display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
.b2b-filters .filter-group { display:flex; flex-direction:column; gap:4px; }
.b2b-filters label { font-size:11px; font-weight:600; color:#555; margin:0; }
.b2b-filters input,.b2b-filters select { font-size:13px; padding:5px 10px; border:1px solid #ced4da; border-radius:5px; height:34px; }
.b2b-table th { background:#1e3a5f; color:#fff; font-size:13px; padding:10px 12px; white-space:nowrap; }
.b2b-table td { font-size:13px; padding:9px 12px; vertical-align:middle; }
.b2b-table tr:hover td { background:#f0f4ff; }
.btn-excel { background:#28a745; color:#fff; border:none; padding:6px 14px; border-radius:5px; font-size:13px; font-weight:600; text-decoration:none; }
.s-0 { background:#fff3cd; color:#856404; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600; }
.s-1 { background:#d4edda; color:#155724; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600; }
.s-2 { background:#cce5ff; color:#004085; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600; }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card" style="border-radius:8px; overflow:hidden;">
      <div class="b2b-page-header">
        <div>
          <h5><i class="typcn typcn-plane-outline me-2"></i> Upcoming B2B Flight Booking</h5>
          <small>Dashboard &rsaquo; B2B &rsaquo; Upcoming-bookings</small>
        </div>
        <a href="{{ url('b2b/upcoming-flights') }}?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}" class="btn-excel">Export to Excel</a>
      </div>

      <form method="GET" action="{{ url('b2b/upcoming-flights') }}">
        <div class="b2b-filters">
          <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Booking ref / PNR / Agency..." style="width:250px;">
          </div>
          <div class="filter-group">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
          </div>
        </div>
      </form>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0 b2b-table">
            <thead>
              <tr>
                <th>Booking Reference</th>
                <th>Agency</th>
                <th>PNR</th>
                <th>Airline PNR</th>
                <th>Route</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Ticket Issue Last Time</th>
                <th>Journey Type</th>
                <th>API</th>
                <th>Total Passenger</th>
                <th>Status</th>
                <th>Payable Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $b)
              @php $totalPax = (int)$b->adult + (int)$b->child + (int)$b->infant; @endphp
              <tr>
                <td style="font-weight:600;">
                  <a href="{{ url('flight/booking/details/'.$b->booking_no) }}" style="color:#1e3a5f;">{{ $b->booking_no }}</a>
                </td>
                <td>{{ $b->agency_name }}</td>
                <td>{{ $b->pnr_id ?? 'N/A' }}</td>
                <td>{{ $b->airlines_pnr ?? 'N/A' }}</td>
                <td>{{ $b->departure_location }} → {{ $b->arrival_location }}</td>
                <td>{{ $b->departure_date ?? 'N/A' }}</td>
                <td>N/A</td>
                <td>{{ $b->last_ticket_datetime ?? 'N/A' }}</td>
                <td>{{ \App\Http\Controllers\B2bController::journeyTypeLabel($b->flight_type) }}</td>
                <td>{{ $b->gds ?? 'N/A' }}</td>
                <td class="text-center">{{ $totalPax }}</td>
                <td>
                  @if($b->status==0)<span class="s-0">Booking Request</span>
                  @elseif($b->status==1)<span class="s-1">Booked</span>
                  @else<span class="s-2">Issued</span>
                  @endif
                </td>
                <td>BDT {{ number_format($b->total_fare ?? 0, 2) }}</td>
              </tr>
              @empty
              <tr><td colspan="13" class="text-center py-5 text-muted">No data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
          <small class="text-muted">Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries</small>
          {{ $bookings->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
