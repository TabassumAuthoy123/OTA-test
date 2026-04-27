@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.current-banner{max-width:100%;max-height:200px;object-fit:cover;border-radius:6px;border:2px solid #2471a3;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="b2c-page-header mb-0 d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-edit me-2"></i>Update Banner</h4>
        <a href="{{ route('B2cBannerList') }}" class="btn btn-light btn-sm">Back to List</a>
      </div>
      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      <div class="card form-card">
        <div class="card-body p-4">
          <form method="POST" action="{{ route('B2cUpdateBanner', $offer->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
              <label class="form-label fw-semibold">Title</label>
              <input type="text" name="title" class="form-control" value="{{ $offer->title }}">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Link (URL)</label>
              <input type="url" name="link" class="form-control" value="{{ $offer->link }}">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Photo</label>
              @if($offer->photo)
                <div class="mb-2">
                  <img src="{{ asset($offer->photo) }}" class="current-banner">
                  <div class="text-muted small mt-1">Current banner image</div>
                </div>
              @endif
              <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn" style="background:#1a5276;color:#fff;">Update Banner</button>
              <a href="{{ route('B2cBannerList') }}" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
