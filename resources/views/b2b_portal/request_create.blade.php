@extends('master')
@section('header_css')
<style>
.b2b-page-header{background:linear-gradient(135deg,#0f1f3d,#1a3a6e);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;}
.b2b-page-header h5{margin:0;font-size:18px;font-weight:700;}
.form-label{font-size:13px;font-weight:600;color:#444;}
.form-control{font-size:13px;}
.btn-submit{background:linear-gradient(135deg,#0f1f3d,#1565a0);color:#fff;border:none;padding:10px 28px;border-radius:6px;font-weight:700;font-size:14px;}
.btn-submit:hover{opacity:.88;color:#fff;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-8 col-xl-6">
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2b-page-header">
      <h5>
        @if($type=='reissue')<i class="fas fa-redo me-2"></i>
        @elseif($type=='refund')<i class="fas fa-hand-holding-usd me-2"></i>
        @else<i class="fas fa-ban me-2"></i>@endif
        New {{ $typeLabel }} Request
      </h5>
      <small>Dashboard &rsaquo; {{ $typeLabel }} &rsaquo; New Request</small>
    </div>

    <div class="card-body" style="padding:24px;">
      <form method="POST" action="{{ route('MyStore'.ucfirst($type)) }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Booking Reference <small class="text-muted">(optional)</small></label>
          <input type="text" name="booking_ref" class="form-control @error('booking_ref') is-invalid @enderror"
                 placeholder="e.g. BK2024001" value="{{ old('booking_ref') }}">
          @error('booking_ref')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Subject <span class="text-danger">*</span></label>
          <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                 placeholder="Brief subject for your {{ strtolower($typeLabel) }} request"
                 value="{{ old('subject') }}" required>
          @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
          <label class="form-label">Description <span class="text-danger">*</span></label>
          <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                    placeholder="Explain your {{ strtolower($typeLabel) }} request in detail..." required>{{ old('description') }}</textarea>
          @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn-submit">
            <i class="fas fa-paper-plane me-2"></i>Submit Request
          </button>
          <a href="{{ route($backRoute) }}" class="btn btn-secondary" style="font-size:14px;">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div></div>
@endsection
