@extends('master')
@section('header_css')
<style>
.b2b-list-header{display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-bottom:1px solid #e9ecef;flex-wrap:wrap;gap:10px;}
.b2b-list-title{font-size:15px;font-weight:700;color:#0f1f3d;margin:0;}
.b2b-date-filter{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.b2b-date-filter input[type="date"]{border:1px solid #ced4da;border-radius:5px;padding:5px 10px;font-size:13px;height:34px;outline:none;}
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
.booking-ref-link:hover{text-decoration:underline;}
.status-badge{display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.st-open{background:#fff3cd;color:#856404;}
.st-in_progress{background:#cce5ff;color:#004085;}
.st-resolved{background:#d4edda;color:#155724;}
.st-closed{background:#e2e3e5;color:#383d41;}
.btn-eye{background:#0f1f3d;color:#fff;border:none;padding:5px 12px;border-radius:5px;font-size:13px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
.btn-eye:hover{background:#1a3a6b;color:#fff;text-decoration:none;}
.b2b-pagination-bar{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px;}
.badge-na{background:#e9ecef;color:#6c757d;font-size:11px;padding:2px 8px;border-radius:8px;font-weight:600;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-12">
  @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;border:1px solid #e0e0e0;">

    {{-- Header: Title + Date Filter + Export --}}
    <div class="b2b-list-header">
      <span class="b2b-list-title">{{ $title }} ({{ $tickets->total() }})</span>
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        @php
          $createRoutes = ['reissue'=>'MyCreateReissue','refund'=>'MyCreateRefund','void'=>'MyCreateVoid'];
          $createRoute  = $createRoutes[$issueType] ?? 'MyCreateReissue';
        @endphp
        <a href="{{ route($createRoute) }}" class="btn-export" style="background:#f0a500;">
          <i class="fas fa-plus" style="font-size:12px;"></i> New {{ ucfirst($issueType) }} Request
        </a>
        <form method="GET" action="{{ request()->url() }}" class="b2b-date-filter" style="margin:0;">
          <input type="date" name="start_date" value="{{ request('start_date') }}">
          <span class="b2b-date-arrow">→</span>
          <input type="date" name="end_date" value="{{ request('end_date') }}">
          <button type="submit" class="btn-export" style="background:#1a3a6b;">
            <i class="fas fa-search" style="font-size:12px;"></i>
          </button>
          @if(request('start_date') || request('end_date'))
            <a href="{{ request()->url() }}" class="btn-export" style="background:#6c757d;">
              <i class="fas fa-times" style="font-size:12px;"></i>
            </a>
          @endif
        </form>
        <a href="{{ request()->url() }}?export=excel{{ request('start_date') ? '&start_date='.request('start_date') : '' }}{{ request('end_date') ? '&end_date='.request('end_date') : '' }}" class="btn-export" style="background:#1d7a4b;">
          <i class="fas fa-file-excel"></i> Export
        </a>
      </div>
    </div>

    {{-- Table --}}
    <div class="b2b-table-wrap">
      <table class="b2b-tbl">
        <thead>
          <tr>
            <th>SL</th>
            <th>Booking Date</th>
            <th>Booking Time</th>
            <th>Ref No.</th>
            <th>Booking Ref No.</th>
            <th>Staff Status</th>
            <th>Staff Name</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tickets as $i => $t)
          <tr>
            <td style="color:#555;">{{ $tickets->firstItem() + $i }}</td>
            <td>{{ $t->created_at ? date('d-m-Y', strtotime($t->created_at)) : 'N/A' }}</td>
            <td>{{ $t->created_at ? date('h:i A', strtotime($t->created_at)) : 'N/A' }}</td>
            <td style="font-weight:700;color:#0f1f3d;">#{{ str_pad($t->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td>
              @if($t->booking_ref)
                <a href="{{ url('my/bookings') }}?search={{ urlencode($t->booking_ref) }}" class="booking-ref-link">{{ $t->booking_ref }}</a>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td><span class="badge-na">N/A</span></td>
            <td><span class="badge-na">N/A</span></td>
            <td><span class="status-badge st-{{ $t->status }}">{{ strtoupper(str_replace('_',' ',$t->status)) }}</span></td>
            <td>
              <a href="#" class="btn-eye" title="View Details">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center py-5 text-muted">
              <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.25;"></i>
              No data
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="b2b-pagination-bar">
      <small class="text-muted">Showing {{ $tickets->firstItem() ?? 0 }}–{{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }}</small>
      {{ $tickets->links() }}
    </div>
  </div>
</div></div>
@endsection
