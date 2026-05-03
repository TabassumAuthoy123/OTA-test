@extends('master')
@section('header_css')
<style>
.ldg-header{background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#fff;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;}
.ldg-header h5{margin:0;font-size:18px;font-weight:700;}
.ldg-tbl th{background:#1e3a5f;color:#fff;font-size:12px;padding:9px 12px;white-space:nowrap;}
.ldg-tbl td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.ldg-tbl tr:hover td{background:#f8f9ff;}
.debit-row td{background:#fff5f5;}
.credit-row td{background:#f5fff8;}
.entry-debit{color:#dc3545;font-weight:700;}
.entry-credit{color:#28a745;font-weight:700;}
.sum-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:14px 18px;text-align:center;}
.sum-num{font-size:20px;font-weight:800;}
.sum-lbl{font-size:12px;color:#777;margin-top:4px;}
</style>
@endsection
@section('content')
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:#155724;">৳ {{ number_format($summary['total_paid'],2) }}</div>
      <div class="sum-lbl">Total Paid (Debited from agent)</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:#004085;">৳ {{ number_format($summary['total_receivable'],2) }}</div>
      <div class="sum-lbl">Total Receivable (Credited to agent)</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:{{ $summary['net_balance'] >= 0 ? '#155724' : '#721c24' }};">
        ৳ {{ number_format(abs($summary['net_balance']),2) }}
        {{ $summary['net_balance'] >= 0 ? '(Credit)' : '(Debit)' }}
      </div>
      <div class="sum-lbl">Net Balance</div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card" style="border-radius:8px;overflow:hidden;">
      <div class="ldg-header">
        <div>
          <h5><i class="fas fa-book me-2"></i> Ledger: {{ $agent->name }}</h5>
          <small>B2B-{{ str_pad($agent->id,3,'0',STR_PAD_LEFT) }} &bull; {{ $agent->email }} &bull; Commission: {{ number_format((float)$agent->comission,2) }}%</small>
        </div>
        <a href="{{ route('AdminLedger') }}" class="btn btn-sm btn-light">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>

      {{-- Filter + Manual Entry --}}
      <div style="padding:12px 16px;background:#f8f9fa;border-bottom:1px solid #dee2e6;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;justify-content:space-between;">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-end">
          <div>
            <label style="font-size:11px;font-weight:600;display:block;">From</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;display:block;">To</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
          </div>
          <button class="btn btn-primary btn-sm">Filter</button>
        </form>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addEntryModal">
          <i class="fas fa-plus"></i> Manual Entry
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered mb-0 ldg-tbl">
          <thead>
            <tr>
              <th>#</th><th>Date</th><th>Type</th><th>Description</th>
              <th>Reference</th><th>Booking</th><th>Debit (৳)</th><th>Credit (৳)</th><th>Balance After (৳)</th>
            </tr>
          </thead>
          <tbody>
            @php $sl = $entries->firstItem() ?? 1; @endphp
            @forelse($entries as $e)
            <tr class="{{ $e->entry_type }}-row">
              <td>{{ $sl++ }}</td>
              <td>{{ date('d-m-Y H:i', strtotime($e->created_at)) }}</td>
              <td>
                @if($e->entry_type === 'debit')
                  <span class="entry-debit"><i class="fas fa-arrow-down"></i> DEBIT</span>
                @else
                  <span class="entry-credit"><i class="fas fa-arrow-up"></i> CREDIT</span>
                @endif
              </td>
              <td>{{ $e->description }}</td>
              <td style="font-size:12px;color:#888;">{{ $e->reference_no ?? '—' }}</td>
              <td>
                @if($e->booking)
                  <a href="{{ url('flight/booking/details/' . $e->booking->booking_no) }}" style="font-size:12px;">
                    {{ $e->booking->booking_no }}
                  </a>
                @else—@endif
              </td>
              <td class="entry-debit">{{ $e->entry_type==='debit' ? number_format($e->amount,2) : '' }}</td>
              <td class="entry-credit">{{ $e->entry_type==='credit' ? number_format($e->amount,2) : '' }}</td>
              <td style="font-weight:600;">{{ number_format($e->balance_after,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">No ledger entries found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-3 py-2">{{ $entries->links() }}</div>
    </div>
  </div>
</div>

{{-- Manual Entry Modal --}}
<div class="modal fade" id="addEntryModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('AdminLedgerEntry') }}">
      @csrf
      <input type="hidden" name="party_id" value="{{ $agent->id }}">
      <div class="modal-content">
        <div class="modal-header" style="background:#1e3a5f;color:#fff;">
          <h5 class="modal-title">Manual Ledger Entry — {{ $agent->name }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Entry Type <span class="text-danger">*</span></label>
            <select name="entry_type" class="form-select" required>
              <option value="credit">Credit (Add money / receivable)</option>
              <option value="debit">Debit (Charge / payable)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount (৳) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <input type="text" name="description" class="form-control" required placeholder="Reason for this entry">
          </div>
          <div class="mb-3">
            <label class="form-label">Reference No.</label>
            <input type="text" name="reference_no" class="form-control" placeholder="Transaction ID / Cheque no.">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Entry</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
