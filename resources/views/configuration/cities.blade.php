@extends('master')
@section('header_css')
<style>
.cfg-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.cfg-header h5{margin:0;font-size:18px;font-weight:700;}
.cfg-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.cfg-filters .fg{display:flex;flex-direction:column;gap:4px;}
.cfg-filters label{font-size:11px;font-weight:600;color:#555;margin:0;}
.cfg-filters input{font-size:13px;padding:5px 10px;border:1px solid #ced4da;border-radius:5px;height:34px;}
.cfg-table th{background:#1a5276;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.cfg-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.cfg-table tr:hover td{background:#eaf4ff;}
.iata-badge{background:#e3f2fd;color:#0d47a1;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;font-family:monospace;}
.ap-count{background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:10px;font-size:11px;font-weight:600;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="cfg-header">
      <div>
        <h5><i class="typcn typcn-location-outline me-2"></i> Cities</h5>
        <small>Configuration &rsaquo; City &nbsp;|&nbsp; Total Cities: {{ $cities->total() }}</small>
      </div>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="typcn typcn-plus"></i> Add City / Airport
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success m-3 py-2">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ url('configuration/cities') }}">
      <div class="cfg-filters">
        <div class="fg">
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="City / Code / Country..." style="width:260px;">
        </div>
        <div class="fg"><label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
        </div>
        @if(request('search'))
          <div class="fg"><label>&nbsp;</label>
            <a href="{{ url('configuration/cities') }}" class="btn btn-secondary btn-sm" style="height:34px;">Clear</a>
          </div>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered cfg-table mb-0">
        <thead>
          <tr>
            <th>#</th><th>City Name</th><th>City Code</th><th>Country</th>
            <th>Country Code</th><th>Airports</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cities as $i => $city)
          <tr>
            <td>{{ $cities->firstItem() + $i }}</td>
            <td><strong>{{ $city->city_name }}</strong></td>
            <td><span class="iata-badge">{{ $city->city_code }}</span></td>
            <td>{{ $city->country_name ?: '—' }}</td>
            <td>{{ $city->country_code ?: '—' }}</td>
            <td><span class="ap-count">{{ $city->airport_count }} airport{{ $city->airport_count != 1 ? 's' : '' }}</span></td>
            <td>
              <a href="{{ url('configuration/airports') }}?search={{ urlencode($city->city_name) }}" class="btn btn-sm btn-info" style="font-size:11px;">View Airports</a>
              <button class="btn btn-sm btn-danger btn-del-city" style="font-size:11px;"
                data-name="{{ $city->city_name }}">Del City</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center text-muted py-4">No cities found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $cities->links() }}</div>
  </div>
</div></div>

{{-- Add City / Airport Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Add City / Airport</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ url('configuration/cities') }}">
        @csrf
        <div class="modal-body">
          <p class="text-muted small mb-3">Cities are grouped from the Airports table. Adding an entry here creates a new airport record under that city.</p>
          @include('configuration._airport_fields')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Delete City Form (hidden) --}}
<form method="POST" id="delCityForm" action="{{ url('configuration/cities') }}">
  @csrf @method('DELETE')
  <input type="hidden" name="city_name" id="delCityName">
</form>
@endsection
@section('footer_js')
<script>
document.querySelectorAll('.btn-del-city').forEach(btn => {
  btn.addEventListener('click', function() {
    const name = this.dataset.name;
    if(confirm('Delete city "' + name + '" and ALL its airports? This cannot be undone.')) {
      document.getElementById('delCityName').value = name;
      document.getElementById('delCityForm').submit();
    }
  });
});
</script>
@endsection
