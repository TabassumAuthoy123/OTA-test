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
.iata-badge{background:#e3f2fd;color:#0d47a1;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;font-family:monospace;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="cfg-header">
      <div>
        <h5><i class="typcn typcn-plane-outline me-2"></i> Airports</h5>
        <small>Configuration &rsaquo; Airports &nbsp;|&nbsp; Total: {{ $airports->total() }}</small>
      </div>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="typcn typcn-plus"></i> Add Airport
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success m-3 py-2">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ url('configuration/airports') }}">
      <div class="cfg-filters">
        <div class="fg">
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Name / IATA / City / Country..." style="width:300px;">
        </div>
        <div class="fg"><label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm" style="height:34px;">Search</button>
        </div>
        @if(request('search'))
          <div class="fg"><label>&nbsp;</label>
            <a href="{{ url('configuration/airports') }}" class="btn btn-secondary btn-sm" style="height:34px;">Clear</a>
          </div>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered cfg-table mb-0">
        <thead>
          <tr>
            <th>#</th><th>IATA</th><th>Airport Name</th><th>City</th><th>City Code</th>
            <th>Country</th><th>Country Code</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($airports as $i => $ap)
          <tr>
            <td>{{ $airports->firstItem() + $i }}</td>
            <td><span class="iata-badge">{{ $ap->airport_code }}</span></td>
            <td><strong>{{ $ap->airport_name }}</strong></td>
            <td>{{ $ap->city_name }}</td>
            <td><span class="iata-badge">{{ $ap->city_code }}</span></td>
            <td>{{ $ap->country_name ?: '—' }}</td>
            <td>{{ $ap->country_code ?: '—' }}</td>
            <td>
              <button class="btn btn-sm btn-warning btn-edit-ap" style="font-size:11px;"
                data-id="{{ $ap->id }}" data-aname="{{ $ap->airport_name }}"
                data-acode="{{ $ap->airport_code }}" data-cname="{{ $ap->city_name }}"
                data-ccode="{{ $ap->city_code }}" data-country="{{ $ap->country_name }}"
                data-cntcode="{{ $ap->country_code }}">Edit</button>
              <form method="POST" action="{{ url('configuration/airports/'.$ap->id) }}" class="d-inline"
                onsubmit="return confirm('Delete airport?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" style="font-size:11px;">Del</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No airports found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $airports->links() }}</div>
  </div>
</div></div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Add Airport</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ url('configuration/airports') }}">
        @csrf
        <div class="modal-body">
          @include('configuration._airport_fields')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Airport</button>
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
        <h5 class="modal-title">Edit Airport</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-body">
          @include('configuration._airport_fields', ['edit' => true])
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
document.querySelectorAll('.btn-edit-ap').forEach(btn => {
  btn.addEventListener('click', function() {
    const d = this.dataset;
    const f = document.getElementById('editForm');
    f.action = '/configuration/airports/' + d.id;
    f.querySelector('[name=airport_name]').value = d.aname;
    f.querySelector('[name=airport_code]').value = d.acode;
    f.querySelector('[name=city_name]').value = d.cname;
    f.querySelector('[name=city_code]').value = d.ccode;
    f.querySelector('[name=country_name]').value = d.country || '';
    f.querySelector('[name=country_code]').value = d.cntcode || '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
  });
});
</script>
@endsection
