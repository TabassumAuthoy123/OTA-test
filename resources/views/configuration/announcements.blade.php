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
.ann-info{background:#cce5ff;color:#004085;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;}
.ann-warning{background:#fff3cd;color:#856404;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;}
.ann-success{background:#d4edda;color:#155724;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;}
.ann-danger{background:#f8d7da;color:#721c24;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="cfg-header">
      <div>
        <h5><i class="typcn typcn-bell me-2"></i> Announcements</h5>
        <small>Configuration &rsaquo; Announcement</small>
      </div>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="typcn typcn-plus"></i> Add Announcement
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success m-3 py-2">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ url('configuration/announcements') }}">
      <div class="cfg-filters">
        <div class="fg">
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Title / Message..." style="width:260px;">
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
            <a href="{{ url('configuration/announcements') }}" class="btn btn-secondary btn-sm" style="height:34px;">Clear</a>
          </div>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered cfg-table mb-0">
        <thead>
          <tr>
            <th>#</th><th>Title</th><th>Message</th><th>Type</th><th>Target</th>
            <th>Show Period</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $i => $item)
          <tr>
            <td>{{ $items->firstItem() + $i }}</td>
            <td><strong>{{ $item->title }}</strong></td>
            <td>{{ Str::limit($item->message, 80) }}</td>
            <td><span class="ann-{{ $item->type }}">{{ ucfirst($item->type) }}</span></td>
            <td>{{ ucfirst($item->target) }}</td>
            <td>
              @if($item->show_from || $item->show_until)
                <small>{{ $item->show_from ? date('d M Y', strtotime($item->show_from)) : 'Always' }}<br>
                → {{ $item->show_until ? date('d M Y', strtotime($item->show_until)) : 'No end' }}</small>
              @else
                Always
              @endif
            </td>
            <td>
              <span class="{{ $item->is_active ? 'badge-active' : 'badge-inactive' }}" style="cursor:pointer;"
                onclick="toggleAnn({{ $item->id }}, this)">
                {{ $item->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-warning btn-edit-ann" style="font-size:11px;"
                data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                data-message="{{ $item->message }}" data-type="{{ $item->type }}"
                data-target="{{ $item->target }}" data-active="{{ $item->is_active }}"
                data-from="{{ $item->show_from }}" data-until="{{ $item->show_until }}">Edit</button>
              <form method="POST" action="{{ url('configuration/announcements/'.$item->id) }}" class="d-inline"
                onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" style="font-size:11px;">Del</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No announcements found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $items->links() }}</div>
  </div>
</div></div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Add Announcement</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ url('configuration/announcements') }}">
        @csrf
        <div class="modal-body">
          @include('configuration._announcement_fields')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
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
        <h5 class="modal-title">Edit Announcement</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        @csrf @method('PUT')
        <div class="modal-body">
          @include('configuration._announcement_fields', ['edit' => true])
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
document.querySelectorAll('.btn-edit-ann').forEach(btn => {
  btn.addEventListener('click', function() {
    const d = this.dataset;
    const f = document.getElementById('editForm');
    f.action = '/configuration/announcements/' + d.id;
    f.querySelector('[name=title]').value = d.title;
    f.querySelector('[name=message]').value = d.message;
    f.querySelector('[name=type]').value = d.type;
    f.querySelector('[name=target]').value = d.target;
    f.querySelector('[name=is_active]').checked = d.active == '1';
    f.querySelector('[name=show_from]').value = d.from ? d.from.substring(0,16) : '';
    f.querySelector('[name=show_until]').value = d.until ? d.until.substring(0,16) : '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
  });
});

function toggleAnn(id, el) {
  fetch('/configuration/announcements/' + id + '/toggle', {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json'}
  }).then(r => r.json()).then(() => location.reload());
}
</script>
@endsection
