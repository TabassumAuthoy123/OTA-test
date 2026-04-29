<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Agent Login</title>
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
  font-family:'Segoe UI',sans-serif;
  position:relative;overflow:hidden;
}
body::before{
  content:'';position:absolute;inset:0;
  background:url("{{ url('assets/img/bg_search.jpg') }}") center/cover no-repeat;
  opacity:.18;
}
.card-wrap{position:relative;z-index:1;width:100%;max-width:420px;padding:16px;}
.card-box{
  background:#fff;border-radius:16px;
  box-shadow:0 20px 60px rgba(0,0,0,.4);
  padding:40px 36px 36px;
}
.brand{text-align:center;margin-bottom:28px;}
.brand img{height:48px;object-fit:contain;}
.brand h4{color:#0f1f3d;font-size:20px;font-weight:700;margin-top:10px;}
.brand p{color:#888;font-size:13px;margin:0;}
.field-wrap{position:relative;margin-bottom:18px;}
.field-wrap .fa-icon{
  position:absolute;left:14px;top:50%;transform:translateY(-50%);
  color:#999;font-size:14px;
}
.field-wrap input{
  width:100%;padding:11px 14px 11px 40px;
  border:1.5px solid #e0e0e0;border-radius:8px;
  font-size:14px;color:#333;transition:border .2s;outline:none;
  background:#fafafa;
}
.field-wrap input:focus{border-color:#1565a0;background:#fff;}
.field-wrap .toggle-pw{
  position:absolute;right:12px;top:50%;transform:translateY(-50%);
  cursor:pointer;color:#aaa;font-size:14px;
}
.forgot-link{text-align:right;margin-bottom:20px;}
.forgot-link a{font-size:12px;color:#1565a0;text-decoration:none;}
.forgot-link a:hover{text-decoration:underline;}
.btn-login{
  width:100%;padding:12px;
  background:linear-gradient(135deg,#0f1f3d,#1565a0);
  color:#fff;border:none;border-radius:8px;
  font-size:15px;font-weight:700;cursor:pointer;
  transition:opacity .2s;
}
.btn-login:hover{opacity:.88;}
.divider{text-align:center;margin:20px 0;color:#bbb;font-size:12px;position:relative;}
.divider::before,.divider::after{content:'';position:absolute;top:50%;width:42%;height:1px;background:#e8e8e8;}
.divider::before{left:0;}.divider::after{right:0;}
.register-link{text-align:center;font-size:13px;color:#666;}
.register-link a{color:#1565a0;font-weight:600;text-decoration:none;}
.register-link a:hover{text-decoration:underline;}
.alert-success-msg{
  background:#d4edda;color:#155724;border:1px solid #c3e6cb;
  padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;
}
.alert-error-msg{
  background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;
  padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;
}
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
      <h4>Agent Portal</h4>
      <p>Sign in to your B2B account</p>
    </div>

    @if(session('success'))
      <div class="alert-success-msg">{{ session('success') }}</div>
    @endif

    @if(session('error') || $errors->has('email') || $errors->has('password'))
      <div class="alert-error-msg">
        @if(session('error')){{ session('error') }}
        @elseif($errors->first('email')){{ $errors->first('email') }}
        @else{{ $errors->first('password') }}
        @endif
      </div>
    @endif

    <form method="POST" action="{{ url('login') }}" autocomplete="off">
      @csrf

      <div class="field-wrap">
        <i class="fas fa-envelope fa-icon"></i>
        <input type="email" name="email" placeholder="Email address"
               value="{{ old('email') }}" required autocomplete="off">
      </div>

      <div class="field-wrap">
        <i class="fas fa-lock fa-icon"></i>
        <input type="password" id="pw" name="password" placeholder="Password"
               required autocomplete="new-password">
        <span class="toggle-pw" onclick="togglePw()">
          <i class="fas fa-eye" id="pwIcon"></i>
        </span>
      </div>

      <div class="forgot-link">
        <a href="{{ url('agent/forgot-password') }}">Forget password?</a>
      </div>

      <button type="submit" class="btn-login">LOGIN</button>
    </form>

    <div class="divider">or</div>

    <div class="register-link">
      New agency? <a href="{{ url('agent/register') }}">Register here</a>
    </div>

  </div>
</div>

<script src="{{ url('assets') }}/admin-assets/vendor/jQuery/jquery.min.js"></script>
<script src="{{ url('assets') }}/nanopkg-assets/vendor/toastr/build/toastr.min.js"></script>
<script>
function togglePw(){
  var inp=document.getElementById('pw');
  var ico=document.getElementById('pwIcon');
  if(inp.type==='password'){inp.type='text';ico.classList.replace('fa-eye','fa-eye-slash');}
  else{inp.type='password';ico.classList.replace('fa-eye-slash','fa-eye');}
}
@if(session('toastr_success'))
  toastr.success("{{ session('toastr_success') }}");
@endif
</script>
</body>
</html>
