@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.current-photo{max-width:200px;max-height:140px;object-fit:cover;border-radius:6px;border:2px solid #2471a3;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="b2c-page-header mb-0">
        <h4><i class="fas fa-edit me-2"></i>Update {{ $typeLabel }}</h4>
      </div>
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      <div class="card form-card">
        <div class="card-body p-4">
          <form method="POST" action="{{ route('B2cUpdateOffer', [$type, $offer->id]) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
              <label class="form-label fw-semibold">Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $offer->title) }}">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="4">{{ old('description', $offer->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ old('link', $offer->link) }}">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Photo</label>
              @if($offer->photo)
                <div class="mb-2">
                  <img src="{{ asset($offer->photo) }}" class="current-photo">
                  <div class="text-muted small mt-1">Current photo</div>
                </div>
              @endif
              <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" {{ $offer->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$offer->is_active ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn" style="background:#1a5276;color:#fff;">Update</button>
              <a href="{{ route('B2cSpecialOfferList', $type) }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
