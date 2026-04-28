@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2c-filters{background:#f8f9fa;padding:14px 16px;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
.b2c-filters label{font-size:11px;font-weight:600;color:#555;margin:0;}
.b2c-table th{background:#1a5276;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.b2c-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2c-table tr:hover td{background:#eaf4ff;}
.btn-add{background:#f0a500;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:700;text-decoration:none;cursor:pointer;}
.btn-edit{background:#f0a500;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:12px;font-weight:600;cursor:pointer;}
.btn-del{background:#dc3545;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:12px;font-weight:600;cursor:pointer;}
.badge-section{background:#e2e8f0;color:#333;padding:3px 8px;border-radius:10px;font-size:11px;}
.badge-media{background:#bee3f8;color:#2c7a7b;padding:3px 8px;border-radius:10px;font-size:11px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <div>
        <h5>Gallery List</h5>
        <small>Dashboard &rsaquo; Configuration &rsaquo; Gallery</small>
      </div>
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Add New Media</button>
    </div>
    <div class="b2c-filters">
      <form method="GET" action="{{ route('B2cGallery') }}" class="d-flex flex-wrap gap-2 align-items-end w-100">
        <div>
          <label>Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="form-control form-control-sm" style="width:200px;">
        </div>
        <div>
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary btn-sm d-block"><i class="fas fa-search me-1"></i>Search</button>
        </div>
        @if(request('search'))
        <div>
          <label>&nbsp;</label>
          <a href="{{ route('B2cGallery') }}" class="btn btn-secondary btn-sm d-block">Clear</a>
        </div>
        @endif
      </form>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2c-table">
          <thead><tr><th>SL</th><th>Title</th><th>Section</th><th>Media</th><th>Duration</th><th>Size (MB)</th><th>Created At</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($items as $i => $item)
            <tr>
              <td>{{ $items->firstItem() + $i }}</td>
              <td>{{ $item->title }}</td>
              <td><span class="badge-section">{{ $item->section_type }}</span></td>
              <td><span class="badge-media">{{ $item->media_type }}</span></td>
              <td>{{ $item->duration ?? '-' }}</td>
              <td>{{ $item->file_size_mb }}</td>
              <td>{{ $item->created_at ? date('d/m/Y g:i A', strtotime($item->created_at)) : 'N/A' }}</td>
              <td>
                <button class="btn-edit" onclick="openEditModal({{ $item->id }},'{{ addslashes($item->title) }}','{{ addslashes($item->description??'') }}','{{ $item->section_type }}')">
                  <i class="fas fa-edit me-1"></i>Edit
                </button>
                <form method="POST" action="{{ route('B2cDeleteGallery', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete this media?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-del"><i class="fas fa-trash me-1"></i>Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5 text-muted">No media items.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <small class="text-muted">Showing {{ $items->firstItem()??0 }}&ndash;{{ $items->lastItem()??0 }} of {{ $items->total() }} entries</small>
        {{ $items->links() }}
      </div>
    </div>
  </div>
</div></div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('B2cStoreGallery') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header" style="background:#2471a3;color:#fff;">
          <h5 class="modal-title">Add New Media</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label fw-bold text-danger">* Title</label><input type="text" name="title" class="form-control" placeholder="Media title" required></div>
          <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea></div>
          <div class="mb-3">
            <label class="form-label fw-bold text-danger">* Section Type</label>
            <select name="section_type" class="form-select" required>
              <option value="">Select section...</option>
              <option value="short">Short</option>
              <option value="certificates">Certificates</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label fw-bold">File (Video/Image)</label><input type="file" name="file" class="form-control"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white fw-bold">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editForm" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="PUT">
      <div class="modal-content">
        <div class="modal-header" style="background:#1a5276;color:#fff;">
          <h5 class="modal-title">Edit Media</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label fw-bold text-danger">* Title</label><input type="text" name="title" id="edit_title" class="form-control" required></div>
          <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description" id="edit_description" class="form-control" rows="3"></textarea></div>
          <div class="mb-3">
            <label class="form-label fw-bold text-danger">* Section Type</label>
            <select name="section_type" id="edit_section" class="form-select">
              <option value="short">Short</option>
              <option value="certificates">Certificates</option>
            </select>
          </div>
          <div class="mb-3"><label class="form-label fw-bold">File (leave blank to keep existing)</label><input type="file" name="file" class="form-control"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white fw-bold">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('footer_js')
<script>
function openEditModal(id, title, desc, section) {
  document.getElementById('editForm').action = '{{ url("b2c/config/gallery") }}/' + id;
  document.getElementById('edit_title').value = title;
  document.getElementById('edit_description').value = desc;
  document.getElementById('edit_section').value = section;
  new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endsection
