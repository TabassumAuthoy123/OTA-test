@extends('master')
@section('header_css')
<style>
.b2b-detail-header{display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid #e9ecef;}
.b2b-back-btn{background:#0f1f3d;color:#fff;padding:6px 14px;border-radius:5px;text-decoration:none;font-size:13px;display:inline-flex;align-items:center;gap:6px;}
.b2b-back-btn:hover{background:#1a3a6b;color:#fff;text-decoration:none;}
.b2b-detail-title{font-size:16px;font-weight:700;color:#0f1f3d;margin:0;}
.b2b-section{margin-bottom:0;}
.b2b-section-title{font-size:13px;font-weight:700;color:#333;padding:10px 20px;border-bottom:1px solid #e9ecef;margin:0;}
.b2b-detail-tbl{width:100%;border-collapse:collapse;font-size:13px;}
.b2b-detail-tbl thead th{background:#e8edf5;color:#0f1f3d;padding:9px 14px;font-weight:700;font-size:12px;border-bottom:2px solid #d0d9e8;white-space:nowrap;text-align:center;}
.b2b-detail-tbl tbody td{padding:10px 14px;border-bottom:1px solid #f0f0f0;vertical-align:middle;color:#333;text-align:center;}
.b2b-detail-tbl tbody tr:hover td{background:#f9f9ff;}
.airline-badge{display:inline-block;background:#0f1f3d;color:#fff;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:700;}
.quick-info-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;position:sticky;top:16px;}
.qi-header{background:#0f1f3d;color:#fff;padding:12px 16px;font-size:14px;font-weight:700;}
.qi-body{padding:14px 16px;}
.qi-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-size:13px;}
.qi-label{color:#777;font-weight:500;}
.qi-val{font-weight:600;color:#222;}
.qi-divider{border:none;border-top:1px solid #f0f0f0;margin:10px 0;}
.qi-actions-title{font-size:13px;font-weight:700;color:#333;margin-bottom:10px;}
.action-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:9px 12px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;text-decoration:none;margin-bottom:8px;transition:all .15s;}
.action-btn:last-child{margin-bottom:0;}
.action-btn.ab-disabled{background:#f0f0f0;color:#aaa;cursor:not-allowed;}
.action-btn.ab-navy{background:#0f1f3d;color:#fff;}
.action-btn.ab-navy:hover{background:#1a3a6b;color:#fff;text-decoration:none;}
.action-btn.ab-yellow{background:#f0a500;color:#fff;}
.action-btn.ab-yellow:hover{background:#d4911a;color:#fff;text-decoration:none;}
.action-btn.ab-purple{background:#6f42c1;color:#fff;}
.action-btn.ab-purple:hover{background:#5a359a;color:#fff;text-decoration:none;}
.action-btn.ab-red{background:#dc3545;color:#fff;}
.action-btn.ab-red:hover{background:#b02a37;color:#fff;text-decoration:none;}
.st-badge{display:inline-block;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.st-cancelled{background:#f8d7da;color:#721c24;}
.st-hold{background:#fff3cd;color:#856404;}
.st-success{background:#d4edda;color:#155724;}
.st-issued{background:#cce5ff;color:#004085;}
.st-refund{background:#f8d7da;color:#721c24;}
.badge-yes{background:#d4edda;color:#155724;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.badge-no{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.badge-refundable{background:#d4edda;color:#155724;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.badge-nonrefund{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;}
.void-notice{background:#fff8e1;border:1px solid #ffc107;border-radius:5px;padding:10px 12px;font-size:12px;color:#856404;margin-top:10px;}
</style>
@endsection
@section('content')
@php
  $statusNum = is_numeric($booking->status) ? (int)$booking->status : -1;
  if ($statusNum === 0) { $stClass = 'st-hold'; $stText = 'Booking-Hold'; }
  elseif ($statusNum === 1) { $stClass = 'st-success'; $stText = 'Booking-Success'; }
  elseif ($statusNum === 2) { $stClass = 'st-issued'; $stText = 'Ticket-Issued'; }
  elseif ($statusNum === 3) { $stClass = 'st-cancelled'; $stText = 'Booking-Cancelled'; }
  elseif ($statusNum === 4) { $stClass = 'st-refund'; $stText = 'Ticket-Refund'; }
  else {
    $stText = ucwords(str_replace(['-','_'],' ',$booking->status ?? ''));
    $stLower = strtolower($booking->status ?? '');
    if (str_contains($stLower,'cancel')) $stClass = 'st-cancelled';
    elseif (str_contains($stLower,'refund')) $stClass = 'st-refund';
    elseif (str_contains($stLower,'issue')) $stClass = 'st-issued';
    elseif (str_contains($stLower,'hold')||str_contains($stLower,'pending')) $stClass = 'st-hold';
    else $stClass = 'st-success';
  }
  $jt = (int)($booking->flight_type ?? $booking->journey_type ?? 1);
  $jtText = $jt === 2 ? 'Round Trip' : ($jt === 3 ? 'Multi City' : 'One Way');
  $depCode = strtoupper(substr($booking->departure_location ?? '', 0, 3));
  $arrCode = strtoupper(substr($booking->arrival_location ?? '', 0, 3));
@endphp

<div class="row"><div class="col-12">
  <div class="card" style="border-radius:8px;overflow:hidden;border:1px solid #e0e0e0;">

    {{-- Header --}}
    <div class="b2b-detail-header">
      <a href="{{ url('my/bookings') }}" class="b2b-back-btn"><i class="fas fa-arrow-left"></i> Back</a>
      <h5 class="b2b-detail-title">Booking Details ({{ $booking->booking_no }})</h5>
    </div>

    <div class="row g-0">
      {{-- LEFT: Tables --}}
      <div class="col-lg-8" style="border-right:1px solid #e9ecef;">

        {{-- Traveler Details --}}
        <div class="b2b-section">
          <div class="b2b-section-title">Traveler Details</div>
          <div style="overflow-x:auto;">
            <table class="b2b-detail-tbl">
              <thead><tr>
                <th>Name</th><th>Date of Birth</th><th>Type</th><th>Gender</th>
                <th>Flyer Number</th><th>Passport Number</th><th>Passport Expiry Date</th>
                <th>Ticket Number</th><th>Wheelchair Required</th>
              </tr></thead>
              <tbody>
                @forelse($passengers as $p)
                <tr>
                  <td style="text-align:left;font-weight:600;">
                    {{ strtoupper($p->title ?? '') }} {{ $p->first_name }} {{ $p->last_name }}
                  </td>
                  <td>{{ $p->dob ? date('d-M-Y', strtotime($p->dob)) : 'N/A' }}</td>
                  <td>{{ strtoupper($p->passenger_type ?? 'ADT') }}</td>
                  <td>{{ strtoupper(substr($p->gender ?? 'M', 0, 1)) }}</td>
                  <td>{{ $p->frequent_flyer_no ?? '—' }}</td>
                  <td>{{ $p->document_no ?? 'N/A' }}</td>
                  <td>{{ $p->document_expire_date ? date('d-M-Y', strtotime($p->document_expire_date)) : 'N/A' }}</td>
                  <td>{{ $p->ticket_no ?? '—' }}</td>
                  <td>
                    @if(!empty($p->wheelchair_required) && $p->wheelchair_required)
                      <i class="fas fa-check" style="color:green;"></i>
                    @else
                      <i class="fas fa-times" style="color:red;"></i>
                    @endif
                  </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-3">No traveler information available.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <hr style="margin:0;">

        {{-- Travel Segments --}}
        <div class="b2b-section">
          <div class="b2b-section-title">Travel Segments</div>
          <div style="overflow-x:auto;">
            <table class="b2b-detail-tbl">
              <thead><tr>
                <th>Flight Number</th><th>Airline</th><th>Origin</th><th>Destination</th>
                <th>Class</th><th>Baggage</th><th>Departure Date</th><th>Arrival Date</th>
              </tr></thead>
              <tbody>
                @forelse($segments as $s)
                <tr>
                  <td style="font-weight:700;">{{ ($s->carrier_marketing_code ?? '') . ($s->carrier_marketing_flight_number ?? '') ?: 'N/A' }}</td>
                  <td>
                    <span class="airline-badge">{{ strtoupper($s->carrier_marketing_code ?? '??') }}</span>
                  </td>
                  <td style="text-align:left;">{{ $s->departure_airport_code ?? $s->departure_city_code ?? 'N/A' }}</td>
                  <td style="text-align:left;">{{ $s->arrival_airport_code ?? $s->arrival_city_code ?? 'N/A' }}</td>
                  <td>{{ $s->cabin_code ?: ($s->booking_code ?: 'Economy') }}</td>
                  <td>{{ $s->baggage_allowance ?: 'N/A' }}</td>
                  <td>
                    {{ $booking->departure_date ? date('d-M-Y', strtotime($booking->departure_date)) : 'N/A' }}
                    @if(!empty($s->departure_time))
                      <div style="font-size:11px;color:#666;">{{ date('h:i A', strtotime(preg_replace('/\+.*/', '', $s->departure_time))) }}</div>
                    @endif
                  </td>
                  <td>
                    @if(!empty($s->arrival_time))
                      {{ date('h:i A', strtotime(preg_replace('/\+.*/', '', $s->arrival_time))) }}
                    @else
                      N/A
                    @endif
                  </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No travel segment information available.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <hr style="margin:0;">

        {{-- Fare Details --}}
        <div class="b2b-section">
          <div class="b2b-section-title">Fare Details</div>
          <div style="overflow-x:auto;">
            <table class="b2b-detail-tbl">
              <thead><tr>
                <th>Base Fare</th><th>Total Tax</th><th>Discount</th><th>AIT</th><th>Payable Amount</th>
              </tr></thead>
              <tbody>
                <tr>
                  <td>৳ {{ number_format($booking->base_fare_amount ?? ($booking->total_fare ?? 0) * 0.85, 2) }}</td>
                  <td>৳ {{ number_format($booking->total_tax_amount ?? ($booking->total_fare ?? 0) * 0.15, 2) }}</td>
                  <td>৳ {{ number_format($booking->discount ?? 0, 2) }}</td>
                  <td>৳ {{ number_format($booking->ait ?? 0, 2) }}</td>
                  <td style="font-weight:700;color:#0f1f3d;">৳ {{ number_format($booking->total_fare ?? 0, 2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      {{-- ANCILLARY SECTION --}}
      @php $ancillaries = \App\Models\BookingAncillary::where('flight_booking_id', $booking->id)->get(); @endphp
      <div class="col-12" style="border-top:1px solid #e9ecef;">
        <div class="b2b-section">
          <div class="b2b-section-title" style="display:flex;justify-content:space-between;align-items:center;">
            <span>Ancillary Services (Baggage &amp; Meal)</span>
            <button type="button" class="btn-export" style="background:#f0a500;font-size:12px;" data-bs-toggle="modal" data-bs-target="#ancModal">
              <i class="fas fa-plus"></i> Add Ancillary
            </button>
          </div>
          <div style="overflow-x:auto;">
            <table class="b2b-detail-tbl">
              <thead><tr>
                <th>Type</th><th>Name</th><th>Pax</th><th>Qty</th>
                <th>Unit Price</th><th>Total</th><th>Status</th><th>Action</th>
              </tr></thead>
              <tbody>
                @forelse($ancillaries as $anc)
                <tr>
                  <td><span class="status-badge" style="background:#cce5ff;color:#004085;">{{ strtoupper($anc->type) }}</span></td>
                  <td>{{ $anc->name }}</td>
                  <td>Pax {{ $anc->pax_index + 1 }}</td>
                  <td>{{ $anc->qty }}</td>
                  <td>৳ {{ number_format($anc->unit_price, 2) }}</td>
                  <td style="font-weight:700;">৳ {{ number_format($anc->total_price, 2) }}</td>
                  <td><span class="status-badge st-{{ $anc->status }}">{{ strtoupper($anc->status) }}</span></td>
                  <td>
                    <button class="btn-eye" style="background:#dc3545;" onclick="removeAncillary({{ $anc->id }})" title="Remove">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No ancillary services added yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- RIGHT: Quick Info + Actions --}}
      <div class="col-lg-4" style="padding:16px;">
        <div class="quick-info-card">
          <div class="qi-header">Quick Information</div>
          <div class="qi-body">
            <div class="qi-row">
              <span class="qi-label">Status:</span>
              <span class="st-badge {{ $stClass }}">{{ $stText }}</span>
            </div>
            <div class="qi-row">
              <span class="qi-label">PNR Code</span>
              <span class="qi-val">{{ $booking->pnr_id ?? 'N/A' }}</span>
            </div>
            <div class="qi-row">
              <span class="qi-label">Create Date</span>
              <span class="qi-val">{{ $booking->created_at ? date('d-M-Y', strtotime($booking->created_at)) : 'N/A' }}</span>
            </div>
            <div class="qi-row">
              <span class="qi-label">Payment Percentage</span>
              <span class="qi-val">{{ $booking->payment_percentage ?? '100.00' }}%</span>
            </div>
            <div class="qi-row">
              <span class="qi-label">Partial Payment</span>
              @if(!empty($booking->partial_payment))
                <span class="badge-yes">Yes</span>
              @else
                <span class="badge-no">No</span>
              @endif
            </div>
            <div class="qi-row">
              <span class="qi-label">Refundable</span>
              @if(!empty($booking->refundable))
                <span class="badge-refundable">Refundable</span>
              @else
                <span class="badge-nonrefund">Non-Refundable</span>
              @endif
            </div>
            <div class="qi-row">
              <span class="qi-label">Ticket issue last Date</span>
              <span class="qi-val" style="font-size:12px;">
                {{ $booking->last_ticket_datetime ? date('d-M-Y H:i', strtotime($booking->last_ticket_datetime)) : 'Still Not Available' }}
              </span>
            </div>

            <hr class="qi-divider">

            <div class="qi-actions-title">Action</div>

            {{-- Issue Ticket: enabled when status=1 --}}
            @if($statusNum === 1)
              <a href="{{ route('IssueFlightTicket', $booking->booking_no) }}" class="action-btn ab-navy">
                <i class="fas fa-ticket-alt"></i> Issue Ticket
              </a>
            @else
              <button class="action-btn ab-disabled" disabled>
                <i class="fas fa-ticket-alt"></i> Issue Ticket
              </button>
            @endif

            {{-- Cancel Booking: enabled when status=0 or 1 --}}
            @if(in_array($statusNum,[0,1]))
              <a href="{{ route('CancelFlightBooking', $booking->booking_no) }}" class="action-btn ab-red"
                 onclick="return confirm('Cancel this booking?')">
                <i class="fas fa-times-circle"></i> Cancel Booking
              </a>
            @else
              <button class="action-btn ab-disabled" disabled>
                <i class="fas fa-times-circle"></i> Cancel Booking
              </button>
            @endif

            {{-- Full Details --}}
            <a href="{{ url('flight/booking/details/'.$booking->booking_no) }}" class="action-btn ab-navy">
              <i class="fas fa-clipboard-list"></i> Full Details
            </a>

            {{-- Refund Ticket: enabled when issued --}}
            @if($statusNum === 2)
              <a href="{{ route('MyCreateRefund') }}?booking_ref={{ $booking->booking_no }}" class="action-btn ab-navy">
                <i class="fas fa-hand-holding-usd"></i> Refund Ticket
              </a>
            @else
              <button class="action-btn ab-disabled" disabled>
                <i class="fas fa-hand-holding-usd"></i> Refund Ticket
              </button>
            @endif

            {{-- Reissue Ticket: enabled when issued --}}
            @if($statusNum === 2)
              <a href="{{ route('MyCreateReissue') }}?booking_ref={{ $booking->booking_no }}" class="action-btn ab-navy">
                <i class="fas fa-redo"></i> Reissue Ticket
              </a>
            @else
              <button class="action-btn ab-disabled" disabled>
                <i class="fas fa-redo"></i> Reissue Ticket
              </button>
            @endif

            {{-- Preview / Print E-Ticket --}}
            <a href="{{ url('booking/preview/'.$booking->booking_no) }}" target="_blank" class="action-btn ab-yellow">
              <i class="fas fa-print"></i> Print E-Ticket
            </a>

            {{-- Support --}}
            <a href="{{ url('my/booking-support/create') }}?booking_ref={{ $booking->booking_no }}" class="action-btn ab-purple">
              <i class="fas fa-headset"></i> Support
            </a>

            {{-- Void Ticket: enabled when issued --}}
            @if($statusNum === 2)
              <a href="{{ route('MyCreateVoid') }}?booking_ref={{ $booking->booking_no }}" class="action-btn ab-navy">
                <i class="fas fa-ban"></i> Void Ticket
              </a>
            @else
              <button class="action-btn ab-disabled" disabled>
                <i class="fas fa-ban"></i> Void Ticket
              </button>
            @endif

            <div class="void-notice">
              <strong>Voiding Before</strong><br>
              For Voidable Tickets, Submit Request For Void Before 23:00 (Bangladesh Time)
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div></div>

{{-- Ancillary Add Modal --}}
<div class="modal fade" id="ancModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#0f1f3d;color:#fff;">
        <h5 class="modal-title">Add Ancillary Service</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Type</label>
          <select id="ancType" class="form-select" onchange="loadAncOptions()">
            <option value="baggage">Baggage</option>
            <option value="meal">Meal</option>
            <option value="seat">Seat</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Option (or enter custom below)</label>
          <select id="ancOptionSelect" class="form-select" onchange="fillAncOption()">
            <option value="">-- Loading... --</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" id="ancName" class="form-control" placeholder="e.g. Extra 10kg Baggage" required>
        </div>
        <div class="row g-2">
          <div class="col-4">
            <label class="form-label">Qty</label>
            <input type="number" id="ancQty" class="form-control" value="1" min="1" oninput="calcAncTotal()">
          </div>
          <div class="col-4">
            <label class="form-label">Unit Price (৳)</label>
            <input type="number" id="ancPrice" class="form-control" step="0.01" min="0" value="0" oninput="calcAncTotal()">
          </div>
          <div class="col-4">
            <label class="form-label">Total (৳)</label>
            <input type="text" id="ancTotal" class="form-control" readonly style="background:#f8f9fa;font-weight:700;">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Passenger</label>
          <select id="ancPax" class="form-select">
            @foreach($passengers as $pi => $p)
              <option value="{{ $pi }}">Pax {{ $pi+1 }}: {{ $p->first_name }} {{ $p->last_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mt-3">
          <label class="form-label">Notes</label>
          <input type="text" id="ancNotes" class="form-control" placeholder="Optional">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitAncillary()">Add to Booking</button>
      </div>
    </div>
  </div>
</div>

<script>
const BOOKING_ID = {{ $booking->id }};
const AIRLINE    = '{{ $booking->governing_carriers ?? '' }}';
const FROM_CODE  = '{{ $booking->departure_location ?? '' }}';
const TO_CODE    = '{{ $booking->arrival_location ?? '' }}';

function loadAncOptions() {
    const type = document.getElementById('ancType').value;
    fetch(`{{ url('ancillary/options') }}?type=${type}&airline_code=${AIRLINE}&route_from=${FROM_CODE}&route_to=${TO_CODE}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('ancOptionSelect');
            sel.innerHTML = '<option value="">-- Select option or type custom --</option>';
            data.forEach(o => {
                sel.innerHTML += `<option value="${o.id}" data-name="${o.name}" data-price="${o.price}">${o.name} — ৳${parseFloat(o.price).toFixed(2)}</option>`;
            });
        }).catch(() => {
            document.getElementById('ancOptionSelect').innerHTML = '<option value="">-- No options found, enter custom --</option>';
        });
}

function fillAncOption() {
    const sel = document.getElementById('ancOptionSelect');
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('ancName').value  = opt.dataset.name;
        document.getElementById('ancPrice').value = opt.dataset.price;
        calcAncTotal();
    }
}

function calcAncTotal() {
    const qty   = parseFloat(document.getElementById('ancQty').value)   || 1;
    const price = parseFloat(document.getElementById('ancPrice').value) || 0;
    document.getElementById('ancTotal').value = (qty * price).toFixed(2);
}

function submitAncillary() {
    const name = document.getElementById('ancName').value.trim();
    if (!name) { alert('Please enter a name.'); return; }
    const sel = document.getElementById('ancOptionSelect');
    const payload = {
        flight_booking_id:   BOOKING_ID,
        ancillary_option_id: sel.value || null,
        type:       document.getElementById('ancType').value,
        name:       name,
        pax_index:  document.getElementById('ancPax').value,
        qty:        document.getElementById('ancQty').value,
        unit_price: document.getElementById('ancPrice').value,
        notes:      document.getElementById('ancNotes').value,
    };
    fetch('{{ route("AncillaryAddToBooking") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(payload),
    }).then(r => r.json()).then(res => {
        if (res.success) { location.reload(); }
        else { alert(res.message || 'Failed.'); }
    });
}

function removeAncillary(id) {
    if (!confirm('Remove this ancillary?')) return;
    fetch(`{{ url('ancillary') }}/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    }).then(r => r.json()).then(res => { if (res.success) location.reload(); });
}

document.addEventListener('DOMContentLoaded', loadAncOptions);
</script>
@endsection
