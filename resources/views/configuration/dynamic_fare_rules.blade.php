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
        <h5><i class="typcn typcn-calculator me-2"></i> Dynamic Fare Rules</h5>
        <small>Configuration &rsaquo; Dynamic Fare Rules</small>
      </div>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="typcn typcn-plus"></i> Add Rule
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success m-3 py-2">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ url('configuration/dynamic-fare-rules') }}">
      <div class="cfg-filters">
        <div class="fg">
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Name / Origin / Destination / Airline..." style="width:280px;">
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
            <a href="{{ url('configuration/dynamic-fare-rules') }}" class="btn btn-secondary btn-sm" style="height:34px;">Clear</a>
          </div>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered cfg-table mb-0">
        <thead>
          <tr>
            <th>#</th><th>Name</th><th>Origin→Dest</th><th>Airline</th><th>Trip Type</th>
            <th>Markup</th><th>Fare Range</th><th>Valid</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rules as $i => $rule)
          <tr>
            <td>{{ $rules->firstItem() + $i }}</td>
            <td><strong>{{ $rule->name }}</strong>@if($rule->notes)<br><small class="text-muted">{{ Str::limit($rule->notes, 50) }}</small>@endif</td>
            <td>{{ $rule->origin ?: '*' }} → {{ $rule->destination ?: '*' }}</td>
            <td>{{ $rule->airline_code ?: 'All' }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$rule->trip_type)) }}</td>
            <td>
              @if($rule->markup_type === 'percentage')
                {{ $rule->markup_value }}%
              @else
                BDT {{ number_format($rule->markup_value,2) }}
              @endif
            </td>
            <td>
              @if($rule->min_fare || $rule->max_fare)
                {{ $rule->min_fare ? number_format($rule->min_fare,0) : '0' }} – {{ $rule->max_fare ? number_format($rule->max_fare,0) : '∞' }}
              @else
                All
              @endif
            </td>
            <td>
              @if($rule->valid_from || $rule->valid_until)
                <small>{{ $rule->valid_from ?? '—' }}<br>{{ $rule->valid_until ?? '—' }}</small>
              @else
                Always
              @endif
            </td>
            <td>
              <span class="{{ $rule->is_active ? 'badge-active' : 'badge-inactive' }}">
                {{ $rule->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-warning btn-edit-fare" style="font-size:11px;"
                data-id="{{ $rule->id }}" data-name="{{ $rule->name }}"
                data-origin="{{ $rule->origin }}" data-destination="{{ $rule->destination }}"
                data-airline="{{ $rule->airline_code }}" data-trip="{{ $rule->trip_type }}"
                data-cabin="{{ $rule->cabin_class }}" data-mtype="{{ $rule->markup_type }}"
                data-mvalue="{{ $rule->markup_value }}" data-minf="{{ $rule->min_fare }}"
                data-maxf="{{ $rule->max_fare }}" data-vfrom="{{ $rule->valid_from }}"
                data-vuntil="{{ $rule->valid_until }}" data-active="{{ $rule->is_active }}"
                data-notes="{{ $rule->notes }}">Edit</button>
              <form method="POST" action="{{ url('configuration/dynamic-fare-rules/'.$rule->id) }}" class="d-inline"
                onsubmit="return confirm('Delete this rule?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" style="font-size:11px;">Del</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" class="text-center text-muted py-4">No rules found.</td></tr>
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
        <h5 class="modal-title">Add Dynamic Fare Rule</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ url('configuration/dynamic-fare-rules') }}">
        @csrf
        <div class="modal-body">
          @include('configuration._fare_rule_fields')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Rule</button>
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
        <h5 class="modal-title">Edit Dynamic Fare Rule</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-body">
          @include('configuration._fare_rule_fields', ['edit' => true])
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Update Rule</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
<script>
document.querySelectorAll('.btn-edit-fare').forEach(btn => {
  btn.addEventListener('click', function() {
    const d = this.dataset;
    const form = document.getElementById('editForm');
    form.action = '/configuration/dynamic-fare-rules/' + d.id;
    form.querySelector('[name=name]').value = d.name;
    form.querySelector('[name=origin]').value = d.origin || '';
    form.querySelector('[name=destination]').value = d.destination || '';
    form.querySelector('[name=airline_code]').value = d.airline || '';
    form.querySelector('[name=trip_type]').value = d.trip;
    form.querySelector('[name=cabin_class]').value = d.cabin || '';
    form.querySelector('[name=markup_type]').value = d.mtype;
    form.querySelector('[name=markup_value]').value = d.mvalue;
    form.querySelector('[name=min_fare]').value = d.minf || '';
    form.querySelector('[name=max_fare]').value = d.maxf || '';
    form.querySelector('[name=valid_from]').value = d.vfrom || '';
    form.querySelector('[name=valid_until]').value = d.vuntil || '';
    form.querySelector('[name=is_active]').checked = d.active == '1';
    form.querySelector('[name=notes]').value = d.notes || '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
  });
});
</script>
@endsection
