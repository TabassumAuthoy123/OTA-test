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
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <div>
        <h5><i class="fas fa-headset me-2"></i>Booking Support</h5>
        <small style="opacity:.7;">Dashboard &rsaquo; Booking Support</small>
      </div>
      <a href="{{ url('my/booking-support/create') }}" class="btn btn-warning btn-sm fw-bold">
        <i class="fas fa-plus me-1"></i> New Ticket
      </a>
    </div>
    <form method="GET" action="{{ url('my/booking-support') }}">
      <div class="b2b-filters">
        <div class="fg"><label>Status</label>
          <select name="filter_status" style="width:140px;">
            <option value="all">All Status</option>
            <option value="open" {{ request('filter_status')=='open'?'selected':'' }}>Open</option>
            <option value="in_progress" {{ request('filter_status')=='in_progress'?'selected':'' }}>In Progress</option>
            <option value="resolved" {{ request('filter_status')=='resolved'?'selected':'' }}>Resolved</option>
            <option value="closed" {{ request('filter_status')=='closed'?'selected':'' }}>Closed</option>
          </select>
        </div>
        <div class="fg"><label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Subject / Booking Ref..." style="width:220px;">
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
            <th>Ticket #</th><th>Booking Ref</th><th>Issue Type</th>
            <th>Subject</th><th>Status</th><th>Submitted</th>
          </tr></thead>
          <tbody>
            @php
              $sc = ['open'=>['#fff3cd','#856404'],'in_progress'=>['#cce5ff','#004085'],'resolved'=>['#d4edda','#155724'],'closed'=>['#e2e3e5','#383d41']];
            @endphp
            @forelse($tickets as $t)
            <tr>
              <td style="font-weight:700;color:#0f1f3d;">#{{ $t->id }}</td>
              <td>{{ $t->booking_ref ?? '—' }}</td>
              <td>{{ ucwords(str_replace('_',' ',$t->issue_type)) }}</td>
              <td>{{ $t->subject }}</td>
              <td>
                <span style="background:{{ ($sc[$t->status]??['#eee','#333'])[0] }};color:{{ ($sc[$t->status]??['#eee','#333'])[1] }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;">
                  {{ strtoupper(str_replace('_',' ',$t->status)) }}
                </span>
              </td>
              <td style="font-size:12px;">{{ $t->created_at ? date('d-m-Y', strtotime($t->created_at)) : 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">
              <i class="fas fa-headset fa-2x mb-2 d-block" style="opacity:.3;"></i>
              No support tickets yet. <a href="{{ url('my/booking-support/create') }}">Submit a ticket</a>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $tickets->firstItem()??0 }}&ndash;{{ $tickets->lastItem()??0 }} of {{ $tickets->total() }}</small>
        {{ $tickets->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
