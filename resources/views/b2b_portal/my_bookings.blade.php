@extends('master')
@section('header_css')
<style>
.b2b-list-header{display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-bottom:1px solid #e9ecef;flex-wrap:wrap;gap:10px;}
.b2b-list-title{font-size:15px;font-weight:700;color:#0f1f3d;margin:0;}
.b2b-date-filter{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.b2b-date-filter input[type="date"]{border:1px solid #ced4da;border-radius:5px;padding:5px 10px;font-size:13px;height:34px;color:#333;outline:none;}
.b2b-date-filter input[type="date"]:focus{border-color:#0f1f3d;}
.b2b-date-arrow{color:#888;font-size:13px;}
.btn-export{background:#0f1f3d;color:#fff;border:none;padding:6px 16px;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;height:34px;}
.btn-export:hover{background:#1a3a6b;color:#fff;text-decoration:none;}
.b2b-table-wrap{overflow-x:auto;}
.b2b-tbl{width:100%;border-collapse:collapse;font-size:13px;}
.b2b-tbl thead th{background:#0f1f3d;color:#fff;padding:11px 14px;white-space:nowrap;font-weight:600;font-size:12px;border:none;}
.b2b-tbl tbody td{padding:10px 14px;border-bottom:1px solid #f0f0f0;vertical-align:middle;color:#333;}
.b2b-tbl tbody tr:hover td{background:#f4f7ff;}
.booking-ref-link{color:#0d6efd;font-weight:600;text-decoration:none;}
.booking-ref-link:hover{text-decoration:underline;color:#0a58ca;}
.status-badge{display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;white-space:nowrap;}
.st-hold{background:#fff3cd;color:#856404;}
.st-success{background:#d4edda;color:#155724;}
.st-issued{background:#cce5ff;color:#004085;}
.st-cancelled{background:#f8d7da;color:#721c24;}
.st-refund{background:#f8d7da;color:#721c24;}
.st-void{background:#e2e3e5;color:#383d41;}
.btn-eye{background:#0f1f3d;color:#fff;border:none;padding:5px 12px;border-radius:5px;font-size:13px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
.btn-eye:hover{background:#1a3a6b;color:#fff;text-decoration:none;}
.b2b-pagination-bar{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-12">
  @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;border:1px solid #e0e0e0;">

    {{-- Header: Title + Date Filter + Export --}}
    <div class="b2b-list-header">
      <span class="b2b-list-title">{{ $title }} ({{ $bookings->total() }})</span>
      <form method="GET" action="{{ request()->url() }}" class="b2b-date-filter">
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Start date">
        <span class="b2b-date-arrow">→</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="End date">
        <button type="submit" class="btn-export" style="background:#1a3a6b;">
          <i class="fas fa-search" style="font-size:12px;"></i>
        </button>
        @if(request('start_date') || request('end_date'))
          <a href="{{ request()->url() }}" class="btn-export" style="background:#6c757d;">
            <i class="fas fa-times" style="font-size:12px;"></i>
          </a>
        @endif
        <a href="{{ request()->url() }}?export=excel{{ request('start_date') ? '&start_date='.request('start_date') : '' }}{{ request('end_date') ? '&end_date='.request('end_date') : '' }}" class="btn-export">
          <i class="fas fa-file-excel"></i> Export to Excel
        </a>
      </form>
    </div>

    {{-- Table --}}
    <div class="b2b-table-wrap">
      <table class="b2b-tbl">
        <thead>
          <tr>
            <th>#</th>
            <th>Booking Ref</th>
            <th>Booking Date</th>
            <th>Booking Time</th>
            <th>Journey Type</th>
            <th>PNR Code</th>
            <th>Route</th>
            <th>Payable Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $i => $b)
          @php
            $statusNum = is_numeric($b->status) ? (int)$b->status : -1;
            if ($statusNum === 0) { $stClass = 'st-hold'; $stText = 'Booking-Hold'; }
            elseif ($statusNum === 1) { $stClass = 'st-success'; $stText = 'Booking-Success'; }
            elseif ($statusNum === 2) { $stClass = 'st-issued'; $stText = 'Ticket-Issued'; }
            elseif ($statusNum === 3) { $stClass = 'st-cancelled'; $stText = 'Booking-Cancelled'; }
            elseif ($statusNum === 4) { $stClass = 'st-refund'; $stText = 'Ticket-Refund'; }
            elseif ($statusNum === 5) { $stClass = 'st-void'; $stText = 'Voided'; }
            else {
              $stText = ucwords(str_replace(['-','_'], ' ', $b->status));
              $stLower = strtolower($b->status);
              if (str_contains($stLower, 'cancel')) $stClass = 'st-cancelled';
              elseif (str_contains($stLower, 'refund')) $stClass = 'st-refund';
              elseif (str_contains($stLower, 'void')) $stClass = 'st-void';
              elseif (str_contains($stLower, 'issue') || str_contains($stLower, 'approv')) $stClass = 'st-issued';
              elseif (str_contains($stLower, 'hold') || str_contains($stLower, 'pending')) $stClass = 'st-hold';
              else $stClass = 'st-success';
            }
            $depCode = strtoupper(substr($b->departure_location ?? '', 0, 3));
            $arrCode = strtoupper(substr($b->arrival_location ?? '', 0, 3));
            $jType = '';
            if (!empty($b->journey_type)) {
              $jt = (int)$b->journey_type;
              $jType = $jt === 1 ? 'One Way' : ($jt === 2 ? 'Round Trip' : ($jt === 3 ? 'Multi City' : $b->journey_type));
            }
          @endphp
          <tr>
            <td style="color:#555;">{{ $bookings->firstItem() + $i }}</td>
            <td>
              <a href="{{ url('my/bookings/'.$b->id) }}" class="booking-ref-link">{{ $b->booking_no }}</a>
            </td>
            <td>{{ $b->created_at ? date('d-m-Y', strtotime($b->created_at)) : 'N/A' }}</td>
            <td>{{ $b->created_at ? date('h:i A', strtotime($b->created_at)) : 'N/A' }}</td>
            <td>{{ $jType ?: 'One Way' }}</td>
            <td style="font-weight:600;">{{ $b->pnr_id ?? 'N/A' }}</td>
            <td style="font-weight:600;">{{ $depCode }}-{{ $arrCode }}</td>
            <td style="font-weight:600;">{{ number_format($b->total_fare ?? 0, 2) }}</td>
            <td><span class="status-badge {{ $stClass }}">{{ $stText }}</span></td>
            <td>
              <a href="{{ url('my/bookings/'.$b->id) }}" class="btn-eye">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="text-center py-5 text-muted">
              <i class="fas fa-ticket-alt fa-2x mb-2 d-block" style="opacity:.25;"></i>
              No data
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="b2b-pagination-bar">
      <small class="text-muted">Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }}</small>
      {{ $bookings->links() }}
    </div>
  </div>
</div></div>
@endsection
