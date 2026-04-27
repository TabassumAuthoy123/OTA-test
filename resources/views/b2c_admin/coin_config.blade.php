@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.info-card{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:20px;}
.info-row{display:flex;justify-content:space-between;border-bottom:1px solid #eee;padding:8px 0;font-size:14px;}
.badge-enabled{background:#d4edda;color:#155724;padding:3px 10px;border-radius:10px;font-size:12px;font-weight:700;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header"><h5>Coin Configuration</h5><small>Dashboard &rsaquo; Configuration &rsaquo; Coin-configuration</small></div>
    <div class="card-body">
      <ul class="nav nav-tabs mb-4" id="coinTabs">
        <li class="nav-item"><a class="nav-link active" href="#redeemTab" data-bs-toggle="tab">Redeem Rule</a></li>
        <li class="nav-item"><a class="nav-link" href="#transTab" data-bs-toggle="tab">Transactions</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="redeemTab">
          <div class="row">
            <div class="col-md-6">
              <form method="POST" action="{{ url('b2c/config/coin-config') }}">@csrf
                <h6 class="fw-bold mb-3">Update Redeem Rule</h6>
                <div class="mb-3"><label class="form-label fw-bold text-danger">* Taka Spend per Coin</label><input type="number" name="taka_per_coin" class="form-control" step="0.01" value="{{ $config ? $config->taka_per_coin : 500 }}" required></div>
                <div class="mb-3"><label class="form-label fw-bold text-danger">* Per Coin Value</label><input type="number" name="coin_value" class="form-control" step="0.01" value="{{ $config ? $config->coin_value : 1 }}" required></div>
                <div class="mb-3"><label class="form-label fw-bold text-danger">* Minimum Redeem Coins</label><input type="number" name="min_redeem_coins" class="form-control" step="0.01" value="{{ $config ? $config->min_redeem_coins : 50 }}" required></div>
                <div class="mb-3"><label class="form-label fw-bold text-danger">* Max Redeem Percent</label><div class="input-group"><input type="number" name="max_redeem_percent" class="form-control" step="0.01" value="{{ $config ? $config->max_redeem_percent : 50 }}" required><span class="input-group-text">%</span></div></div>
                <div class="mb-3"><label class="form-label fw-bold">Coin System</label><br><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" role="switch" {{ (!$config || $config->is_active) ? 'checked' : '' }}></div></div>
                <button type="submit" class="btn btn-warning text-white fw-bold">Save</button>
              </form>
            </div>
            <div class="col-md-6">
              <div class="info-card">
                <h6 class="fw-bold mb-3">Current Rule</h6>
                <div class="info-row"><span>Coin System</span><span><span class="badge-enabled">{{ ($config && $config->is_active) ? 'Enabled' : 'Disabled' }}</span></span></div>
                <div class="info-row"><span>Taka Spend per Coin</span><span>{{ $config ? number_format($config->taka_per_coin,2) : '500.00' }}</span></div>
                <div class="info-row"><span>Per Coin Value</span><span>{{ $config ? number_format($config->coin_value,2) : '1.00' }}</span></div>
                <div class="info-row"><span>Minimum Redeem Coins</span><span>{{ $config ? number_format($config->min_redeem_coins,2) : '50.00' }}</span></div>
                <div class="info-row"><span>Max Redeem Percent</span><span>{{ $config ? number_format($config->max_redeem_percent,2).'%' : '50.00%' }}</span></div>
                @if($config)
                <div class="info-row"><span>Last Updated</span><span>{{ date('d-m-Y h:i A', strtotime($config->updated_at)) }}</span></div>
                <div class="info-row"><span>Updated By</span><span>{{ $config->updated_by }}</span></div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="transTab">
          <p class="text-muted">No coin transactions yet.</p>
        </div>
      </div>
    </div>
  </div>
</div></div>
@endsection
