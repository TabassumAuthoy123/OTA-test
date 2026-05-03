@extends('master')
@section('header_css')
<style>
.ldg-header{background:linear-gradient(135deg,#1e3a5f,#2d5f8a);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.ldg-header h5{margin:0;font-size:18px;font-weight:700;}
.ldg-table th{background:#1e3a5f;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.ldg-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.ldg-table tr:hover td{background:#f0f4ff;}
.bal-pos{color:#155724;font-weight:700;}
.bal-neg{color:#721c24;font-weight:700;}
.stat-card{background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:16px 20px;text-align:center;}
.stat-num{font-size:22px;font-weight:800;color:#0f1f3d;}
.stat-lbl{font-size:12px;color:#777;margin-top:4px;}
</style>
@endsection
@section('content')
<div class="row g-3 mb-3">
  @php
    $allAgents = \App\Models\User::where('user_type',2)->get();
    $totalBal  = $allAgents->sum('balance');
    $totalDebit= \App\Models\LedgerEntry::where('entry_type','debit')->sum('amount');
    $totalCredit=\App\Models\LedgerEntry::where('entry_type','credit')->sum('amount');
  @endphp
  <div class="col-md-3"><div class="stat-card"><div class="stat-num">{{ $allAgents->count() }}</div><div class="stat-lbl">Total B2B Agents</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="stat-num" style="color:#155724;">৳ {{ number_format($totalCredit,2) }}</div><div class="stat-lbl">Total Credited (Receivable)</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="stat-num" style="color:#721c24;">৳ {{ number_format($totalDebit,2) }}</div><div class="stat-lbl">Total Debited (Paid)</div></div></div>
  <div class="col-md-3"><div class="stat-card"><div class="stat-num">৳ {{ number_format($totalBal,2) }}</div><div class="stat-lbl">Total Outstanding Balance</div></div></div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card" style="border-radius:8px;overflow:hidden;">
      <div class="ldg-header">
        <div>
          <h5><i class="fas fa-book me-2"></i> Party-wise Ledger</h5>
          <small>Receivable / Payable / Paid per B2B agent</small>
        </div>
      </div>

      <form method="GET" style="padding:12px 16px;background:#f8f9fa;border-bottom:1px solid #dee2e6;">
        <div class="d-flex gap-2 flex-wrap align-items-end">
          <div>
            <label style="font-size:11px;font-weight:600;display:block;color:#555;">Search Agent</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name / email..." style="width:220px;">
          </div>
          <button class="btn btn-primary btn-sm">Search</button>
          @if(request('search'))<a href="{{ route('AdminLedger') }}" class="btn btn-secondary btn-sm">Clear</a>@endif
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-bordered mb-0 ldg-table">
          <thead>
            <tr>
              <th>SL</th><th>Agent ID</th><th>Agency Name</th><th>Email</th>
              <th>Commission %</th><th>Current Balance</th>
              <th>Total Receivable</th><th>Total Paid</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($agents as $i => $a)
            @php
              $debit  = $a->total_debit  ?? 0;
              $credit = $a->total_credit ?? 0;
            @endphp
            <tr>
              <td>{{ $agents->firstItem() + $i }}</td>
              <td style="color:#aaa;font-size:12px;">B2B-{{ str_pad($a->id,3,'0',STR_PAD_LEFT) }}</td>
              <td style="font-weight:600;">{{ $a->name }}</td>
              <td>{{ $a->email }}</td>
              <td>
                <form method="POST" action="{{ route('SetAgentCommission') }}" class="d-flex gap-1">
                  @csrf
                  <input type="hidden" name="agent_id" value="{{ $a->id }}">
                  <input type="number" name="commission" value="{{ number_format((float)$a->comission,2) }}"
                    step="0.01" min="0" max="100" class="form-control form-control-sm" style="width:80px;" required>
                  <button class="btn btn-sm btn-outline-primary" title="Save">%</button>
                </form>
              </td>
              <td class="{{ $a->balance >= 0 ? 'bal-pos' : 'bal-neg' }}">৳ {{ number_format($a->balance,2) }}</td>
              <td style="color:#155724;font-weight:600;">৳ {{ number_format($credit,2) }}</td>
              <td style="color:#721c24;font-weight:600;">৳ {{ number_format($debit,2) }}</td>
              <td>
                <a href="{{ route('AdminLedgerDetail', $a->id) }}" class="btn btn-sm btn-info text-white">
                  <i class="fas fa-eye"></i> Ledger
                </a>
              </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5 text-muted">No agents found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-3 py-2">{{ $agents->links() }}</div>
    </div>
  </div>
</div>
@endsection
