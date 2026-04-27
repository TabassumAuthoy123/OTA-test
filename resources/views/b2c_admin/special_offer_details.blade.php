@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.detail-label{color:#7f8c8d;font-size:.82rem;font-weight:600;text-transform:uppercase;margin-bottom:3px;}
.detail-value{font-size:.95rem;color:#2c3e50;}
.badge-active{background:#27ae60;color:#fff;padding:4px 12px;border-radius:12px;}
.badge-inactive{background:#e74c3c;color:#fff;padding:4px 12px;border-radius:12px;}
.offer-photo{max-width:340px;max-height:220px;object-fit:cover;border-radius:8px;border:2px solid #2471a3;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="b2c-page-header d-flex align-items-center justify-content-between">
        <h4><i class="fas fa-eye me-2"></i>Offer Details</h4>
        <div>
          <a href="{{ route('B2cEditOffer', [$offer->type, $offer->id]) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i>Edit
          </a>
          <a href="{{ route('B2cSpecialOfferList', $offer->type) }}" class="btn btn-secondary btn-sm ms-1">Back</a>
        </div>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          @if($offer->photo)
          <div class="mb-4 text-center">
            <img src="{{ asset($offer->photo) }}" class="offer-photo">
          </div>
          @endif
          <div class="row g-3">
            <div class="col-md-6">
              <div class="detail-label">Title</div>
              <div class="detail-value">{{ $offer->title ?? '—' }}</div>
            </div>
            <div class="col-md-6">
              <div class="detail-label">Type</div>
              <div class="detail-value text-capitalize">{{ str_replace('_', ' ', $offer->type) }}</div>
            </div>
            <div class="col-md-6">
              <div class="detail-label">Status</div>
              <div><span class="{{ $offer->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $offer->is_active ? 'Active' : 'Inactive' }}</span></div>
            </div>
            <div class="col-md-6">
              <div class="detail-label">Link</div>
              <div class="detail-value">
                @if($offer->link)
                  <a href="{{ $offer->link }}" target="_blank">{{ $offer->link }}</a>
                @else —
                @endif
              </div>
            </div>
            <div class="col-12">
              <div class="detail-label">Description</div>
              <div class="detail-value">{{ $offer->description ?? '—' }}</div>
            </div>
            <div class="col-md-6">
              <div class="detail-label">Created At</div>
              <div class="detail-value">{{ \Carbon\Carbon::parse($offer->created_at)->format('d M Y H:i') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
