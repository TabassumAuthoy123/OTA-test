@extends('master')
@section('header_css')
<style>
.user-card{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:20px;}
.info-row{display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:9px 0;font-size:13px;}
.info-row:last-child{border-bottom:none;}
.info-row label{color:#888;font-weight:600;min-width:140px;}
.info-row span{font-weight:600;color:#2c3e50;text-align:right;}
.section-title{font-size:14px;font-weight:700;color:#1a5276;border-bottom:2px solid #1a5276;padding-bottom:6px;margin:20px 0 14px;}
.b2c-table th{background:#2471a3;color:#fff;font-size:12px;padding:9px 12px;white-space:nowrap;}
.b2c-table td{font-size:12px;padding:9px 12px;vertical-align:middle;}
.b2c-table tr:hover td{background:#eaf4ff;}
.badge-active{background:#d4edda;color:#155724;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.badge-inactive{background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.coin-box{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;border-radius:8px;padding:16px 20px;text-align:center;}
.coin-box .coin-val{font-size:28px;font-weight:800;}
.coin-box small{opacity:.8;font-size:12px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ url('b2c/user-list') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <h5 class="mb-0" style="color:#1a5276;font-weight:700;">B2C User Detail</h5>
    <div></div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-8">
      <div class="user-card">
        <div class="info-row"><label>Name</label><span>{{ $user->name }}</span></div>
        <div class="info-row"><label>Email</label><span>{{ $user->email }}</span></div>
        <div class="info-row"><label>Phone</label><span>{{ $user->phone ?? 'N/A' }}</span></div>
        <div class="info-row"><label>Status</label>
          <span>@if($user->status == 1 || $user->status === null)<span class="badge-active">ACTIVE</span>@else<span class="badge-inactive">INACTIVE</span>@endif</span>
        </div>
        <div class="info-row"><label>Member Since</label><span>{{ $user->created_at ? date('d-m-Y', strtotime($user->created_at)) : 'N/A' }}</span></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="coin-box">
        <div class="coin-val"><i class="fas fa-coins me-2"></i>{{ number_format($coinBalance, 2) }}</div>
        <small>Coin Balance</small>
      </div>
    </div>
  </div>

  {{-- Flight Bookings --}}
  <div class="section-title"><i class="fas fa-plane me-2"></i>Flight Bookings</div>
  <div class="table-responsive mb-4">
    <table class="table table-bordered b2c-table mb-0">
      <thead>
        <tr>
          <th>Booking Ref</th>
          <th>Created</th>
          <th>Route</th>
          <th>Departure</th>
          <th>Journey Type</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($flightBookings as $b)
        @php
          $sMap = [0=>'Pending',1=>'Booked',2=>'Issued',3=>'Cancelled'];
          $sCls = [0=>'#fff3cd',1=>'#d4edda',2=>'#cce5ff',3=>'#f8d7da'];
          $sTxt = [0=>'#856404',1=>'#155724',2=>'#004085',3=>'#721c24'];
        @endphp
        <tr>
          <td style="font-weight:700;color:#1a5276;">{{ $b->booking_no }}</td>
          <td>{{ $b->created_at ? date('d-m-Y', strtotime($b->created_at)) : 'N/A' }}</td>
          <td>{{ $b->departure_location }} &rarr; {{ $b->arrival_location }}</td>
          <td>{{ $b->departure_date ? date('d-m-Y', strtotime($b->departure_date)) : 'N/A' }}</td>
          <td>{{ \App\Http\Controllers\B2cAdminController::journeyTypeLabel($b->flight_type) }}</td>
          <td>{{ number_format($b->total_fare ?? 0, 2) }} BDT</td>
          <td><span style="background:{{ $sCls[$b->status]??'#eee' }};color:{{ $sTxt[$b->status]??'#333' }};padding:3px 8px;border-radius:10px;font-size:11px;font-weight:700;">{{ $sMap[$b->status]??'Unknown' }}</span></td>
          <td><a href="{{ url('b2c/flight-bookings/'.$b->id) }}" style="background:#f0a500;color:#fff;padding:3px 10px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;">View</a></td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-3 text-muted">No flight bookings</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Tour Bookings --}}
  <div class="section-title"><i class="fas fa-map me-2"></i>Tour Bookings</div>
  <div class="table-responsive">
    <table class="table table-bordered b2c-table mb-0">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Booking Reference</th>
          <th>Tour Type</th>
          <th>Travel Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tourBookings as $t)
        @php
          $tsMap = [0=>'Pending',1=>'Confirmed',2=>'Cancelled'];
          $tsCls = ['Pending'=>'#fff3cd','Confirmed'=>'#d4edda','Cancelled'=>'#f8d7da'];
          $tsTxt = ['Pending'=>'#856404','Confirmed'=>'#155724','Cancelled'=>'#721c24'];
          $tsLabel = $tsMap[$t->status] ?? 'Pending';
        @endphp
        <tr>
          <td>{{ $t->id }}</td>
          <td>{{ $t->booking_id ?? 'N/A' }}</td>
          <td>{{ $t->tour_type }}</td>
          <td>{{ $t->travel_date ? date('d-m-Y', strtotime($t->travel_date)) : 'N/A' }}</td>
          <td>{{ number_format($t->amount ?? 0, 2) }} BDT</td>
          <td><span style="background:{{ $tsCls[$tsLabel]??'#eee' }};color:{{ $tsTxt[$tsLabel]??'#333' }};padding:3px 8px;border-radius:10px;font-size:11px;font-weight:700;">{{ strtoupper($tsLabel) }}</span></td>
          <td><a href="{{ url('b2c/tour-bookings/'.$t->id) }}" style="background:#f0a500;color:#fff;padding:3px 10px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;">View</a></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center py-3 text-muted">No tour bookings</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div></div>
@endsection
