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
.btn-view { background:#17a2b8; color:#fff; border:none; padding:4px 12px; border-radius:5px; font-size:12px; }
.badge-booked { background:#d4edda; color:#155724; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600; }
.badge-pending { background:#fff3cd; color:#856404; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600; }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card" style="border-radius:8px; overflow:hidden;">
      <div class="b2b-page-header">
        <div>
          <h5><i class="typcn typcn-ticket me-2"></i> Pending Ticket Issuance</h5>
          <small>Dashboard &rsaquo; B2B &rsaquo; Pending-ticket-issuance</small>
        </div>
        <a href="{{ url('b2b/pending-ticket-issuance') }}?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}" class="btn-excel">Export to Excel</a>
      </div>

      <form method="GET" action="{{ url('b2b/pending-ticket-issuance') }}">
        <div class="b2b-filters">
          <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Booking ref / PNR..." style="width:220px;">
          </div>
          <div class="filter-group">
            <label>All</label>
            <select name="filter_status" style="width:120px;">
              <option value="all">All</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}">
          </div>
          <div class="filter-group">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}">
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
                <th>SL</th>
                <th>Created At</th>
                <th>Booking Ref</th>
                <th>PNR Code</th>
                <th>Route</th>
                <th>Total Passenger</th>
                <th>Payable Amount</th>
                <th>Ticket Request Status</th>
                <th>Booking Status</th>
                <th>Action</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $i => $b)
              @php $totalPax = (int)$b->adult + (int)$b->child + (int)$b->infant; @endphp
              <tr>
                <td>{{ $bookings->firstItem() + $i }}</td>
                <td>{{ $b->created_at ? date('d-M-Y', strtotime($b->created_at)) : 'N/A' }}</td>
                <td style="font-weight:600;">{{ $b->booking_no }}</td>
                <td>{{ $b->pnr_id ?? 'N/A' }}</td>
                <td>{{ $b->departure_location }} → {{ $b->arrival_location }}</td>
                <td class="text-center">{{ $totalPax }}</td>
                <td>BDT {{ number_format($b->total_fare ?? 0, 2) }}</td>
                <td><span class="badge-pending">Pending</span></td>
                <td><span class="badge-booked">Booked</span></td>
                <td><a href="{{ url('issue/flight/ticket/'.$b->booking_no) }}" class="btn btn-sm btn-success" style="font-size:12px;">Issue Ticket</a></td>
                <td><a href="{{ url('flight/booking/details/'.$b->booking_no) }}" class="btn-view"><i class="fas fa-eye"></i></a></td>
              </tr>
              @empty
              <tr><td colspan="11" class="text-center py-5 text-muted">No data</td></tr>
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
