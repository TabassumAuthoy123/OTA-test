@extends('master')
@section('header_css')
<style>
.ldg-header{display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-bottom:1px solid #e9ecef;}
.ldg-title{font-size:15px;font-weight:700;color:#0f1f3d;margin:0;}
.ldg-tbl{width:100%;border-collapse:collapse;font-size:13px;}
.ldg-tbl thead th{background:#0f1f3d;color:#fff;padding:10px 14px;white-space:nowrap;font-weight:600;font-size:12px;}
.ldg-tbl tbody td{padding:9px 14px;border-bottom:1px solid #f0f0f0;vertical-align:middle;color:#333;}
.ldg-tbl tbody tr:hover td{background:#f4f7ff;}
.debit-row td{background:#fff5f5!important;}
.credit-row td{background:#f5fff8!important;}
.entry-debit{color:#dc3545;font-weight:700;}
.entry-credit{color:#28a745;font-weight:700;}
.sum-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:14px 18px;text-align:center;}
.sum-num{font-size:20px;font-weight:800;color:#0f1f3d;}
.sum-lbl{font-size:12px;color:#777;margin-top:4px;}
</style>
@endsection
@section('content')
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:#155724;">৳ {{ number_format($summary['total_receivable'],2) }}</div>
      <div class="sum-lbl">Total Credited to Account</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:#721c24;">৳ {{ number_format($summary['total_paid'],2) }}</div>
      <div class="sum-lbl">Total Paid / Debited</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="sum-card">
      <div class="sum-num" style="color:{{ $summary['net_balance'] >= 0 ? '#155724' : '#721c24' }};">
        ৳ {{ number_format(abs($summary['net_balance']),2) }}
      </div>
      <div class="sum-lbl">Net Balance {{ $summary['net_balance'] >= 0 ? '(Credit)' : '(Debit)' }}</div>
    </div>
  </div>
</div>

<div class="row"><div class="col-12">
  <div class="card" style="border-radius:8px;overflow:hidden;border:1px solid #e0e0e0;">
    <div class="ldg-header">
      <span class="ldg-title"><i class="fas fa-book me-2" style="color:#0f1f3d;"></i> My Account Ledger</span>
      <form method="GET" action="{{ route('MyLedger') }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm" style="width:140px;">
        <span style="color:#888;">→</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm" style="width:140px;">
        <button type="submit" class="btn btn-sm" style="background:#0f1f3d;color:#fff;"><i class="fas fa-search"></i></button>
        @if(request('start_date')||request('end_date'))
          <a href="{{ route('MyLedger') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
        @endif
      </form>
    </div>

    <div style="overflow-x:auto;">
      <table class="ldg-tbl">
        <thead>
          <tr>
            <th>SL</th><th>Date &amp; Time</th><th>Type</th><th>Description</th>
            <th>Reference</th><th>Debit (৳)</th><th>Credit (৳)</th><th>Balance (৳)</th>
          </tr>
        </thead>
        <tbody>
          @php $sl = $entries->firstItem() ?? 1; @endphp
          @forelse($entries as $e)
          <tr class="{{ $e->entry_type }}-row">
            <td style="color:#888;">{{ $sl++ }}</td>
            <td>{{ date('d-m-Y h:i A', strtotime($e->created_at)) }}</td>
            <td>
              @if($e->entry_type==='debit')
                <span class="entry-debit"><i class="fas fa-arrow-down"></i> Debit</span>
              @else
                <span class="entry-credit"><i class="fas fa-arrow-up"></i> Credit</span>
              @endif
            </td>
            <td>{{ $e->description }}</td>
            <td style="font-size:12px;color:#888;">{{ $e->reference_no ?? '—' }}</td>
            <td class="entry-debit">{{ $e->entry_type==='debit' ? number_format($e->amount,2) : '' }}</td>
            <td class="entry-credit">{{ $e->entry_type==='credit' ? number_format($e->amount,2) : '' }}</td>
            <td style="font-weight:700;">{{ number_format($e->balance_after,2) }}</td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.2;"></i>
            No ledger entries yet.
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px;">
      <small class="text-muted">Showing {{ $entries->firstItem() ?? 0 }}–{{ $entries->lastItem() ?? 0 }} of {{ $entries->total() }}</small>
      {{ $entries->links() }}
    </div>
  </div>
</div></div>
@endsection
