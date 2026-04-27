@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.table thead th{background:#2471a3;color:#fff;border:none;font-size:.82rem;padding:10px 12px;}
.table tbody tr:hover{background:#eaf4fb;}
.banner-thumb{width:100px;height:55px;object-fit:cover;border-radius:4px;border:2px solid #2471a3;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="b2c-page-header d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-image me-2"></i>Banner List</h4>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#addBannerModal">
          <i class="fas fa-plus me-1"></i>Add Banner
        </button>
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
                  <th>Photo</th>
                  <th>Title</th>
                  <th>Link</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($banners as $i => $banner)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>
                    @if($banner->photo)
                      <img src="{{ asset($banner->photo) }}" class="banner-thumb">
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td>{{ $banner->title ?? '—' }}</td>
                  <td>{{ $banner->link ? Str::limit($banner->link, 40) : '—' }}</td>
                  <td>{{ \Carbon\Carbon::parse($banner->created_at)->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('B2cEditBanner', $banner->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('B2cDeleteBanner', $banner->id) }}" style="display:inline" onsubmit="return confirm('Delete banner?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No banners yet. Click "Add Banner" to create one.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#2471a3;color:#fff;">
        <h5 class="modal-title">Add Banner</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('B2cStoreBanner') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Link (URL)</label>
            <input type="url" name="link" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn" style="background:#2471a3;color:#fff;">Save Banner</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
