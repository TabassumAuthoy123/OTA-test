@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;margin-bottom:0;}
.b2c-page-header h4{margin:0;font-weight:700;font-size:1.2rem;}
.table thead th{background:#2471a3;color:#fff;border:none;font-size:.82rem;padding:10px 12px;}
.table tbody tr:hover{background:#eaf4fb;}
.btn-add-new{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;border:none;border-radius:6px;padding:8px 18px;font-size:.85rem;font-weight:600;cursor:pointer;}
.btn-add-new:hover{background:#1a5276;color:#fff;}
.logo-thumb{width:40px;height:40px;object-fit:cover;border-radius:50%;border:2px solid #2471a3;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="b2c-page-header d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-film me-2"></i>Film Watch Links</h4>
        <button class="btn btn-add-new" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i>Add Film Watch Link</button>
      </div>
      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      <div class="card shadow-sm border-0">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Logo</th>
                  <th>Name</th>
                  <th>Link</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($items as $i => $item)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>
                    @if($item->logo)
                      <img src="{{ asset($item->logo) }}" class="logo-thumb" alt="{{ $item->name }}">
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td>{{ $item->name }}</td>
                  <td><a href="{{ $item->link }}" target="_blank" class="text-primary">{{ Str::limit($item->link, 50) }}</a></td>
                  <td>
                    <button class="btn btn-sm btn-warning" onclick="openEdit({{ $item->id }},'{{ addslashes($item->name) }}','{{ addslashes($item->link) }}','{{ $item->logo }}')">
                      <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="{{ route('B2cDeleteFilm', $item->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No film watch links found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#2471a3;color:#fff;">
        <h5 class="modal-title">Add Film Watch Link</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('B2cStoreFilm') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Link</label>
            <input type="url" name="link" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Logo (optional)</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-add-new">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#1a5276;color:#fff;">
        <h5 class="modal-title">Edit Film Watch Link</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" id="editName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Link</label>
            <input type="url" name="link" id="editLink" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Current Logo</label>
            <div id="currentLogo" class="mb-2"></div>
            <label class="form-label fw-semibold">Change Logo (optional)</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background:#1a5276;color:#fff;">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
<script>
function openEdit(id, name, link, logo) {
  document.getElementById('editName').value = name;
  document.getElementById('editLink').value = link;
  document.getElementById('editForm').action = '{{ url("b2c/config/film-watch") }}/' + id;
  var logoDiv = document.getElementById('currentLogo');
  logoDiv.innerHTML = logo ? '<img src="/' + logo + '" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">' : '<span class="text-muted small">No logo</span>';
  new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endsection
