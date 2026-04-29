<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Forgot Password - Agent Portal</title>
<link rel="shortcut icon" href="{{ url('assets') }}/img/favicon.svg">
<link href="{{ url('assets') }}/admin-assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ url('assets') }}/nanopkg-assets/vendor/fontawesome-free-6.3.0-web/css/all.min.css" rel="stylesheet">
<link href="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{
  min-height:100vh;
  background:linear-gradient(135deg,#0a1628 0%,#0f2d5a 35%,#1565a0 65%,#0d7a8a 100%);
  display:flex;align-items:center;justify-content:center;
  font-family:'Segoe UI',sans-serif;position:relative;overflow:hidden;
}
body::before{
  content:'';position:absolute;inset:0;
  background:url("{{ url('assets/img/bg_search.jpg') }}") center/cover no-repeat;
  opacity:.18;
}
.card-wrap{position:relative;z-index:1;width:100%;max-width:420px;padding:16px;}
.card-box{background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.4);padding:40px 36px 36px;}
.brand{text-align:center;margin-bottom:24px;}
.brand img{height:44px;object-fit:contain;}
.card-title{font-size:22px;font-weight:700;color:#0f1f3d;margin:14px 0 6px;}
.card-sub{font-size:13px;color:#888;margin-bottom:24px;line-height:1.5;}
.field-wrap{position:relative;margin-bottom:20px;}
.field-wrap .fa-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#999;font-size:14px;}
.field-wrap input{
  width:100%;padding:11px 14px 11px 40px;
  border:1.5px solid #e0e0e0;border-radius:8px;
  font-size:14px;color:#333;transition:border .2s;outline:none;background:#fafafa;
}
.field-wrap input:focus{border-color:#1565a0;background:#fff;}
.btn-submit{
  width:100%;padding:12px;
  background:linear-gradient(135deg,#0f1f3d,#1565a0);
  color:#fff;border:none;border-radius:8px;
  font-size:15px;font-weight:700;cursor:pointer;transition:opacity .2s;
}
.btn-submit:hover{opacity:.88;}
.back-link{text-align:center;margin-top:18px;font-size:13px;}
.back-link a{color:#1565a0;font-weight:600;text-decoration:none;}
.back-link a:hover{text-decoration:underline;}
.alert-success-msg{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
.alert-error-msg{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
</style>
</head>
<body>
<div class="card-wrap">
  <div class="card-box">

    <div class="brand">
      @php $cp = DB::table('company_profiles')->where('id',1)->first(); @endphp
      @if($cp && $cp->logo && file_exists(public_path($cp->logo)))
        <img src="{{ url($cp->logo) }}" alt="Logo">
      @else
        <img src="{{ url('assets/img/logo.svg') }}" alt="Logo">
      @endif
    </div>

    <div class="card-title">Find Your Account</div>
    <div class="card-sub">Enter your registered email address and we'll send you a password reset link.</div>

    @if(session('status'))
      <div class="alert-success-msg"><i class="fas fa-check-circle me-2"></i>{{ session('status') }}</div>
    @endif

    @if($errors->has('email'))
      <div class="alert-error-msg">{{ $errors->first('email') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="field-wrap">
        <i class="fas fa-envelope fa-icon"></i>
        <input type="email" name="email" placeholder="Registered email address"
               value="{{ old('email') }}" required autocomplete="off">
      </div>
      <button type="submit" class="btn-submit">
        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
      </button>
    </form>

    <div class="back-link">
      <a href="{{ url('agent/login') }}"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
    </div>

  </div>
</div>
<script src="{{ url('assets') }}/admin-assets/vendor/jQuery/jquery.min.js"></script>
<script src="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>
</body>
</html>
