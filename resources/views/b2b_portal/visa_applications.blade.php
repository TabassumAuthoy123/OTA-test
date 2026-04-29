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
        <h5><i class="fas fa-passport me-2"></i>Visa Application List</h5>
        <small style="opacity:.7;">Dashboard &rsaquo; Visa Application List</small>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ url(request()->path()) }}?{{ http_build_query(array_merge(request()->except('page'), ['export'=>'excel'])) }}"
           style="background:#1d7a4b;color:#fff;padding:6px 14px;border-radius:5px;font-size:13px;font-weight:600;text-decoration:none;">
          <i class="fas fa-file-excel me-1"></i> Export
        </a>
        <a href="{{ url('my/visa-applications/create') }}" class="btn btn-warning btn-sm fw-bold">
          <i class="fas fa-plus me-1"></i> New Application
        </a>
      </div>
    </div>
    <form method="GET" action="{{ url('my/visa-applications') }}">
      <div class="b2b-filters">
        <div class="fg"><label>Status</label>
          <select name="filter_status" style="width:140px;">
            <option value="all">All Status</option>
            <option value="pending" {{ request('filter_status')=='pending'?'selected':'' }}>Pending</option>
            <option value="processing" {{ request('filter_status')=='processing'?'selected':'' }}>Processing</option>
            <option value="approved" {{ request('filter_status')=='approved'?'selected':'' }}>Approved</option>
            <option value="rejected" {{ request('filter_status')=='rejected'?'selected':'' }}>Rejected</option>
            <option value="cancelled" {{ request('filter_status')=='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
        <div class="fg"><label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Name / Passport / Country..." style="width:220px;">
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
            <th>#</th><th>Applicant Name</th><th>Passport No</th>
            <th>Destination</th><th>Visa Type</th><th>Travel Date</th>
            <th>Status</th><th>Applied At</th>
          </tr></thead>
          <tbody>
            @php
              $statusColors = ['pending'=>'#fff3cd','processing'=>'#cce5ff','approved'=>'#d4edda','rejected'=>'#f8d7da','cancelled'=>'#e2e3e5'];
              $statusText   = ['pending'=>'#856404','processing'=>'#004085','approved'=>'#155724','rejected'=>'#721c24','cancelled'=>'#383d41'];
            @endphp
            @forelse($applications as $a)
            <tr>
              <td>{{ $a->id }}</td>
              <td style="font-weight:600;">{{ $a->applicant_name }}</td>
              <td>{{ $a->passport_no ?? 'N/A' }}</td>
              <td>{{ $a->destination_country }}</td>
              <td>{{ ucfirst($a->visa_type) }}</td>
              <td>{{ $a->travel_date ? date('d-m-Y', strtotime($a->travel_date)) : 'N/A' }}</td>
              <td>
                <span style="background:{{ $statusColors[$a->status]??'#eee' }};color:{{ $statusText[$a->status]??'#333' }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;">
                  {{ strtoupper($a->status) }}
                </span>
              </td>
              <td style="font-size:12px;">{{ $a->created_at ? date('d-m-Y', strtotime($a->created_at)) : 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">
              <i class="fas fa-passport fa-2x mb-2 d-block" style="opacity:.3;"></i>
              No visa applications yet. <a href="{{ url('my/visa-applications/create') }}">Submit one now</a>
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $applications->firstItem()??0 }}&ndash;{{ $applications->lastItem()??0 }} of {{ $applications->total() }}</small>
        {{ $applications->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
