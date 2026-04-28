@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.current-photo{max-width:100%;max-height:160px;object-fit:cover;border-radius:6px;border:2px solid #2471a3;margin-bottom:6px;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="b2c-page-header mb-0 d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-edit me-2"></i>Update {{ $typeLabel }}</h4>
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
          <form method="POST" action="{{ route('B2cUpdateOffer', [$type, $offer->id]) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            @if($type === 'offer')
            {{-- Hot Deals --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $offer->title) }}">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" id="descEditor" class="form-control" rows="6">{{ old('description', $offer->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ old('link', $offer->link) }}" placeholder="https://...">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Photo</label>
              @if($offer->photo)
                <div><img src="{{ asset($offer->photo) }}" class="current-photo"><div class="text-muted small">Current photo</div></div>
              @endif
              <input type="file" name="photo" class="form-control mt-2" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" {{ $offer->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$offer->is_active ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>

            @else
            {{-- AD / Banner --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Photo
                @if($type === 'ad')
                  <span class="text-muted ms-2 small">(Image must be 2220&times;280 px)</span>
                @endif
              </label>
              @if($offer->photo)
                <div><img src="{{ asset($offer->photo) }}" class="current-photo"><div class="text-muted small">Current photo</div></div>
              @endif
              <input type="file" name="photo" class="form-control mt-2" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ old('link', $offer->link) }}" placeholder="https://...">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" {{ $offer->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$offer->is_active ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
            @endif

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-warning fw-bold text-white px-4">
                <i class="fas fa-save me-1"></i>Update {{ $typeLabel }}
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
@endsection
