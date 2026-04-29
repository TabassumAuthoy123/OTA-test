@extends('master')
@section('header_css')
<style>
.detail-section{margin-bottom:24px;}
.detail-section h6{font-size:14px;font-weight:700;color:#1a5276;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #1a5276;padding-bottom:6px;margin-bottom:14px;}
.info-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-bottom:18px;}
.info-item label{font-size:11px;color:#888;font-weight:600;display:block;margin-bottom:2px;}
.info-item span{font-size:13px;font-weight:600;color:#2c3e50;}
.b2c-table th{background:#2471a3;color:#fff;font-size:12px;padding:9px 12px;white-space:nowrap;}
.b2c-table td{font-size:12px;padding:9px 12px;vertical-align:middle;}
.b2c-table tr:hover td{background:#eaf4ff;}
.status-badge{padding:4px 14px;border-radius:10px;font-size:12px;font-weight:700;display:inline-block;}
.s-booked{background:#d4edda;color:#155724;}
.s-issued{background:#cce5ff;color:#004085;}
.s-pending{background:#fff3cd;color:#856404;}
.s-cancelled{background:#f8d7da;color:#721c24;}
.btn-action{padding:6px 16px;border-radius:5px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">

  {{-- Top bar --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ url('b2c/flight-bookings') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <div class="d-flex flex-wrap gap-2">
      <a href="{{ url('b2c/flight-bookings/'.$booking->id) }}" class="btn-action" style="background:#17a2b8;color:#fff;">
        <i class="fas fa-sync-alt"></i> Refresh Booking
      </a>
      <a href="{{ route('BookingPreview', $booking->booking_no) }}" class="btn-action" style="background:#6f42c1;color:#fff;">
        <i class="fas fa-file-alt"></i> Preview Booking
      </a>
      <span class="btn-action" style="background:#6c757d;color:#fff;cursor:default;">
        <i class="fas fa-edit"></i> Booking Update
      </span>
      @if($booking->status == 1 || $booking->status == 0)
      <a href="{{ route('CancelFlightBooking', $booking->booking_no) }}" class="btn-action" style="background:#dc3545;color:#fff;"
         onclick="return confirm('Cancel this booking?')">
        <i class="fas fa-times-circle"></i> Cancel Booking
      </a>
      @endif
      @if($booking->status == 1)
      <a href="{{ route('IssueFlightTicket', $booking->booking_no) }}" class="btn-action" style="background:#f0a500;color:#fff;">
        <i class="fas fa-ticket-alt"></i> Issue Ticket
      </a>
      @endif
    </div>
  </div>

  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="card-body">

      {{-- Summary info --}}
      <div class="info-grid mb-4">
        <div class="info-item">
          <label>Status</label>
          @php
            $statusMap = [0=>'PENDING',1=>'BOOKED',2=>'ISSUED',3=>'CANCELLED',4=>'CANCELLED'];
            $statusClass = [0=>'s-pending',1=>'s-booked',2=>'s-issued',3=>'s-cancelled',4=>'s-cancelled'];
          @endphp
          <span class="status-badge {{ $statusClass[$booking->status] ?? 's-pending' }}">
            {{ $statusMap[$booking->status] ?? 'UNKNOWN' }}
          </span>
        </div>
        <div class="info-item">
          <label>Journey Type</label>
          <span>{{ \App\Http\Controllers\B2cAdminController::journeyTypeLabel($booking->flight_type) }}</span>
        </div>
        <div class="info-item">
          <label>Create Date</label>
          <span>{{ $booking->created_at ? date('d-M-Y', strtotime($booking->created_at)) : 'N/A' }}</span>
        </div>
        <div class="info-item">
          <label>Departure</label>
          <span>{{ $booking->departure_location }} &rarr; {{ $booking->arrival_location }}</span>
        </div>
        <div class="info-item">
          <label>Departure Date</label>
          <span>{{ $booking->departure_date ? date('d-M-Y', strtotime($booking->departure_date)) : 'N/A' }}</span>
        </div>
        <div class="info-item">
          <label>Last Date of Ticket Issue</label>
          <span>{{ $booking->last_ticket_datetime ? date('d-M-Y h:i A', strtotime($booking->last_ticket_datetime)) : 'Not Available' }}</span>
        </div>
        <div class="info-item">
          <label>Passenger Name</label>
          <span>{{ $booking->user_name ?? $booking->traveller_name }}</span>
        </div>
        <div class="info-item">
          <label>Contact</label>
          <span>{{ $booking->traveller_contact ?? 'N/A' }}</span>
        </div>
      </div>

      {{-- Travel Segments --}}
      <div class="detail-section">
        <h6>Travel Segments</h6>
        <div class="table-responsive">
          <table class="table table-bordered b2c-table mb-0">
            <thead>
              <tr>
                <th>Airline</th>
                <th>Flight</th>
                <th>Departs</th>
                <th>Departure Date/Time</th>
                <th>Arrives</th>
                <th>Arrival Date/Time</th>
                <th>Baggage</th>
                <th>Cabin</th>
              </tr>
            </thead>
            <tbody>
              @forelse($segments as $seg)
              <tr>
                <td>{{ $seg->carrier_marketing_code }}</td>
                <td>{{ $seg->carrier_marketing_code }} {{ $seg->carrier_marketing_flight_number }}</td>
                <td>{{ $seg->departure_airport_code }}{{ $seg->departure_city_code ? ' ('.$seg->departure_city_code.')' : '' }}</td>
                <td>{{ $seg->departure_time ? date('d-M-Y h:i A', strtotime($seg->departure_time)) : 'N/A' }}</td>
                <td>{{ $seg->arrival_airport_code }}{{ $seg->arrival_city_code ? ' ('.$seg->arrival_city_code.')' : '' }}</td>
                <td>{{ $seg->arrival_time ? date('d-M-Y h:i A', strtotime($seg->arrival_time)) : 'N/A' }}</td>
                <td>{{ $seg->baggage_allowance ?? 'N/A' }}</td>
                <td>{{ $seg->cabin_code ?? 'N/A' }}</td>
              </tr>
              @empty
              <tr><td colspan="8" class="text-center text-muted py-3">No segment data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Customer Fare Details --}}
      <div class="detail-section">
        <h6>Customer Fare Details</h6>
        <div class="table-responsive">
          <table class="table table-bordered b2c-table mb-0">
            <thead>
              <tr>
                <th>PNR Code</th>
                <th>Journey Type</th>
                <th>Status</th>
                <th>Total Passenger</th>
                <th>Base Fare</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>AIT</th>
                <th>Payable Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ $booking->pnr_id ?? 'N/A' }}</td>
                <td>{{ \App\Http\Controllers\B2cAdminController::journeyTypeLabel($booking->flight_type) }}</td>
                <td><span class="status-badge {{ $statusClass[$booking->status] ?? 's-pending' }}" style="font-size:11px;">{{ strtolower($statusMap[$booking->status] ?? 'unknown') }}</span></td>
                <td class="text-center">{{ (int)$booking->adult + (int)$booking->child + (int)$booking->infant }}</td>
                <td>{{ number_format($booking->base_fare_amount ?? 0, 2) }} BDT</td>
                <td>{{ number_format($booking->total_tax_amount ?? 0, 2) }} BDT</td>
                <td>{{ number_format($booking->markup_amount ?? 0, 2) }} BDT</td>
                <td>0.00 BDT</td>
                <td class="fw-bold">{{ number_format($booking->total_fare ?? 0, 2) }} BDT</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Travelers Info --}}
      <div class="detail-section">
        <h6>Travelers Info</h6>
        <div class="table-responsive">
          <table class="table table-bordered b2c-table mb-0">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Passport</th>
                <th>Passport Expiry</th>
                <th>Date of Birth</th>
                <th>FF Number</th>
                <th>Type</th>
                <th>Contact Number</th>
              </tr>
            </thead>
            <tbody>
              @forelse($passengers as $p)
              <tr>
                <td>{{ trim(($p->title ? $p->title.'. ' : '').$p->first_name.' '.$p->last_name) }}</td>
                <td>{{ $p->email ?? 'N/A' }}</td>
                <td>{{ $p->document_no ?? 'N/A' }}</td>
                <td>{{ $p->document_expire_date ? date('d-M-Y', strtotime($p->document_expire_date)) : 'N/A' }}</td>
                <td>{{ $p->dob ? date('d-M-Y', strtotime($p->dob)) : 'N/A' }}</td>
                <td>{{ $p->frequent_flyer_no ?? 'N/A' }}</td>
                <td>{{ strtoupper($p->passenger_type ?? 'ADT') }}</td>
                <td>{{ $p->phone ?? 'N/A' }}</td>
              </tr>
              @empty
              <tr><td colspan="8" class="text-center text-muted py-3">No passenger data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div></div>
@endsection
