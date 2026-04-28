@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.preview-img{max-width:100%;max-height:160px;object-fit:cover;border-radius:6px;border:2px solid #2471a3;margin-top:8px;}
.size-hint{font-size:.78rem;color:#888;margin-top:4px;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="b2c-page-header mb-0 d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-plus-circle me-2"></i>Create {{ $typeLabel }}</h4>
        <a href="{{ route('B2cSpecialOfferList', $type) }}" class="btn btn-light btn-sm">Back to List</a>
      </div>
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      @if($errors->any())
        <div class="alert alert-danger mt-2">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif
      <div class="card form-card">
        <div class="card-body p-4">
          <form method="POST" action="{{ route('B2cStoreOffer', $type) }}" enctype="multipart/form-data">
            @csrf

            @if($type === 'offer')
            {{-- Hot Deals: Title + Jodit description + link + photo + status --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Deal title">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" id="descEditor" class="form-control" rows="6">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Photo</label>
              <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this)">
              <div id="imgPreview"></div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

            @else
            {{-- AD / Banner: Photo + Link only --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Photo
                @if($type === 'ad')
                  <span class="text-muted ms-2 small">(Image must be 2220&times;280 px)</span>
                @endif
              </label>
              <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this)">
              <div id="imgPreview"></div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            @endif

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-warning fw-bold text-white px-4">
                <i class="fas fa-save me-1"></i>Save {{ $typeLabel }}
              </button>
              <a href="{{ route('B2cSpecialOfferList', $type) }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
@if($type === 'offer')
<script src="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.css">
<script>Jodit.make('#descEditor', {height: 300, toolbarButtonSize: 'small'});</script>
@endif
<script>
function previewImg(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('imgPreview').innerHTML = '<img src="' + e.target.result + '" class="preview-img">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection
