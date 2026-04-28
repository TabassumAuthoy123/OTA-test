@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.info-card{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:20px;}
.info-row{display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:10px 0;font-size:14px;}
.info-row:last-child{border-bottom:none;}
.badge-enabled{background:#d4edda;color:#155724;padding:3px 12px;border-radius:10px;font-size:12px;font-weight:700;}
.badge-disabled{background:#f8d7da;color:#721c24;padding:3px 12px;border-radius:10px;font-size:12px;font-weight:700;}
.nav-tabs .nav-link{color:#1a5276;font-weight:600;}
.nav-tabs .nav-link.active{color:#1a5276;border-bottom:3px solid #1a5276;background:none;}
.tx-type-earn{background:#d4edda;color:#155724;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.tx-type-redeem{background:#cce5ff;color:#004085;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.tx-type-expire{background:#f8d7da;color:#721c24;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.tx-type-adjust{background:#fff3cd;color:#856404;padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;display:inline-block;}
.table thead th{background:#2471a3;color:#fff;border:none;font-size:.82rem;padding:10px 12px;white-space:nowrap;}
.table tbody tr:hover{background:#eaf4fb;}
.filter-bar{background:#f8f9fa;padding:12px 16px;border-bottom:1px solid #dee2e6;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <h5><i class="fas fa-coins me-2"></i>Coin Configuration</h5>
      <small style="opacity:.8;">Dashboard &rsaquo; B2C Configuration &rsaquo; Coin-configuration</small>
    </div>
    <div class="card-body">
      <ul class="nav nav-tabs mb-4" id="coinTabs">
        <li class="nav-item">
          <a class="nav-link {{ request('tab','redeem') === 'redeem' ? 'active' : '' }}"
             href="{{ url()->current() }}?tab=redeem">
            <i class="fas fa-redo me-1"></i>Redeem Rule
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request('tab') === 'transactions' ? 'active' : '' }}"
             href="{{ url()->current() }}?tab=transactions">
            <i class="fas fa-exchange-alt me-1"></i>Transactions
            @if($transactions->total())
              <span class="badge bg-secondary ms-1">{{ $transactions->total() }}</span>
            @endif
          </a>
        </li>
      </ul>

      {{-- ═══ REDEEM RULE TAB ═══ --}}
      @if(request('tab','redeem') === 'redeem')
      <div class="row">
        <div class="col-md-6">
          <form method="POST" action="{{ route('B2cSaveCoinConfig') }}">
            @csrf
            <h6 class="fw-bold mb-3">Update Redeem Rule</h6>

            <div class="mb-3">
              <label class="form-label fw-bold text-danger">* Taka Spend per Coin</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-taka-sign"></i>৳</span>
                <input type="number" name="taka_per_coin" class="form-control" step="0.01" min="0"
                       value="{{ $config ? $config->taka_per_coin : 500 }}" required>
              </div>
              <small class="text-muted">Amount of Taka to spend to earn 1 coin</small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold text-danger">* Per Coin Value (Taka)</label>
              <div class="input-group">
                <span class="input-group-text">৳</span>
                <input type="number" name="coin_value" class="form-control" step="0.01" min="0"
                       value="{{ $config ? $config->coin_value : 1 }}" required>
              </div>
              <small class="text-muted">1 coin = how many Taka when redeeming</small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold text-danger">* Minimum Redeem Coins</label>
              <input type="number" name="min_redeem_coins" class="form-control" step="0.01" min="0"
                     value="{{ $config ? $config->min_redeem_coins : 50 }}" required>
              <small class="text-muted">Minimum coins required to redeem</small>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold text-danger">* Max Redeem Percent</label>
              <div class="input-group">
                <input type="number" name="max_redeem_percent" class="form-control" step="0.01" min="0" max="100"
                       value="{{ $config ? $config->max_redeem_percent : 50 }}" required>
                <span class="input-group-text">%</span>
              </div>
              <small class="text-muted">Max % of booking amount payable via coins</small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Coin System Status</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="coinSwitch"
                       {{ (!$config || $config->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="coinSwitch">Enable Coin System</label>
              </div>
            </div>

            <button type="submit" class="btn btn-warning text-white fw-bold px-4">
              <i class="fas fa-save me-1"></i>Save Rule
            </button>
          </form>
        </div>

        <div class="col-md-6">
          <div class="info-card">
            <h6 class="fw-bold mb-3">Current Rule</h6>
            <div class="info-row">
              <span class="text-muted">Coin System</span>
              <span>
                @if($config && $config->is_active)
                  <span class="badge-enabled">Enabled</span>
                @else
                  <span class="badge-disabled">Disabled</span>
                @endif
              </span>
            </div>
            <div class="info-row">
              <span class="text-muted">Taka Spend per Coin</span>
              <span class="fw-bold">৳{{ $config ? number_format($config->taka_per_coin, 2) : '500.00' }}</span>
            </div>
            <div class="info-row">
              <span class="text-muted">Per Coin Value</span>
              <span class="fw-bold">৳{{ $config ? number_format($config->coin_value, 2) : '1.00' }}</span>
            </div>
            <div class="info-row">
              <span class="text-muted">Min Redeem Coins</span>
              <span class="fw-bold">{{ $config ? number_format($config->min_redeem_coins, 2) : '50.00' }} coins</span>
            </div>
            <div class="info-row">
              <span class="text-muted">Max Redeem Percent</span>
              <span class="fw-bold">{{ $config ? number_format($config->max_redeem_percent, 2) : '50.00' }}%</span>
            </div>
            @if($config)
            <div class="info-row">
              <span class="text-muted">Last Updated</span>
              <span>{{ date('d-m-Y h:i A', strtotime($config->updated_at)) }}</span>
            </div>
            <div class="info-row">
              <span class="text-muted">Updated By</span>
              <span>{{ $config->updated_by ?? '—' }}</span>
            </div>
            @endif
            @if($config)
            <div class="mt-3 p-3 rounded" style="background:#eaf4ff;border:1px solid #bee3f8;font-size:13px;">
              <strong>Example:</strong> Spend ৳{{ number_format($config->taka_per_coin,0) }} → earn 1 coin &bull;
              Redeem {{ number_format(1/$config->coin_value,0) }} coins → ৳1
            </div>
            @endif
          </div>
        </div>
      </div>
      @endif

      {{-- ═══ TRANSACTIONS TAB ═══ --}}
      @if(request('tab') === 'transactions')
      <div class="filter-bar mb-3 rounded">
        <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
          <input type="hidden" name="tab" value="transactions">
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">Type</label>
            <select name="tx_type" class="form-select form-select-sm" style="min-width:130px;">
              <option value="all" {{ request('tx_type','all') === 'all' ? 'selected' : '' }}>All Types</option>
              <option value="earn" {{ request('tx_type') === 'earn' ? 'selected' : '' }}>Earn</option>
              <option value="redeem" {{ request('tx_type') === 'redeem' ? 'selected' : '' }}>Redeem</option>
              <option value="expire" {{ request('tx_type') === 'expire' ? 'selected' : '' }}>Expire</option>
              <option value="adjust" {{ request('tx_type') === 'adjust' ? 'selected' : '' }}>Adjust</option>
            </select>
          </div>
          <div class="col-auto">
            <label class="form-label mb-1 small fw-semibold">Search</label>
            <input type="text" name="tx_search" value="{{ request('tx_search') }}" class="form-control form-control-sm"
                   placeholder="User / Note / Ref..." style="min-width:220px;">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
            <a href="{{ url()->current() }}?tab=transactions" class="btn btn-secondary btn-sm ms-1">Clear</a>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Type</th>
              <th>Coins</th>
              <th>Note</th>
              <th>Reference</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $tx)
            <tr>
              <td>{{ $tx->id }}</td>
              <td>
                @if($tx->user_name)
                  <div class="fw-semibold" style="font-size:13px;">{{ $tx->user_name }}</div>
                  <div class="text-muted" style="font-size:11px;">{{ $tx->user_email }}</div>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>
                @php
                  $cls = match($tx->type) {
                    'earn'   => 'tx-type-earn',
                    'redeem' => 'tx-type-redeem',
                    'expire' => 'tx-type-expire',
                    default  => 'tx-type-adjust',
                  };
                @endphp
                <span class="{{ $cls }}">{{ $tx->type }}</span>
              </td>
              <td class="fw-bold {{ $tx->type === 'earn' || $tx->type === 'adjust' ? 'text-success' : 'text-danger' }}">
                {{ $tx->type === 'earn' || $tx->type === 'adjust' ? '+' : '-' }}{{ number_format($tx->coins, 2) }}
              </td>
              <td style="font-size:13px;">{{ $tx->note ?? '—' }}</td>
              <td style="font-size:12px;color:#888;">{{ $tx->reference ?? '—' }}</td>
              <td style="font-size:12px;">{{ $tx->created_at ? date('d-m-Y H:i', strtotime($tx->created_at)) : '—' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">
                <i class="fas fa-coins fa-2x mb-2 d-block" style="opacity:.3;"></i>
                No coin transactions yet.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($transactions->hasPages())
      <div class="d-flex justify-content-between align-items-center px-2 py-2 mt-2">
        <small class="text-muted">
          Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
        </small>
        {{ $transactions->appends(request()->except('page'))->links() }}
      </div>
      @endif
      @endif

    </div>
  </div>
</div></div>
@endsection
