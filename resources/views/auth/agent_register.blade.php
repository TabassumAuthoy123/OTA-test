<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Agency Registration - Agent Portal</title>
<link rel="shortcut icon" href="{{ url('assets') }}/img/favicon.svg">
<link href="{{ url('assets') }}/admin-assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ url('assets') }}/nanopkg-assets/vendor/fontawesome-free-6.3.0-web/css/all.min.css" rel="stylesheet">
<link href="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{
  min-height:100vh;
  background:linear-gradient(135deg,#0a1628 0%,#0f2d5a 35%,#1565a0 65%,#0d7a8a 100%);
  font-family:'Segoe UI',sans-serif;
  position:relative;padding:24px 16px;
}
body::before{
  content:'';position:fixed;inset:0;
  background:url("{{ url('assets/img/bg_search.jpg') }}") center/cover no-repeat;
  opacity:.15;z-index:0;
}
.page-wrap{position:relative;z-index:1;max-width:680px;margin:0 auto;}
.card-box{background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.4);overflow:hidden;}
.card-header-custom{
  background:linear-gradient(135deg,#0f1f3d,#1565a0);
  padding:24px 32px;text-align:center;color:#fff;
}
.card-header-custom img{height:42px;object-fit:contain;margin-bottom:10px;}
.card-header-custom h4{font-size:20px;font-weight:700;margin:0;}
.card-header-custom p{font-size:13px;opacity:.8;margin:4px 0 0;}
.card-body-custom{padding:28px 32px;}
.section-title{
  font-size:12px;font-weight:700;color:#1565a0;letter-spacing:.8px;
  text-transform:uppercase;margin:20px 0 12px;
  padding-bottom:6px;border-bottom:1px solid #e8f0fe;
}
.section-title:first-child{margin-top:0;}
.form-row{display:grid;gap:14px;margin-bottom:0;}
.form-row.cols-2{grid-template-columns:1fr 1fr;}
.field-wrap{position:relative;margin-bottom:14px;}
.field-wrap label{display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:5px;}
.field-wrap .fa-icon{position:absolute;left:13px;top:calc(50% + 9px);transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none;}
.field-wrap input[type="text"],
.field-wrap input[type="email"],
.field-wrap input[type="password"],
.field-wrap input[type="tel"],
.field-wrap textarea{
  width:100%;padding:10px 13px 10px 38px;
  border:1.5px solid #e0e0e0;border-radius:8px;
  font-size:13px;color:#333;transition:border .2s;outline:none;background:#fafafa;
}
.field-wrap input[type="file"]{
  width:100%;padding:8px 12px;
  border:1.5px dashed #ccc;border-radius:8px;
  font-size:12px;color:#555;background:#fafafa;cursor:pointer;
  padding-left:12px;
}
.field-wrap input[type="file"]:hover{border-color:#1565a0;}
.field-wrap textarea{padding:10px 13px;resize:vertical;min-height:70px;}
.field-wrap input:focus,.field-wrap textarea:focus{border-color:#1565a0;background:#fff;}
.field-wrap .toggle-pw{position:absolute;right:12px;top:calc(50% + 9px);transform:translateY(-50%);cursor:pointer;color:#aaa;font-size:13px;}
.hint{font-size:11px;color:#aaa;margin-top:3px;}
.file-label{
  display:block;font-size:12px;font-weight:600;color:#555;margin-bottom:5px;
}
.file-note{font-size:11px;color:#1565a0;margin-bottom:6px;}
.agree-wrap{display:flex;align-items:flex-start;gap:10px;margin:18px 0;}
.agree-wrap input[type="checkbox"]{width:16px;height:16px;margin-top:2px;flex-shrink:0;cursor:pointer;accent-color:#1565a0;}
.agree-wrap label{font-size:13px;color:#555;cursor:pointer;line-height:1.5;}
.agree-wrap a{color:#1565a0;text-decoration:none;}
.btn-register{
  width:100%;padding:13px;
  background:linear-gradient(135deg,#0f1f3d,#1565a0);
  color:#fff;border:none;border-radius:8px;
  font-size:15px;font-weight:700;cursor:pointer;transition:opacity .2s;
}
.btn-register:hover{opacity:.88;}
.login-link{text-align:center;margin-top:16px;font-size:13px;color:#666;}
.login-link a{color:#1565a0;font-weight:600;text-decoration:none;}
.login-link a:hover{text-decoration:underline;}
.error-text{color:#dc3545;font-size:11px;margin-top:3px;}
.alert-success-msg{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;}
@media(max-width:540px){.form-row.cols-2{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="page-wrap">
  <div class="card-box">

    <div class="card-header-custom">
      @php $cp = DB::table('company_profiles')->where('id',1)->first(); @endphp
      @if($cp && $cp->logo && file_exists(public_path($cp->logo)))
        <img src="{{ url($cp->logo) }}" alt="Logo"><br>
      @endif
      <h4>Agency Registration</h4>
      <p>Submit your details — we'll review and activate your account</p>
    </div>

    <div class="card-body-custom">

      @if(session('success'))
        <div class="alert-success-msg"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('agent.register.submit') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf

        {{-- Agency Info --}}
        <div class="section-title"><i class="fas fa-building me-1"></i> Agency Information</div>
        <div class="form-row cols-2">
          <div class="field-wrap">
            <label>Company / Agency Name <span style="color:red">*</span></label>
            <i class="fas fa-building fa-icon"></i>
            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Sunrise Travels Ltd." required>
            @error('company_name')<div class="error-text">{{ $message }}</div>@enderror
          </div>
          <div class="field-wrap">
            <label>Contact Person Name <span style="color:red">*</span></label>
            <i class="fas fa-user fa-icon"></i>
            <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Full name" required>
            @error('contact_name')<div class="error-text">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-row cols-2">
          <div class="field-wrap">
            <label>Email Address <span style="color:red">*</span></label>
            <i class="fas fa-envelope fa-icon"></i>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="office@youragency.com" required autocomplete="off">
            @error('email')<div class="error-text">{{ $message }}</div>@enderror
          </div>
          <div class="field-wrap">
            <label>Phone Number <span style="color:red">*</span></label>
            <i class="fas fa-phone fa-icon"></i>
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+880 17..." required>
            @error('phone')<div class="error-text">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="field-wrap">
          <label>Office Address</label>
          <textarea name="address" placeholder="Full office address...">{{ old('address') }}</textarea>
          @error('address')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        {{-- Account --}}
        <div class="section-title"><i class="fas fa-key me-1"></i> Account Credentials</div>
        <div class="form-row cols-2">
          <div class="field-wrap">
            <label>Password <span style="color:red">*</span></label>
            <i class="fas fa-lock fa-icon"></i>
            <input type="password" id="pw1" name="password" placeholder="Min. 8 characters" required autocomplete="new-password">
            <span class="toggle-pw" onclick="togglePw('pw1','ic1')"><i class="fas fa-eye" id="ic1"></i></span>
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
          </div>
          <div class="field-wrap">
            <label>Confirm Password <span style="color:red">*</span></label>
            <i class="fas fa-lock fa-icon"></i>
            <input type="password" id="pw2" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
            <span class="toggle-pw" onclick="togglePw('pw2','ic2')"><i class="fas fa-eye" id="ic2"></i></span>
          </div>
        </div>

        {{-- Photos --}}
        <div class="section-title"><i class="fas fa-image me-1"></i> Profile &amp; Branding</div>
        <div class="form-row cols-2">
          <div class="field-wrap">
            <label class="file-label">Contact Person Photo</label>
            <div class="hint">JPG/PNG, max 2MB</div>
            <input type="file" name="user_photo" accept="image/*">
            @error('user_photo')<div class="error-text">{{ $message }}</div>@enderror
          </div>
          <div class="field-wrap">
            <label class="file-label">Agency Logo</label>
            <div class="hint">JPG/PNG, max 2MB</div>
            <input type="file" name="agency_logo" accept="image/*">
            @error('agency_logo')<div class="error-text">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Documents --}}
        <div class="section-title"><i class="fas fa-file-alt me-1"></i> Required Documents</div>
        <p class="hint" style="margin-bottom:14px;">Upload scanned copies (JPG/PNG/PDF, max 4MB each)</p>

        <div class="field-wrap">
          <label class="file-label">Trade License <span style="color:red">*</span></label>
          <input type="file" name="trade_license" accept=".jpg,.jpeg,.png,.pdf">
          @error('trade_license')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field-wrap">
          <label class="file-label">National ID (NID) of Proprietor</label>
          <input type="file" name="nid_document" accept=".jpg,.jpeg,.png,.pdf">
          @error('nid_document')<div class="error-text">{{ $message }}</div>@enderror
        </div>
        <div class="field-wrap">
          <label class="file-label">Civil Aviation Certificate (if applicable)</label>
          <input type="file" name="civil_aviation" accept=".jpg,.jpeg,.png,.pdf">
          @error('civil_aviation')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        {{-- Agree --}}
        <div class="agree-wrap">
          <input type="checkbox" id="agree" name="agree" required>
          <label for="agree">
            I agree to the <a href="#">Terms &amp; Conditions</a> and confirm that all information provided is accurate and genuine.
          </label>
        </div>

        <button type="submit" class="btn-register">
          <i class="fas fa-paper-plane me-2"></i>Submit Registration
        </button>
      </form>

      <div class="login-link">
        Already have an account? <a href="{{ url('agent/login') }}">Sign In</a>
      </div>

    </div>
  </div>
</div>

<script src="{{ url('assets') }}/admin-assets/vendor/jQuery/jquery.min.js"></script>
<script src="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>
<script>
function togglePw(id,iconId){
  var inp=document.getElementById(id);
  var ico=document.getElementById(iconId);
  if(inp.type==='password'){inp.type='text';ico.classList.replace('fa-eye','fa-eye-slash');}
  else{inp.type='password';ico.classList.replace('fa-eye-slash','fa-eye');}
}
</script>
</body>
</html>
