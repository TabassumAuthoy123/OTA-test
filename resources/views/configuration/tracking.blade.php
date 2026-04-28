@extends('master')
@section('header_css')
<style>
.trk-wrap{background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);}
.trk-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;}
.trk-header h5{margin:0;font-size:18px;font-weight:700;}
.trk-header small{opacity:.85;}
.trk-body{display:flex;min-height:480px;}

/* Left tab list */
.trk-tabs{width:220px;min-width:220px;background:#f8f9fa;border-right:1px solid #e0e0e0;padding:0;}
.trk-tab-item{display:block;padding:16px 20px;font-size:14px;font-weight:500;color:#444;border:none;background:none;text-align:left;width:100%;cursor:pointer;border-bottom:1px solid #e9ecef;transition:background .15s,color .15s;text-decoration:none;}
.trk-tab-item:hover{background:#e9ecef;color:#1a5276;}
.trk-tab-item.active{background:#fff;color:#1a5276;font-weight:700;border-left:3px solid #1a5276;padding-left:17px;}
.trk-tab-item .trk-icon{margin-right:8px;font-size:16px;}

/* Right form panel */
.trk-panel{flex:1;padding:32px 40px;}
.trk-panel h6{font-size:16px;font-weight:700;color:#1a5276;margin-bottom:24px;border-bottom:1px solid #e9ecef;padding-bottom:10px;}
.trk-form-group{margin-bottom:20px;}
.trk-form-group label{display:block;font-size:13px;font-weight:600;color:#333;margin-bottom:6px;}
.trk-form-group label .req{color:#e74c3c;}
.trk-form-group input,.trk-form-group select{width:100%;max-width:480px;border:1px solid #ced4da;border-radius:6px;padding:9px 14px;font-size:14px;color:#333;outline:none;transition:border-color .2s;}
.trk-form-group input:focus,.trk-form-group select:focus{border-color:#2471a3;box-shadow:0 0 0 3px rgba(36,113,163,.12);}
.trk-form-group select{background:#fff;}
.btn-trk-update{background:#f0a500;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .2s;}
.btn-trk-update:hover{background:#d4911a;}
.trk-alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;border-radius:6px;padding:10px 16px;margin-bottom:20px;font-size:13px;}
.trk-breadcrumb{font-size:12px;color:#888;margin-bottom:20px;}
.trk-breadcrumb a{color:#2471a3;text-decoration:none;}
.trk-breadcrumb span{margin:0 5px;}
</style>
@endsection

@section('content')
<div class="row"><div class="col-lg-12">
  <div class="trk-wrap">

    <div class="trk-header">
      <h5><i class="typcn typcn-chart-bar-outline me-2"></i> Tracking your user</h5>
      <small>Dashboard &rsaquo; Configuration &rsaquo; Tracking</small>
    </div>

    <div class="trk-body">

      {{-- ── Left Tab List ── --}}
      <div class="trk-tabs">
        @php
          $tabs = [
            'google_recaptcha'   => ['label' => 'Google ReCaptcha',    'icon' => 'typcn-lock-closed-outline'],
            'google_tag_manager' => ['label' => 'Google Tag Manager',  'icon' => 'typcn-tags'],
            'google_analytics'   => ['label' => 'Google Analytics',    'icon' => 'typcn-chart-area-outline'],
            'facebook_pixel'     => ['label' => 'Facebook Pixel',      'icon' => 'typcn-social-at'],
          ];
        @endphp
        @foreach($tabs as $tabKey => $tabInfo)
          <a href="{{ url('configuration/tracking') }}?tab={{ $tabKey }}"
             class="trk-tab-item {{ $activeTab === $tabKey ? 'active' : '' }}">
            <i class="typcn {{ $tabInfo['icon'] }} trk-icon"></i>{{ $tabInfo['label'] }}
          </a>
        @endforeach
      </div>

      {{-- ── Right Form Panel ── --}}
      <div class="trk-panel">

        @if(session('success'))
          <div class="trk-alert-success">&#10003; {{ session('success') }}</div>
        @endif

        @php
          $cfg = $configs[$activeTab] ?? null;
          $tabLabels = [
            'google_recaptcha'   => 'Google ReCaptcha',
            'google_tag_manager' => 'Google Tag Manager',
            'google_analytics'   => 'Google Analytics',
            'facebook_pixel'     => 'Facebook Pixel',
          ];
        @endphp

        <h6>{{ $tabLabels[$activeTab] }}</h6>

        <form method="POST" action="{{ url('configuration/tracking/' . $activeTab . '/update') }}">
          @csrf

          {{-- Tracking Name --}}
          <div class="trk-form-group">
            <label>Tracking Name</label>
            <input type="text" name="name" value="{{ old('name', $cfg->name ?? $tabLabels[$activeTab]) }}" placeholder="Tracking name...">
          </div>

          {{-- Status --}}
          <div class="trk-form-group">
            <label>Status <span class="req">*</span></label>
            <select name="is_active" style="max-width:480px;">
              <option value="1" {{ ($cfg->is_active ?? 0) == 1 ? 'selected' : '' }}>True</option>
              <option value="0" {{ ($cfg->is_active ?? 0) == 0 ? 'selected' : '' }}>False</option>
            </select>
          </div>

          {{-- ── Service-specific fields ── --}}

          @if($activeTab === 'google_recaptcha')
            <div class="trk-form-group">
              <label>Site Key <span class="req">*</span></label>
              <input type="text" name="tracking_code"
                value="{{ old('tracking_code', $cfg->tracking_code ?? '') }}"
                placeholder="Your Google reCAPTCHA Site Key">
            </div>
            <div class="trk-form-group">
              <label>Secret Key <span class="req">*</span></label>
              <input type="text" name="secret_key"
                value="{{ old('secret_key', $cfg->secret_key ?? '') }}"
                placeholder="Your Google reCAPTCHA Secret Key">
            </div>

          @elseif($activeTab === 'google_tag_manager')
            <div class="trk-form-group">
              <label>Tag Manager ID <span class="req">*</span></label>
              <input type="text" name="tracking_code"
                value="{{ old('tracking_code', $cfg->tracking_code ?? '') }}"
                placeholder="GTM-XXXXXXX">
            </div>

          @elseif($activeTab === 'google_analytics')
            <div class="trk-form-group">
              <label>Google Analytics Code <span class="req">*</span></label>
              <input type="text" name="tracking_code"
                value="{{ old('tracking_code', $cfg->tracking_code ?? '') }}"
                placeholder="Your Google Analytics ID (e.g. G-XXXXXXXXXX)">
            </div>

          @elseif($activeTab === 'facebook_pixel')
            <div class="trk-form-group">
              <label>Facebook Code <span class="req">*</span></label>
              <input type="text" name="tracking_code"
                value="{{ old('tracking_code', $cfg->tracking_code ?? '') }}"
                placeholder="Your Facebook Pixel ID (e.g. 3910932549039920)">
            </div>
          @endif

          <div style="margin-top:28px;">
            <button type="submit" class="btn-trk-update">
              <i class="typcn typcn-arrow-right"></i> Update
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div></div>
@endsection
