@extends('master')
@section('header_css')
<style>
.cfg-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.cfg-header h5{margin:0;font-size:18px;font-weight:700;}
.cfg-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.cfg-filters .fg{display:flex;flex-direction:column;gap:4px;}
.cfg-filters label{font-size:11px;font-weight:600;color:#555;margin:0;}
.cfg-filters input,.cfg-filters select{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.cfg-table th{background:#1a5276;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.cfg-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.cfg-table tr:hover td{background:#eaf4ff;}
.badge-active{background:#d4edda;color:#155724;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
.badge-inactive{background:#f8d7da;color:#721c24;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="cfg-header">
      <div>
        <h5><i class="typcn typcn-cancel-outline me-2"></i> Block Routes</h5>
        <small>Configuration &rsaquo; Block Route</small>
      </div>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="typcn typcn-plus"></i> Add Block Rule
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success m-3 py-2">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ url('configuration/block-routes') }}">
      <div class="cfg-filters">
        <div class="fg">
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Name / Route / Airline..." style="width:260px;">
        </div>
        <div class="fg">
          <label>Status</label>
          <select name="filter_status">
            <option value="all">All</option>
            <option value="1" {{ request('filter_status')=='1'?'selected':'' }}>Active</option>
            <option value="0" {{ request('filter_status')=='0'?'selected':'' }}>Inactive</option>
          </select>
        </div>
        <div class="fg"><label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
        </div>
        @if(request()->hasAny(['search','filter_status']))
          <div class="fg"><label>&nbsp;</label>
            <a href="{{ url('configuration/block-routes') }}" class="btn btn-secondary btn-sm" style="height:34px;">Clear</a>
          </div>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered cfg-table mb-0">
        <thead>
          <tr>
            <th>#</th><th>Name</th><th>GDS</th><th>Airline</th><th>Route</th>
            <th>Cabin</th><th>Block Type</th><th>Reason</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rules as $i => $rule)
          <tr>
            <td>{{ $rules->firstItem() + $i }}</td>
            <td><strong>{{ $rule->name }}</strong></td>
            <td>{{ strtoupper($rule->gds) }}</td>
            <td>{{ $rule->airline_code ?: '—' }}</td>
            <td>{{ $rule->route_from ?: '*' }} → {{ $rule->route_to ?: '*' }}</td>
            <td>{{ $rule->cabin_class ?: 'All' }}</td>
            <td><span class="badge bg-secondary">{{ ucfirst($rule->block_type) }}</span></td>
            <td>{{ Str::limit($rule->reason, 50) ?: '—' }}</td>
            <td><span class="{{ $rule->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $rule->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td>
              <button class="btn btn-sm btn-warning btn-edit-br" style="font-size:11px;"
                data-id="{{ $rule->id }}" data-name="{{ $rule->name }}"
                data-gds="{{ $rule->gds }}" data-airline="{{ $rule->airline_code }}"
                data-rfrom="{{ $rule->route_from }}" data-rto="{{ $rule->route_to }}"
                data-cabin="{{ $rule->cabin_class }}" data-btype="{{ $rule->block_type }}"
                data-reason="{{ $rule->reason }}" data-active="{{ $rule->is_active }}">Edit</button>
              <form method="POST" action="{{ url('configuration/block-routes/'.$rule->id) }}" class="d-inline"
                onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" style="font-size:11px;">Del</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" class="text-center text-muted py-4">No block rules found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $rules->links() }}</div>
  </div>
</div></div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Add Block Route Rule</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ url('configuration/block-routes') }}">
        @csrf
        <div class="modal-body">
          @include('configuration._block_route_fields')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Block Route</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Edit Block Route Rule</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-body">
          @include('configuration._block_route_fields', ['edit' => true])
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
<script>
document.querySelectorAll('.btn-edit-br').forEach(btn => {
  btn.addEventListener('click', function() {
    const d = this.dataset;
    const f = document.getElementById('editForm');
    f.action = '/configuration/block-routes/' + d.id;
    f.querySelector('[name=name]').value = d.name;
    f.querySelector('[name=gds]').value = d.gds;
    f.querySelector('[name=airline_code]').value = d.airline || '';
    f.querySelector('[name=route_from]').value = d.rfrom || '';
    f.querySelector('[name=route_to]').value = d.rto || '';
    f.querySelector('[name=cabin_class]').value = d.cabin || '';
    f.querySelector('[name=block_type]').value = d.btype;
    f.querySelector('[name=reason]').value = d.reason || '';
    f.querySelector('[name=is_active]').checked = d.active == '1';
    new bootstrap.Modal(document.getElementById('editModal')).show();
  });
});
</script>
@endsection
