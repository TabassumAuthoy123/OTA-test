@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2b-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.b2b-filters label{font-size:11px;font-weight:600;color:#555;margin:0;display:block;}
.b2b-filters input{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.b2b-table th{background:#0f1f3d;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.b2b-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2b-table tr:hover td{background:#f0f4ff;}
.s-open{background:#fff3cd;color:#856404;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-in_progress{background:#cce5ff;color:#004085;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-resolved{background:#d4edda;color:#155724;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.s-closed{background:#e2e3e5;color:#383d41;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.btn-new{background:#0f1f3d;color:#fff;border:none;padding:6px 14px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;display:inline-block;}
.btn-new:hover{background:#1a3a6e;color:#fff;text-decoration:none;}
.alert-success-b2b{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:10px 16px;border-radius:6px;font-size:13px;margin:12px 16px 0;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <div>
        <h5>
          @if($issueType == 'reissue')<i class="fas fa-redo me-2"></i>
          @elseif($issueType == 'refund')<i class="fas fa-hand-holding-usd me-2"></i>
          @else<i class="fas fa-ban me-2"></i>@endif
          {{ $title }}
        </h5>
        <small>Dashboard &rsaquo; {{ ucfirst($issueType) }} &rsaquo; {{ $title }}</small>
      </div>
      @php
        $createRoutes = ['reissue'=>'MyCreateReissue','refund'=>'MyCreateRefund','void'=>'MyCreateVoid'];
        $createRoute  = $createRoutes[$issueType] ?? 'MyCreateReissue';
      @endphp
      <a href="{{ route($createRoute) }}" class="btn-new">
        <i class="fas fa-plus me-1"></i> New {{ ucfirst($issueType) }} Request
      </a>
    </div>

    @if(session('success'))
      <div class="alert-success-b2b"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ request()->url() }}">
      <div class="b2b-filters">
        <div>
          <label>Search Booking Ref / Subject</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." style="width:260px;">
        </div>
        <div>
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;"><i class="fas fa-search me-1"></i>Search</button>
        </div>
        @if(request('search'))
        <div>
          <label>&nbsp;</label>
          <a href="{{ request()->url() }}" class="btn btn-secondary btn-sm" style="height:34px;line-height:22px;">Clear</a>
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
              <th>Ref No</th>
              <th>Booking Ref</th>
              <th>Subject</th>
              <th>Description</th>
              <th>Status</th>
              <th>Admin Reply</th>
              <th>Submitted</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tickets as $i => $t)
            <tr>
              <td>{{ $tickets->firstItem() + $i }}</td>
              <td style="font-weight:700;color:#0f1f3d;">#{{ $t->id }}</td>
              <td>{{ $t->booking_ref ?? 'N/A' }}</td>
              <td>{{ $t->subject }}</td>
              <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $t->description }}</td>
              <td><span class="s-{{ $t->status }}">{{ ucwords(str_replace('_',' ',$t->status)) }}</span></td>
              <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $t->admin_reply ?? '—' }}
              </td>
              <td>{{ $t->created_at ? date('d M Y', strtotime($t->created_at)) : '' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.3;"></i>
                No {{ $issueType }} requests found.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $tickets->firstItem() ?? 0 }}&ndash;{{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries</small>
        {{ $tickets->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
