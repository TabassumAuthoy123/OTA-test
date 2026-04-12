@extends('master')

@section('content')
<style>
    .bd-page { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
    .bd-header { background: linear-gradient(135deg, #1e3a5f 0%, #2d5f8a 100%); border-radius: 12px 12px 0 0; padding: 20px 28px; }
    .bd-header h5 { color: #fff; font-weight: 700; font-size: 20px; margin: 0; letter-spacing: 0.3px; }
    .bd-header .btn { border-radius: 6px; font-size: 12.5px; font-weight: 600; padding: 6px 14px; border: none; transition: all 0.2s; }
    .bd-header .btn:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.25); }
    .bd-header .btn-issue { background: #28a745; color: #fff; }
    .bd-header .btn-cancel { background: #dc3545; color: #fff; }
    .bd-header .btn-preview { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); }
    .bd-header .btn-share { background: #17a2b8; color: #fff; }

    .bd-status-strip { padding: 10px 28px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .bd-status-strip .status-badge { padding: 5px 16px; border-radius: 20px; font-weight: 600; font-size: 12.5px; letter-spacing: 0.5px; }
    .status-booked { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    .status-issued { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    .status-cancelled { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
    .status-requested { background: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
    .bd-status-strip .meta-info { font-size: 12.5px; color: #6c757d; }
    .bd-status-strip .meta-info strong { color: #495057; }

    .bd-body { padding: 24px 28px; background: #fff; }

    .info-card { background: #f8f9fb; border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; height: 100%; transition: box-shadow 0.2s; }
    .info-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .info-card-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; gap: 8px; }
    .info-card-title i { font-size: 15px; opacity: 0.7; }
    .info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f0f0f0; font-size: 13.5px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #6c757d; font-weight: 500; min-width: 120px; }
    .info-value { color: #212529; font-weight: 600; text-align: right; word-break: break-word; }
    .pnr-highlight { background: #e3f2fd; color: #0d47a1; padding: 3px 10px; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 14px; font-weight: 700; letter-spacing: 1px; }
    .mode-badge { padding: 2px 10px; border-radius: 4px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .mode-live { background: #28a745; color: #fff; }
    .mode-sandbox { background: #ff6b35; color: #fff; }

    .section-title { font-size: 15px; font-weight: 700; color: #1e3a5f; margin: 28px 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; gap: 8px; }
    .section-title i { color: #2d5f8a; }

    .itinerary-table { border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6; }
    .itinerary-table thead th { background: #1e3a5f; color: #fff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px; border: none; text-align: center; vertical-align: middle; }
    .itinerary-table tbody td { font-size: 13px; padding: 12px; vertical-align: middle; text-align: center; }
    .itinerary-table .route-row { background: #fff3cd; font-weight: 600; font-size: 13px; color: #856404; }
    .itinerary-table .transit-row { background: #e9ecef; font-size: 12.5px; color: #6c757d; font-style: italic; }

    .pax-table { border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6; }
    .pax-table thead th { background: #2d5f8a; color: #fff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 8px; border: none; text-align: center; }
    .pax-table tbody td { font-size: 13px; padding: 10px 8px; text-align: center; vertical-align: middle; }
    .ticket-no-badge { background: #e8f5e9; color: #2e7d32; padding: 3px 8px; border-radius: 4px; font-family: 'Courier New', monospace; font-weight: 700; font-size: 12.5px; }
    .ticket-na { color: #adb5bd; font-style: italic; font-size: 12px; }

    .fare-card { background: linear-gradient(135deg, #f8f9fb 0%, #eef2f7 100%); border: 1px solid #dee2e6; border-radius: 10px; padding: 20px; }
    .fare-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
    .fare-row.total { border-top: 2px solid #1e3a5f; margin-top: 8px; padding-top: 14px; }
    .fare-row .fare-label { color: #6c757d; }
    .fare-row .fare-amount { font-weight: 700; color: #212529; }
    .fare-row.total .fare-label { font-size: 16px; font-weight: 700; color: #1e3a5f; }
    .fare-row.total .fare-amount { font-size: 18px; color: #1e3a5f; }

    .audit-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
    .audit-card table thead th { background: #2c3e50; color: #fff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px; }
    .audit-card table tbody td { font-size: 13px; padding: 10px 12px; vertical-align: middle; }
    .audit-badge { padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .audit-issue { background: #d4edda; color: #155724; }
    .audit-cancel { background: #fff3cd; color: #856404; }
    .audit-void { background: #f8d7da; color: #721c24; }
    .audit-refund { background: #f8d7da; color: #721c24; }
    .audit-default { background: #d1ecf1; color: #0c5460; }

    .sandbox-alert { background: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 16px 20px; }
    .sandbox-alert h6 { font-size: 14px; font-weight: 700; color: #e65100; margin-bottom: 8px; }
    .sandbox-alert p { font-size: 13px; color: #6d4c00; margin-bottom: 12px; }
</style>

<div class="bd-page">
    {{-- ─── Header ─── --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="bd-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5><i class="fas fa-plane-departure me-2"></i> Flight Booking Details</h5>
            <div class="d-flex gap-2 flex-wrap">
                @if($flightBookingDetails->gds == 'Sabre')
                <a href="{{url('booking/preview')}}/{{$flightBookingDetails->booking_no}}" class="btn btn-preview"><i class="fa fa-print me-1"></i> Preview</a>
                @endif
                @if($flightBookingDetails->status == 1)
                    <a href="{{url('issue/flight/ticket')}}/{{$flightBookingDetails->booking_no}}" class="btn btn-issue"><i class="fas fa-check-circle me-1"></i> Issue Ticket</a>
                    <a href="{{url('cancel/flight/booking')}}/{{$flightBookingDetails->booking_no}}" class="btn btn-cancel"><i class="fas fa-times-circle me-1"></i> Cancel</a>
                @endif
                @if($flightBookingDetails->status == 2)
                    <a href="{{url('cancel/issued/ticket')}}/{{$flightBookingDetails->booking_no}}" class="btn btn-cancel"><i class="fas fa-ban me-1"></i> Void Ticket</a>
                @endif
                <a href="javascript:void(0)" onclick="sharePnr('{{$flightBookingDetails->pnr_id}}', '{{$flightBookingDetails->traveller_email}}', '{{$flightBookingDetails->traveller_contact}}')" class="btn btn-share"><i class="fas fa-share-alt me-1"></i> Share PNR</a>
            </div>
        </div>

        {{-- ─── Status Strip ─── --}}
        <div class="bd-status-strip" style="background: #f8f9fb; border-bottom: 1px solid #e9ecef;">
            <span class="status-badge
                @if($flightBookingDetails->status == 0) status-requested
                @elseif($flightBookingDetails->status == 1) status-booked
                @elseif($flightBookingDetails->status == 2) status-issued
                @else status-cancelled @endif">
                <i class="fas fa-circle me-1" style="font-size: 7px;"></i>
                @if($flightBookingDetails->status == 0) BOOKING REQUESTED
                @elseif($flightBookingDetails->status == 1) BOOKED
                @elseif($flightBookingDetails->status == 2) TICKET ISSUED
                @elseif($flightBookingDetails->status == 3) BOOKING CANCELLED
                @elseif($flightBookingDetails->status == 4) TICKET VOIDED
                @endif
            </span>
            <span class="meta-info"><strong>Booking #</strong> {{ $flightBookingDetails->booking_no }}</span>
            <span class="meta-info"><strong>Date:</strong> {{ date('d M Y, h:i A', strtotime($flightBookingDetails->created_at)) }}</span>
            <span class="meta-info"><strong>GDS:</strong> {{ $flightBookingDetails->gds }} ({{ $flightBookingDetails->gds_unique_id }})</span>
            <span class="mode-badge @if($flightBookingDetails->is_live == 1) mode-live @else mode-sandbox @endif">
                @if($flightBookingDetails->is_live == 1) LIVE @else SANDBOX @endif
            </span>
        </div>

        <div class="bd-body">
            {{-- ─── Info Cards ─── --}}
            <div class="row g-3 mb-4">
                {{-- Booking Info --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-card-title"><i class="fas fa-clipboard-list"></i> Booking Information</div>
                        <div class="info-row">
                            <span class="info-label">Source</span>
                            <span class="info-value">@if($flightBookingDetails->source == 1) OTA Portal @elseif($flightBookingDetails->source == 2) Website @else Mobile App @endif</span>
                        </div>
                        @if($flightBookingDetails->payment_status)
                        <div class="info-row">
                            <span class="info-label">Payment</span>
                            <span class="info-value">
                                @if($flightBookingDetails->payment_status == 0)
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($flightBookingDetails->payment_status == 1)
                                    <span class="badge bg-success">Paid</span>
                                    <small class="text-muted">(@if($flightBookingDetails->payment_method == 1) SSLCommerz @elseif($flightBookingDetails->payment_method == 2) bKash @else Nagad @endif)</small>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </span>
                        </div>
                        @endif
                        @if($flightBookingDetails->transaction_id)
                        <div class="info-row">
                            <span class="info-label">Transaction ID</span>
                            <span class="info-value" style="font-family: monospace;">{{ $flightBookingDetails->transaction_id }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Booked By</span>
                            <span class="info-value">
                                @php $bookedByUser = DB::table('users')->where('id', $flightBookingDetails->booked_by)->first(); @endphp
                                {{ $bookedByUser ? $bookedByUser->name : 'Passenger' }}
                            </span>
                        </div>
                        @if($flightBookingDetails->passenger_id)
                        <div class="info-row">
                            <span class="info-label">Passenger Acc.</span>
                            <span class="info-value">
                                @php
                                    $userInfo = DB::table('users')->where('id', $flightBookingDetails->passenger_id)->first();
                                @endphp
                                @if($userInfo) {{ $userInfo->name }} <small class="text-muted">({{ $userInfo->email }})</small> @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Traveller & PNR Info --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-card-title"><i class="fas fa-user-tag"></i> Traveller & PNR</div>
                        @if($flightBookingDetails->pnr_id)
                        <div class="info-row">
                            <span class="info-label">GDS PNR</span>
                            <span class="info-value"><span class="pnr-highlight">{{ $flightBookingDetails->pnr_id }}</span></span>
                        </div>
                        @endif
                        @if($flightBookingDetails->airlines_pnr)
                        <div class="info-row">
                            <span class="info-label">Airline PNR</span>
                            <span class="info-value"><span class="pnr-highlight" style="background: #fce4ec; color: #c62828;">{{ $flightBookingDetails->airlines_pnr }}</span></span>
                        </div>
                        @endif
                        @if($flightBookingDetails->pnr_id != $flightBookingDetails->booking_id)
                        <div class="info-row">
                            <span class="info-label">Booking ID</span>
                            <span class="info-value" style="font-family: monospace;">{{ $flightBookingDetails->booking_id }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value">{{ $flightBookingDetails->traveller_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $flightBookingDetails->traveller_email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Contact</span>
                            <span class="info-value">{{ $flightBookingDetails->traveller_contact }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Passengers</span>
                            <span class="info-value">
                                @if($flightBookingDetails->adult) <span class="badge bg-primary">{{ $flightBookingDetails->adult }} Adult</span> @endif
                                @if($flightBookingDetails->child) <span class="badge bg-info text-dark">{{ $flightBookingDetails->child }} Child</span> @endif
                                @if($flightBookingDetails->infant) <span class="badge bg-secondary">{{ $flightBookingDetails->infant }} Infant</span> @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Flight & Fare Info --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-card-title"><i class="fas fa-plane"></i> Flight & Fare</div>
                        <div class="info-row">
                            <span class="info-label">Route</span>
                            <span class="info-value" style="font-weight: 700; color: #1e3a5f;">
                                {{ $flightBookingDetails->departure_location }} <i class="fas fa-long-arrow-alt-right" style="color: #adb5bd; font-size: 11px;"></i> {{ $flightBookingDetails->arrival_location }}
                                @if($flightBookingDetails->flight_type == 2) <i class="fas fa-long-arrow-alt-right" style="color: #adb5bd; font-size: 11px;"></i> {{ $flightBookingDetails->departure_location }} @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Departure</span>
                            <span class="info-value">
                                @php
                                    $departure = $bookingResSegs ? ($bookingResSegs[0]['Product']['ProductDetails']['Air']['DepartureDateTime'] ?? null) : null;
                                    if($departure) { $dt = explode('T', $departure); echo date('d M Y', strtotime($dt[0]))." ".substr($dt[1], 0, 5); }
                                @endphp
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Arrival</span>
                            <span class="info-value">
                                @php
                                    $arrival = $bookingResSegs ? ($bookingResSegs[count($flightSegments)-1]['Product']['ProductDetails']['Air']['ArrivalDateTime'] ?? null) : null;
                                    if($arrival) { $dt = explode('T', $arrival); echo date('d M Y', strtotime($dt[0]))." ".substr($dt[1], 0, 5); }
                                @endphp
                            </span>
                        </div>

                        @if($flightBookingDetails->status == 1 && $flightpassengers[0]->ticket_no == null)
                        <div class="info-row">
                            <span class="info-label">Last Ticket Time</span>
                            <span class="info-value">
                                @if($flightBookingDetails->last_ticket_datetime)
                                    <span style="color: #c62828; font-weight: 700;">{{ date("d M Y, H:i:s", strtotime($flightBookingDetails->last_ticket_datetime)) }}</span>
                                @else
                                    <a href="{{url('flight/booking/details')}}/{{$flightBookingDetails->booking_no}}" class="btn btn-sm btn-outline-success" style="font-size: 11px; padding: 2px 8px;">Refresh</a>
                                @endif
                            </span>
                        </div>
                        @endif

                        <div style="margin-top: 14px; padding-top: 12px; border-top: 2px solid #e2e8f0;">
                            <div class="fare-row">
                                <span class="fare-label">Base Fare</span>
                                <span class="fare-amount">{{ number_format($flightBookingDetails->base_fare_amount) }} {{ $flightBookingDetails->currency }}</span>
                            </div>
                            <div class="fare-row">
                                <span class="fare-label">Tax</span>
                                <span class="fare-amount">{{ number_format($flightBookingDetails->total_tax_amount) }} {{ $flightBookingDetails->currency }}</span>
                            </div>
                            <div class="fare-row total">
                                <span class="fare-label">Total Fare</span>
                                <span class="fare-amount">{{ number_format($flightBookingDetails->total_fare) }} {{ $flightBookingDetails->currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($flightBookingDetails->status == 1 && !$flightBookingDetails->last_ticket_datetime)
            <div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius: 8px; font-size: 13px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Airlines does not share Last Ticket Datetime instantly right after PNR creation.
            </div>
            @endif

            {{-- ─── Flight Itinerary ─── --}}
            <div class="section-title"><i class="fas fa-route"></i> Flight Itinerary</div>
            @include('booking.segments')

            {{-- ─── Passengers Table ─── --}}
            <div class="section-title"><i class="fas fa-users"></i> Passengers & Tickets</div>
            <div class="table-responsive mb-4">
                <table class="table table-hover pax-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket No</th>
                            <th>Type</th>
                            <th>Passenger Name</th>
                            <th>Date of Birth</th>
                            <th>Document</th>
                            <th>Doc Number</th>
                            <th>Expiry</th>
                            <th>Issued By</th>
                            <th>Nationality</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flightpassengers as $passengerIndex => $flightpassenger)
                        <tr>
                            <td><strong>{{ $passengerIndex + 1 }}</strong></td>
                            <td>
                                @if($flightpassenger->ticket_no)
                                    <span class="ticket-no-badge">{{ $flightpassenger->ticket_no }}</span>
                                @else
                                    <span class="ticket-na">Not Issued</span>
                                @endif
                            </td>
                            <td><span class="badge bg-primary" style="font-size: 11px;">{{ $flightpassenger->passenger_type }}</span></td>
                            <td style="font-weight: 600;">{{ $flightpassenger->title }} {{ $flightpassenger->first_name }} {{ $flightpassenger->last_name }}</td>
                            <td>{{ $flightpassenger->dob }}</td>
                            <td>
                                @if($flightpassenger->document_type == 1)
                                    <i class="fas fa-passport text-primary me-1"></i> Passport
                                @else
                                    <i class="fas fa-id-card text-secondary me-1"></i> NID
                                @endif
                            </td>
                            <td style="font-family: monospace; font-weight: 600;">{{ $flightpassenger->document_no }}</td>
                            <td>{{ $flightpassenger->document_expire_date }}</td>
                            <td>{{ $flightpassenger->document_issue_country }}</td>
                            <td>{{ $flightpassenger->nationality }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ─── Sandbox Manual PNR Update ─── --}}
            @if($flightBookingDetails->status == 0)
            <div class="sandbox-alert mb-4">
                <h6><i class="fas fa-exclamation-triangle me-1"></i> Manual PNR Update Required</h6>
                <p class="mb-3">In Sandbox mode, some flights cannot be booked through automation. You need to book them manually and update the PNR ID for later processing like Ticket Issue or Cancel Booking.</p>
                <form action="{{url('update/pnr/booking')}}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_no" value="{{$flightBookingDetails->booking_no}}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-5">
                            <label class="form-label fw-bold" style="font-size: 12px;">PNR ID</label>
                            <input type="text" class="form-control form-control-sm" name="pnr_id" placeholder="Enter PNR ID" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-bold" style="font-size: 12px;">Status</label>
                            <select class="form-select form-select-sm" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1">Booking Done</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-sync-alt me-1"></i> Update PNR</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            {{-- ─── Audit Trail ─── --}}
            @if(isset($auditLogs) && count($auditLogs) > 0)
            <div class="section-title"><i class="fas fa-history"></i> Audit Trail</div>
            <div class="audit-card mb-2">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 180px;">Timestamp</th>
                                <th style="width: 130px;">Action</th>
                                <th>Description</th>
                                <th style="width: 150px;">Triggered By</th>
                                <th style="width: 130px;">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auditLogs as $log)
                            <tr>
                                <td><i class="far fa-clock me-1" style="color: #adb5bd;"></i> {{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                <td>
                                    <span class="audit-badge
                                        @if($log->action == 'ISSUE') audit-issue
                                        @elseif($log->action == 'CANCEL_BOOKING') audit-cancel
                                        @elseif($log->action == 'VOID') audit-void
                                        @elseif($log->action == 'REFUND') audit-refund
                                        @else audit-default @endif">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td><i class="fas fa-user-circle me-1" style="color: #adb5bd;"></i> {{ $log->user ? $log->user->name : 'System' }}</td>
                                <td style="font-family: monospace; font-size: 12px;">{{ $log->ip_address ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ─── Share PNR Modal ─── --}}
<div class="modal fade" id="exampleModal2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3a5f, #2d5f8a); border-radius: 12px 12px 0 0;">
                <h5 class="modal-title text-white"><i class="fas fa-share-alt me-2"></i> Send PNR Copy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="productForm2" name="productForm2" class="form-horizontal">
                    <div class="form-group mb-3">
                        <label for="pnr_id" class="form-label fw-bold" style="font-size: 13px;">PNR ID</label>
                        <input type="text" id="pnr_id" class="form-control" placeholder="PNR ID" readonly style="background: #f8f9fb;">
                    </div>
                    <div class="form-group mb-3">
                        <label for="traveller_email" class="form-label fw-bold" style="font-size: 13px;">Traveller Email</label>
                        <input type="text" id="traveller_email" class="form-control" placeholder="traveller@email.com">
                    </div>
                    <div class="form-group">
                        <label for="traveller_contact" class="form-label fw-bold" style="font-size: 13px;">Traveller Contact</label>
                        <input type="text" id="traveller_contact" class="form-control" placeholder="8801*********">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveBtn" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i> Send PNR</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_js')
    <script>
        function sharePnr(pnr_id, traveller_email, traveller_contact){
            $('#productForm2').trigger("reset");
            $('#exampleModal2').modal('show');
            $("#pnr_id").val(pnr_id);
            $("#traveller_email").val(traveller_email);
            $("#traveller_contact").val(traveller_contact);
        }

        $('#saveBtn').click(function (e) {
            $('#exampleModal2').modal('hide');
            toastr.success("PNR Shared Successfully", "Sent");
        });
    </script>
@endsection
