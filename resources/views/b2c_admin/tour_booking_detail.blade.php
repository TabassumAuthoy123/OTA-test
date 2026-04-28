@extends('master')
@section('content')
<div class="row"><div class="col-lg-12">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ url('b2c/tour-bookings') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <h5 class="mb-0" style="color:#1a5276;font-weight:700;">Tour Booking Detail</h5>
    <div></div>
  </div>

  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;">
      <h5 class="mb-0" style="font-size:17px;font-weight:700;">
        <i class="fas fa-map me-2"></i>
        {{ $booking->booking_id ?? ('TUR-'.$booking->id) }}
      </h5>
      <small style="opacity:.8;">Dashboard &rsaquo; B2C &rsaquo; Tour Bookings &rsaquo; Detail</small>
    </div>
    <div class="card-body">

      @php
        $statusMap = [0=>'PENDING',1=>'CONFIRMED',2=>'CANCELLED'];
        $statusColor = [0=>'#fff3cd',1=>'#d4edda',2=>'#f8d7da'];
        $statusText  = [0=>'#856404',1=>'#155724',2=>'#721c24'];
      @endphp

      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;margin-bottom:20px;">
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Booking ID</div>
          <div style="font-size:14px;font-weight:700;color:#1a5276;">{{ $booking->id }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Booking Reference</div>
          <div style="font-size:14px;font-weight:700;">{{ $booking->booking_id ?? 'N/A' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Status</div>
          <span style="background:{{ $statusColor[$booking->status]??'#eee' }};color:{{ $statusText[$booking->status]??'#333' }};padding:4px 14px;border-radius:10px;font-size:12px;font-weight:700;display:inline-block;">
            {{ $statusMap[$booking->status] ?? 'PENDING' }}
          </span>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Tour Type</div>
          <div style="font-size:14px;font-weight:600;">{{ ucfirst($booking->tour_type) }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Travel Date</div>
          <div style="font-size:14px;font-weight:600;">{{ $booking->travel_date ? date('d-m-Y', strtotime($booking->travel_date)) : 'N/A' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Amount</div>
          <div style="font-size:14px;font-weight:700;color:#1a5276;">{{ number_format($booking->amount ?? 0, 2) }} BDT</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Created At</div>
          <div style="font-size:13px;">{{ $booking->created_at ? date('d-m-Y h:i A', strtotime($booking->created_at)) : 'N/A' }}</div>
        </div>
      </div>

      <hr>
      <h6 style="font-size:13px;font-weight:700;color:#1a5276;margin-bottom:14px;">Customer Info</h6>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Name</div>
          <div style="font-size:13px;font-weight:600;">{{ $booking->name }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Email</div>
          <div style="font-size:13px;">{{ $booking->email }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;font-weight:600;">Phone</div>
          <div style="font-size:13px;">{{ $booking->phone ?? 'N/A' }}</div>
        </div>
      </div>

    </div>
  </div>

</div></div>
@endsection
