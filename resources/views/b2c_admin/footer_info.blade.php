@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:18px 24px;border-radius:8px 8px 0 0;}
.b2c-page-header h4{margin:0;font-weight:700;}
.form-card{border:none;box-shadow:0 2px 10px rgba(0,0,0,.08);}
.section-title{font-weight:700;color:#1a5276;border-bottom:2px solid #2471a3;padding-bottom:6px;margin-bottom:16px;font-size:.95rem;}
.dynamic-row{background:#f8f9fa;border-radius:6px;padding:10px;margin-bottom:8px;}
</style>
@endsection
@section('content')
<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="b2c-page-header mb-0">
        <h4><i class="fas fa-layer-group me-2"></i>B2C Footer Info</h4>
      </div>
      @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
      <div class="card form-card">
        <div class="card-body p-4">
          <form method="POST" action="{{ route('B2cSaveFooterInfo') }}" enctype="multipart/form-data">
            @csrf

            <div class="section-title">Company Info</div>
            <div class="mb-4">
              <textarea name="company_info" class="form-control" rows="3" placeholder="Company description...">{{ $info->company_info ?? '' }}</textarea>
            </div>

            <div class="section-title d-flex align-items-center justify-content-between">
              Social Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('social-rows', socialTpl())">+ Add</button>
            </div>
            <div id="social-rows" class="mb-4">
              @foreach($social as $s)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4"><input type="text" name="social_name[]" class="form-control form-control-sm" placeholder="Name" value="{{ $s['name'] ?? '' }}"></div>
                <div class="col-md-6"><input type="url" name="social_link[]" class="form-control form-control-sm" placeholder="URL" value="{{ $s['link'] ?? '' }}"></div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">x</button></div>
              </div>
              @endforeach
            </div>

            <div class="section-title d-flex align-items-center justify-content-between">
              Company Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('company-rows', companyTpl())">+ Add</button>
            </div>
            <div id="company-rows" class="mb-4">
              @foreach($companyLinks as $c)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4"><input type="text" name="company_link_label[]" class="form-control form-control-sm" placeholder="Label" value="{{ $c['label'] ?? '' }}"></div>
                <div class="col-md-6"><input type="url" name="company_link_url[]" class="form-control form-control-sm" placeholder="URL" value="{{ $c['url'] ?? '' }}"></div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">x</button></div>
              </div>
              @endforeach
            </div>

            <div class="section-title d-flex align-items-center justify-content-between">
              Support Links
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('support-rows', supportTpl())">+ Add</button>
            </div>
            <div id="support-rows" class="mb-4">
              @foreach($supportLinks as $sl)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4"><input type="text" name="support_link_label[]" class="form-control form-control-sm" placeholder="Label" value="{{ $sl['label'] ?? '' }}"></div>
                <div class="col-md-6"><input type="url" name="support_link_url[]" class="form-control form-control-sm" placeholder="URL" value="{{ $sl['url'] ?? '' }}"></div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">x</button></div>
              </div>
              @endforeach
            </div>

            <div class="section-title d-flex align-items-center justify-content-between">
              Payment Methods
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow('payment-rows', paymentTpl())">+ Add</button>
            </div>
            <div id="payment-rows" class="mb-4">
              @foreach($paymentMethods as $pm)
              <div class="dynamic-row row g-2 align-items-center">
                <div class="col-md-4"><input type="text" name="payment_method_name[]" class="form-control form-control-sm" placeholder="Method Name" value="{{ $pm['name'] ?? '' }}"></div>
                <div class="col-md-6"><input type="text" name="payment_method_logo[]" class="form-control form-control-sm" placeholder="Logo URL" value="{{ $pm['logo'] ?? '' }}"></div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.dynamic-row').remove()">x</button></div>
              </div>
              @endforeach
            </div>

            <div class="pt-2">
              <button type="submit" class="btn px-5" style="background:#1a5276;color:#fff;font-weight:600;">Save Footer Info</button>
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
function addRow(containerId, html) {
  document.getElementById(containerId).insertAdjacentHTML('beforeend', html);
}
function removeRow(btn) { btn.closest('.dynamic-row').remove(); }
function socialTpl() {
  return '<div class="dynamic-row row g-2 align-items-center"><div class="col-md-4"><input type="text" name="social_name[]" class="form-control form-control-sm" placeholder="Name"></div><div class="col-md-6"><input type="url" name="social_link[]" class="form-control form-control-sm" placeholder="URL"></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">x</button></div></div>';
}
function companyTpl() {
  return '<div class="dynamic-row row g-2 align-items-center"><div class="col-md-4"><input type="text" name="company_link_label[]" class="form-control form-control-sm" placeholder="Label"></div><div class="col-md-6"><input type="url" name="company_link_url[]" class="form-control form-control-sm" placeholder="URL"></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">x</button></div></div>';
}
function supportTpl() {
  return '<div class="dynamic-row row g-2 align-items-center"><div class="col-md-4"><input type="text" name="support_link_label[]" class="form-control form-control-sm" placeholder="Label"></div><div class="col-md-6"><input type="url" name="support_link_url[]" class="form-control form-control-sm" placeholder="URL"></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">x</button></div></div>';
}
function paymentTpl() {
  return '<div class="dynamic-row row g-2 align-items-center"><div class="col-md-4"><input type="text" name="payment_method_name[]" class="form-control form-control-sm" placeholder="Method Name"></div><div class="col-md-6"><input type="text" name="payment_method_logo[]" class="form-control form-control-sm" placeholder="Logo URL"></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">x</button></div></div>';
}
</script>
@endsection
