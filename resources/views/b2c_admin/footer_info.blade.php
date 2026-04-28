@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.section-title{font-weight:700;color:#1a5276;border-bottom:2px solid #2471a3;padding-bottom:6px;margin-bottom:14px;font-size:.95rem;display:flex;align-items:center;justify-content:space-between;}
.dynamic-row{background:#f8f9fa;border:1px solid #e9ecef;border-radius:6px;padding:10px 12px;margin-bottom:8px;}
.row-thumb{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #dee2e6;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="b2c-page-header mb-0">
        <h4><i class="fas fa-layer-group me-2"></i>B2C Footer Info</h4>
        <small style="opacity:.8;">Dashboard &rsaquo; B2C Configuration &rsaquo; Footer Info</small>
      </div>
      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      <div class="card form-card mt-0">
        <div class="card-body p-4">
          <form method="POST" action="{{ route('B2cSaveFooterInfo') }}" enctype="multipart/form-data">
            @csrf

            {{-- Company Info --}}
            <div class="section-title">Company Info</div>
            <div class="mb-4">
              <textarea name="company_info" class="form-control" rows="3" placeholder="Company description shown in footer...">{{ $info->company_info ?? '' }}</textarea>
            </div>

            {{-- Social Links --}}
            <div class="section-title">
              Social Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSocialRow()">+ Add</button>
            </div>
            <div id="social-rows" class="mb-4">
              @foreach($social as $s)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-3">
                  <input type="text" name="social_name[]" class="form-control form-control-sm" placeholder="Name" value="{{ $s['name'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <input type="url" name="social_link[]" class="form-control form-control-sm" placeholder="URL" value="{{ $s['link'] ?? '' }}">
                </div>
                <div class="col-md-3">
                  @if(!empty($s['logo']))
                    <img src="{{ asset($s['logo']) }}" class="row-thumb me-1">
                  @endif
                  <input type="hidden" name="social_logo_existing[]" value="{{ $s['logo'] ?? '' }}">
                  <input type="file" name="social_logo[]" class="form-control form-control-sm mt-1" accept="image/*">
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">Remove</button>
                </div>
              </div>
              @endforeach
            </div>

            {{-- Company Links --}}
            <div class="section-title">
              Company Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCompanyRow()">+ Add</button>
            </div>
            <div id="company-rows" class="mb-4">
              @foreach($companyLinks as $c)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4">
                  <input type="text" name="company_link_label[]" class="form-control form-control-sm" placeholder="Label" value="{{ $c['label'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <input type="url" name="company_link_url[]" class="form-control form-control-sm" placeholder="URL" value="{{ $c['url'] ?? '' }}">
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">Remove</button>
                </div>
              </div>
              @endforeach
            </div>

            {{-- Support Links --}}
            <div class="section-title">
              Support Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSupportRow()">+ Add</button>
            </div>
            <div id="support-rows" class="mb-4">
              @foreach($supportLinks as $sl)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4">
                  <input type="text" name="support_link_label[]" class="form-control form-control-sm" placeholder="Label" value="{{ $sl['label'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <input type="url" name="support_link_url[]" class="form-control form-control-sm" placeholder="URL" value="{{ $sl['url'] ?? '' }}">
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">Remove</button>
                </div>
              </div>
              @endforeach
            </div>

            {{-- Payment Methods --}}
            <div class="section-title">
              Payment Methods
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPaymentRow()">+ Add</button>
            </div>
            <div id="payment-rows" class="mb-4">
              @foreach($paymentMethods as $pm)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-3">
                  <input type="text" name="payment_method_name[]" class="form-control form-control-sm" placeholder="Method Name" value="{{ $pm['name'] ?? '' }}">
                </div>
                <div class="col-md-3">
                  @if(!empty($pm['logo']))
                    <img src="{{ Str::startsWith($pm['logo'],'http') ? $pm['logo'] : asset($pm['logo']) }}" class="row-thumb me-1">
                  @endif
                  <input type="hidden" name="payment_method_logo_existing[]" value="{{ $pm['logo'] ?? '' }}">
                </div>
                <div class="col-md-4">
                  <label class="form-label mb-0 small text-muted">Change Logo</label>
                  <input type="file" name="payment_method_logo_file[]" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-sm btn-danger mt-3" onclick="this.closest('.dynamic-row').remove()">Remove</button>
                </div>
              </div>
              @endforeach
            </div>

            <div class="pt-2">
              <button type="submit" class="btn btn-warning text-white fw-bold px-5">
                <i class="fas fa-save me-1"></i>Save Footer Info
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer_js')
<script>
function addSocialRow() {
  document.getElementById('social-rows').insertAdjacentHTML('beforeend',
    '<div class="dynamic-row row g-2 align-items-center">' +
    '<div class="col-md-3"><input type="text" name="social_name[]" class="form-control form-control-sm" placeholder="Name"></div>' +
    '<div class="col-md-4"><input type="url" name="social_link[]" class="form-control form-control-sm" placeholder="URL"></div>' +
    '<div class="col-md-3"><input type="hidden" name="social_logo_existing[]" value=""><input type="file" name="social_logo[]" class="form-control form-control-sm" accept="image/*"></div>' +
    '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest(\'.dynamic-row\').remove()">Remove</button></div>' +
    '</div>'
  );
}
function addCompanyRow() {
  document.getElementById('company-rows').insertAdjacentHTML('beforeend',
    '<div class="dynamic-row row g-2 align-items-center">' +
    '<div class="col-md-4"><input type="text" name="company_link_label[]" class="form-control form-control-sm" placeholder="Label"></div>' +
    '<div class="col-md-6"><input type="url" name="company_link_url[]" class="form-control form-control-sm" placeholder="URL"></div>' +
    '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest(\'.dynamic-row\').remove()">Remove</button></div>' +
    '</div>'
  );
}
function addSupportRow() {
  document.getElementById('support-rows').insertAdjacentHTML('beforeend',
    '<div class="dynamic-row row g-2 align-items-center">' +
    '<div class="col-md-4"><input type="text" name="support_link_label[]" class="form-control form-control-sm" placeholder="Label"></div>' +
    '<div class="col-md-6"><input type="url" name="support_link_url[]" class="form-control form-control-sm" placeholder="URL"></div>' +
    '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest(\'.dynamic-row\').remove()">Remove</button></div>' +
    '</div>'
  );
}
function addPaymentRow() {
  document.getElementById('payment-rows').insertAdjacentHTML('beforeend',
    '<div class="dynamic-row row g-2 align-items-center">' +
    '<div class="col-md-3"><input type="text" name="payment_method_name[]" class="form-control form-control-sm" placeholder="Method Name"></div>' +
    '<div class="col-md-3"><input type="hidden" name="payment_method_logo_existing[]" value=""></div>' +
    '<div class="col-md-4"><label class="form-label mb-0 small text-muted">Logo Image</label><input type="file" name="payment_method_logo_file[]" class="form-control form-control-sm" accept="image/*"></div>' +
    '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger mt-3" onclick="this.closest(\'.dynamic-row\').remove()">Remove</button></div>' +
    '</div>'
  );
}
</script>
@endsection
