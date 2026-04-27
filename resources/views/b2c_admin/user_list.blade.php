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
.btn-excel{background:#f0a500;color:#fff;border:none;padding:6px 14px;border-radius:5px;font-size:13px;font-weight:600;text-decoration:none;}
.btn-view{background:#1a5276;color:#fff;border:none;padding:4px 12px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;}
.badge-active{background:#d4edda;color:#155724;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.badge-inactive{background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <div><h5>B2C User List</h5><small>Dashboard &rsaquo; B2c &rsaquo; User-list</small></div>
      <a href="{{ url('b2c/user-list') }}?{{ http_build_query(array_merge(request()->all(),['export'=>'excel'])) }}" class="btn-excel">Export to Excel!</a>
    </div>
    <form method="GET" action="{{ url('b2c/user-list') }}">
      <div class="b2c-filters">
        <div class="filter-group"><label>Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." style="width:200px;"></div>
        <div class="filter-group"><label>Status</label><select name="filter_status" style="width:100px;"><option value="all">All</option><option value="1" {{ request('filter_status')=='1'?'selected':'' }}>Active</option><option value="0" {{ request('filter_status')=='0'?'selected':'' }}>Inactive</option></select></div>
        <div class="filter-group"><label>&nbsp;</label><button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button></div>
      </div>
    </form>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2c-table">
          <thead><tr><th>SL</th><th>Created Date</th><th>Username</th><th>Name</th><th>Email</th><th>Phone No</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            @forelse($users as $i => $u)
            <tr>
              <td>{{ $users->firstItem() + $i }}</td>
              <td>{{ $u->created_at ? date('d-m-Y', strtotime($u->created_at)) : 'N/A' }}</td>
              <td>{{ $u->email }}</td>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>{{ $u->phone ?? 'N/A' }}</td>
              <td>@if($u->status==1 || $u->status===null)<span class="badge-active">ACTIVE</span>@else<span class="badge-inactive">INACTIVE</span>@endif</td>
              <td><a href="{{ url('b2c/user/'.$u->id) }}" class="btn-view">View</a></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">No B2C users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $users->firstItem()??0 }}-{{ $users->lastItem()??0 }} of {{ $users->total() }} entries</small>
        {{ $users->links() }}
      </div>
    </div>
  </div>
</div></div>
@endsection
