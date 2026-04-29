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
.btn-create { background:#f0a500; color:#fff; border:none; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:700; text-decoration:none; }
.btn-view { background:#17a2b8; color:#fff; border:none; padding:4px 10px; border-radius:5px; font-size:12px; }
.btn-addmoney { background:#28a745; color:#fff; border:none; padding:4px 10px; border-radius:5px; font-size:12px; }
.badge-active { background:#d4edda; color:#155724; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; }
.badge-inactive { background:#f8d7da; color:#721c24; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:700; }
.agency-logo { max-height:40px; max-width:70px; border-radius:4px; }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card" style="border-radius:8px; overflow:hidden;">
      <div class="b2b-page-header">
        <div>
          <h5><i class="typcn typcn-group-outline me-2"></i> Agency List</h5>
          <small>Dashboard &rsaquo; B2B &rsaquo; Agency-list</small>
        </div>
        <a href="{{ url('create/b2b/users') }}" class="btn-create">+ Create Agency</a>
      </div>

      <form method="GET" action="{{ url('b2b/agency-list') }}">
        <div class="b2b-filters">
          <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Agency name / email..." style="width:200px;">
          </div>
          <div class="filter-group">
            <label>Filter by status</label>
            <select name="status">
              <option value="all" {{ request('status','all')=='all'?'selected':'' }}>Filter by status</option>
              <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
              <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Filter by category</label>
            <select name="category" style="width:150px;">
              <option value="">Filter by category</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Filter by type</label>
            <select name="type" style="width:130px;">
              <option value="">Filter by type</option>
            </select>
          </div>
          <div class="filter-group">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
          </div>
          <div class="filter-group" style="margin-left:auto;">
            <label>&nbsp;</label>
            <a href="{{ url('b2b/agency-list') }}?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}" class="btn-excel">Export to Excel</a>
          </div>
        </div>
      </form>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0 b2b-table">
            <thead>
              <tr>
                <th>SL</th>
                <th>Logo</th>
                <th>Unique ID</th>
                <th>Created Date</th>
                <th>Agency Name</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($agencies as $i => $a)
              <tr>
                <td>{{ $agencies->firstItem() + $i }}</td>
                <td>
                  @if($a->logo && file_exists(public_path($a->logo)))
                    <img src="{{ url($a->logo) }}" class="agency-logo" alt="{{ $a->agency_name }}">
                  @else
                    <span style="font-size:28px; color:#ccc;"><i class="fas fa-user-circle"></i></span>
                  @endif
                </td>
                <td style="color:#aaa; font-size:12px;">B2B-{{ str_pad($a->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $a->created_at ? date('d-m-Y', strtotime($a->created_at)) : 'N/A' }}</td>
                <td style="font-weight:600;">{{ $a->agency_name }}</td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->phone }}</td>
                <td>
                  @if($a->status == 1)
                    <span class="badge-active">ACTIVE</span>
                  @else
                    <span class="badge-inactive">INACTIVE</span>
                  @endif
                </td>
                <td style="white-space:nowrap;">
                  <a href="{{ url('view/b2b/users') }}" class="btn-view">View</a>
                  <button class="btn-addmoney ms-1" onclick="openAddMoneyModal({{ $a->id }}, '{{ $a->agency_name }}')">
                    <i class="fas fa-plus-circle"></i> Add money
                  </button>
                </td>
              </tr>
              @empty
              <tr><td colspan="9" class="text-center py-5 text-muted">No agencies found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
          <small class="text-muted">Showing {{ $agencies->firstItem() ?? 0 }}–{{ $agencies->lastItem() ?? 0 }} of {{ $agencies->total() }} entries</small>
          {{ $agencies->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Add Money Modal --}}
<div class="modal fade" id="addMoneyModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addMoneyForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header" style="background:#1e3a5f; color:#fff;">
          <h5 class="modal-title">Add Money — <span id="modalAgencyName"></span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-bold">Amount (BDT)</label>
            <input type="number" name="amount" class="form-control" min="1" step="0.01" required placeholder="Enter amount">
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Note (optional)</label>
            <input type="text" name="note" class="form-control" placeholder="Reason...">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add Money</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('footer_js')
<script>
function openAddMoneyModal(userId, name) {
    document.getElementById('addMoneyForm').action = '/b2b/agency/' + userId + '/add-money';
    document.getElementById('modalAgencyName').textContent = name;
    new bootstrap.Modal(document.getElementById('addMoneyModal')).show();
}
</script>
@endsection
