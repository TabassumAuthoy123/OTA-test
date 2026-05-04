@extends('master')

@section('header_css')
<style>
.nl-stat { background:#fff; border-radius:8px; padding:16px 20px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
.nl-stat .num { font-size:26px; font-weight:800; }
.nl-stat .lbl { font-size:12px; color:#888; margin-top:2px; }
.nl-tbl th { background:#1e3a5f; color:#fff; font-size:12px; padding:10px 12px; white-space:nowrap; }
.nl-tbl td { font-size:13px; padding:9px 12px; vertical-align:middle; }
.nl-tbl tr:hover td { background:#f4f7ff; }
.badge-sent   { background:#d4edda; color:#155724; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:700; }
.badge-failed { background:#f8d7da; color:#721c24; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:700; }
.badge-type   { background:#e2e3e5; color:#383d41; padding:2px 7px; border-radius:10px; font-size:11px; }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-3 col-6 mb-2">
        <div class="nl-stat">
            <div class="num text-primary">{{ number_format($total) }}</div>
            <div class="lbl">Total Notifications</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="nl-stat">
            <div class="num text-success">{{ number_format($sent) }}</div>
            <div class="lbl">Successfully Sent</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="nl-stat">
            <div class="num text-danger">{{ number_format($failed) }}</div>
            <div class="lbl">Failed</div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="nl-stat" style="background:#1e3a5f;">
            <div style="font-size:13px;color:#fff;font-weight:600;margin-bottom:6px;">
                <i class="fas fa-paper-plane me-1"></i> Test Email
            </div>
            <button class="btn btn-warning btn-sm w-100" onclick="openTestModal()">Send Test Email</button>
        </div>
    </div>
</div>

<div class="card" style="border-radius:8px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#fff;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <div>
            <h5 style="margin:0;font-size:17px;font-weight:700;">
                <i class="fas fa-bell me-2"></i>Departure Notification Logs
            </h5>
            <small>Auto-sent 10 hours before flight departure to passenger + agent</small>
        </div>
    </div>

    {{-- Filters --}}
    <div style="padding:14px 20px;background:#f8f9fa;border-bottom:1px solid #dee2e6;">
        <form method="GET" action="{{ route('AdminNotificationLogs') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-size:12px;font-weight:600;">Booking Ref</label>
                <input type="text" name="booking_no" value="{{ request('booking_no') }}"
                    class="form-control form-control-sm" placeholder="OTA-...">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;font-weight:600;">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="passenger_email" {{ request('type')=='passenger_email'?'selected':'' }}>Passenger Email</option>
                    <option value="agent_email"     {{ request('type')=='agent_email'?'selected':'' }}>Agent Email</option>
                    <option value="sms"             {{ request('type')=='sms'?'selected':'' }}>SMS</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;font-weight:600;">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="sent"   {{ request('status')=='sent'?'selected':'' }}>Sent</option>
                    <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:12px;font-weight:600;">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm me-1">Filter</button>
                <a href="{{ route('AdminNotificationLogs') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0 nl-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Booking Ref</th>
                    <th>Route</th>
                    <th>Type</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Error</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $i => $log)
                <tr>
                    <td style="color:#aaa;">{{ $logs->firstItem() + $i }}</td>
                    <td>
                        @if($log->booking)
                            <a href="{{ route('FlightBookingDetails', $log->booking->booking_no) }}"
                               style="color:#0d6efd;font-weight:700;text-decoration:none;">
                                {{ $log->booking->booking_no }}
                            </a>
                        @else
                            <span class="text-muted">ID:{{ $log->flight_booking_id }}</span>
                        @endif
                    </td>
                    <td style="font-weight:700;letter-spacing:1px;font-size:12px;">
                        @if($log->booking)
                            {{ strtoupper($log->booking->departure_location ?? '') }}-{{ strtoupper($log->booking->arrival_location ?? '') }}
                        @else —
                        @endif
                    </td>
                    <td>
                        <span class="badge-type">
                            @if($log->type === 'passenger_email') Passenger Email
                            @elseif($log->type === 'agent_email')  Agent Email
                            @else SMS
                            @endif
                        </span>
                    </td>
                    <td style="font-size:12px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $log->recipient ?? '—' }}
                    </td>
                    <td>
                        @if($log->status === 'sent')
                            <span class="badge-sent">Sent</span>
                        @else
                            <span class="badge-failed">Failed</span>
                        @endif
                    </td>
                    <td style="font-size:11px;color:#dc3545;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                        title="{{ $log->error_message }}">
                        {{ $log->error_message ? \Illuminate\Support\Str::limit($log->error_message, 60) : '—' }}
                    </td>
                    <td style="font-size:12px;color:#888;">
                        {{ $log->created_at ? $log->created_at->format('d-m-Y H:i') : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#aaa;">
                        <i class="fas fa-bell" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3;"></i>
                        No notification logs yet. Emails will appear here once the scheduler sends them.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div style="padding:12px 16px;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

{{-- Test Email Modal --}}
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#1e3a5f;color:#fff;">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Send Test Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted" style="font-size:13px;">
                    Sends a sample departure reminder to the address below using the current Mail Server config.
                    Use this to verify your SMTP settings work.
                </p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Recipient Email</label>
                    <input type="email" id="testEmailAddr" class="form-control"
                           value="{{ Auth::user()->email }}" placeholder="test@example.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Type</label>
                    <select id="testEmailType" class="form-select">
                        <option value="passenger">Passenger Reminder</option>
                        <option value="agent">Agent Alert</option>
                    </select>
                </div>
                <div id="testEmailResult" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning fw-bold" id="btnSendTest" onclick="sendTestEmail()">
                    <i class="fas fa-paper-plane me-1"></i> Send Test
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_js')
<script>
function openTestModal() {
    new bootstrap.Modal(document.getElementById('testEmailModal')).show();
}

function sendTestEmail() {
    const btn    = document.getElementById('btnSendTest');
    const result = document.getElementById('testEmailResult');
    const email  = document.getElementById('testEmailAddr').value.trim();
    const type   = document.getElementById('testEmailType').value;

    if (!email) { alert('Enter an email address.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';
    result.style.display = 'none';

    fetch('{{ route("AdminTestSendEmail") }}', {
        method : 'POST',
        headers: {
            'Content-Type'    : 'application/json',
            'X-CSRF-TOKEN'    : '{{ csrf_token() }}',
            'Accept'          : 'application/json',
        },
        body: JSON.stringify({ email, type }),
    })
    .then(r => r.json())
    .then(data => {
        result.style.display = 'block';
        result.innerHTML = data.success
            ? `<div class="alert alert-success mb-0"><i class="fas fa-check-circle me-1"></i>${data.message}</div>`
            : `<div class="alert alert-danger mb-0"><i class="fas fa-times-circle me-1"></i>${data.message}</div>`;
    })
    .catch(() => {
        result.style.display = 'block';
        result.innerHTML = '<div class="alert alert-danger mb-0">Network error. Try again.</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send Test';
    });
}
</script>
@endsection
