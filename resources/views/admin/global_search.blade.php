@extends('master')
@section('header_css')
<style>
.gs-header{background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.gs-header h5{margin:0;font-size:18px;font-weight:700;}
.gs-tbl th{background:#1e3a5f;color:#fff;font-size:12px;padding:10px 12px;white-space:nowrap;}
.gs-tbl td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.gs-tbl tr:hover td{background:#f4f7ff;}
.gs-ref{color:#0d6efd;font-weight:700;text-decoration:none;}
.gs-ref:hover{text-decoration:underline;}
.st-badge{display:inline-block;padding:3px 9px;border-radius:10px;font-size:11px;font-weight:700;}
.st-0{background:#fff3cd;color:#856404;}
.st-1{background:#d4edda;color:#155724;}
.st-2{background:#cce5ff;color:#004085;}
.st-3{background:#f8d7da;color:#721c24;}
.st-4{background:#f8d7da;color:#721c24;}
.st-5{background:#e2e3e5;color:#383d41;}
.gs-empty{text-align:center;padding:60px 20px;color:#aaa;}
.gs-empty i{font-size:48px;display:block;margin-bottom:16px;opacity:.3;}
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card" style="border-radius:8px;overflow:hidden;">
      <div class="gs-header">
        <div>
          <h5><i class="fas fa-search me-2"></i>
            Global Search
            @if($q) <span style="font-weight:400;font-size:14px;">— "{{ $q }}"</span> @endif
          </h5>
          <small>{{ $total }} result(s) across all bookings</small>
        </div>
        {{-- Inline search so user can refine --}}
        <form method="GET" action="{{ route('AdminGlobalSearch') }}" style="display:flex;gap:8px;">
          <input type="text" name="q" value="{{ $q }}" placeholder="PNR / Ticket NO / Booking Ref / Name / Agent ID / Passport"
            class="form-control form-control-sm" style="width:380px;" autofocus>
          <button class="btn btn-warning btn-sm fw-bold">Search</button>
        </form>
      </div>

      @if($q && $total === 0)
      <div class="gs-empty">
        <i class="fas fa-search"></i>
        No bookings found for <strong>"{{ $q }}"</strong>.<br>
        <small style="font-size:13px;">Try PNR, Booking Ref, Agent Name, Passport No, or Agent ID (e.g. B2B-004)</small>
      </div>

      @elseif(!$q)
      <div class="gs-empty">
        <i class="fas fa-search"></i>
        Enter a search term above.
      </div>

      @else
      <div class="table-responsive">
        <table class="table table-bordered mb-0 gs-tbl">
          <thead>
            <tr>
              <th>#</th>
              <th>Booking Ref</th>
              <th>GDS PNR</th>
              <th>Traveler Name</th>
              <th>Agent</th>
              <th>Route</th>
              <th>Dep. Date</th>
              <th>Amount (৳)</th>
              <th>Status</th>
              <th>Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($results as $i => $r)
            @php
              $status = (int)$r->status;
              $stMap  = [0=>'Booking-Hold',1=>'Booking-Success',2=>'Ticket-Issued',3=>'Cancelled',4=>'Refunded',5=>'Voided'];
              $stText = $stMap[$status] ?? strtoupper($r->status ?? '—');
              $jt     = (int)$r->flight_type;
              $jtText = $jt===2 ? 'RT' : ($jt===3 ? 'MC' : 'OW');
            @endphp
            <tr>
              <td style="color:#aaa;">{{ $i+1 }}</td>
              <td>
                <a href="{{ route('FlightBookingDetails', $r->booking_no) }}" class="gs-ref">
                  {{ $r->booking_no }}
                </a>
                <div style="font-size:11px;color:#999;">{{ $jtText }}</div>
              </td>
              <td style="font-size:12px;">
                {{ $r->pnr_id ?: ($r->airlines_pnr ?: '—') }}
              </td>
              <td>
                <div style="font-weight:600;">{{ $r->traveller_name ?? '—' }}</div>
                <div style="font-size:11px;color:#888;">{{ $r->traveller_contact ?? '' }}</div>
              </td>
              <td>
                @if($r->agent_id)
                  <div style="font-weight:600;">{{ $r->agent_name }}</div>
                  <div style="font-size:11px;color:#888;">B2B-{{ str_pad($r->agent_id,3,'0',STR_PAD_LEFT) }}</div>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td style="font-weight:700;letter-spacing:1px;">
                {{ strtoupper($r->departure_location ?? '') }}-{{ strtoupper($r->arrival_location ?? '') }}
              </td>
              <td>{{ $r->departure_date ? date('d-m-Y', strtotime($r->departure_date)) : '—' }}</td>
              <td style="font-weight:700;">{{ number_format($r->total_fare, 2) }}</td>
              <td><span class="st-badge st-{{ $status }}">{{ $stText }}</span></td>
              <td style="font-size:12px;color:#888;">{{ date('d-m-Y', strtotime($r->created_at)) }}</td>
              <td>
                <a href="{{ route('FlightBookingDetails', $r->booking_no) }}"
                   class="btn btn-sm btn-primary" title="View Details">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($total >= 100)
        <div style="padding:10px 16px;background:#fff8e1;font-size:13px;color:#856404;">
          <i class="fas fa-info-circle me-1"></i> Showing first 100 results. Refine your search for more precise results.
        </div>
      @endif
      @endif

    </div>
  </div>
</div>
@endsection
