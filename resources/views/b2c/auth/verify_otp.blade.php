@extends('b2c.layouts.auth')

@section('title', 'Verify OTP')

@section('styles')
<style>
.ft-simple-wrap { width:100%; max-width:480px; }
.ft-simple-card {
    background:#fff; border-radius:16px;
    padding:40px 44px 36px;
    box-shadow:0 8px 40px rgba(0,0,0,.10);
    width:100%;
}
.ft-fp-title {
    font-family:'Poppins',sans-serif;
    font-size:1.4rem; font-weight:800;
    color:#111; margin:0 0 6px; text-align:center;
}
.ft-fp-sub { text-align:center; font-size:.84rem; color:#999; margin:0 0 8px; }
.ft-fp-email-badge {
    text-align:center; margin-bottom:24px;
}
.ft-fp-email-badge span {
    display:inline-block; background:#f4f6ff;
    border:1px solid #c7d2fe; border-radius:20px;
    padding:4px 16px; font-size:.82rem;
    font-weight:700; color:#0D1B5E;
}
.ft-fp-field { margin-bottom:16px; }
.ft-fp-field label {
    display:block; font-size:.79rem;
    font-weight:700; color:#555;
    margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px;
}
.ft-fp-field input {
    width:100%; padding:12px 16px;
    border:1.5px solid #e5e7eb; border-radius:9px;
    font-size:.92rem; font-family:'Inter',sans-serif;
    color:#111; background:#fafafa; outline:none;
    transition:border-color .15s, box-shadow .15s;
}
.ft-fp-field input:focus {
    border-color:#0D1B5E;
    box-shadow:0 0 0 3px rgba(13,27,94,.08);
    background:#fff;
}
.ft-fp-field input::placeholder { color:#bbb; }
.ft-otp-input { text-align:center; letter-spacing:8px; font-size:1.5rem; font-weight:800; }
.ft-pw-wrap3 { position:relative; }
.ft-pw-wrap3 input { padding-right:44px; }
.ft-pw-eye3 {
    position:absolute; right:13px; top:50%; transform:translateY(-50%);
    background:none; border:none; cursor:pointer; color:#aaa; font-size:14px; padding:2px;
}
.ft-pw-eye3:hover { color:#0D1B5E; }
.ft-fp-submit {
    width:100%; padding:13px;
    background:#0D1B5E; color:#fff;
    border:none; border-radius:10px;
    font-family:'Poppins',sans-serif;
    font-size:1rem; font-weight:700;
    cursor:pointer; transition:all .2s; margin-top:4px;
}
.ft-fp-submit:hover { background:#1A3A8F; transform:translateY(-1px); box-shadow:0 6px 20px rgba(13,27,94,.25); }
.ft-fp-error {
    background:#fef2f2; border:1px solid #fecaca;
    color:#dc2626; padding:9px 13px; border-radius:8px;
    font-size:.83rem; margin-bottom:14px;
}
.ft-fp-resend { text-align:center; font-size:.83rem; color:#999; margin-top:16px; }
.ft-fp-resend a { color:#0D1B5E; font-weight:600; text-decoration:none; }
.ft-fp-resend a:hover { color:#F5A623; }
</style>
@endsection

@section('content')
<div class="ft-simple-wrap">

    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:24px;">
        <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
            <svg width="36" height="36" viewBox="0 0 42 42" fill="none">
                <circle cx="21" cy="21" r="21" fill="#0D1B5E"/>
                <path d="M7 30 Q21 17 35 30" stroke="#C62828" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                <polygon points="31,8 37,13 30,15" fill="#F5A623"/>
                <text x="9" y="20.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="white">Faith</text>
                <text x="9" y="29.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="#F5A623">Trip</text>
            </svg>
            <span style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.3rem;">
                <span style="color:#0D1B5E;">Faith</span><span style="color:#F5A623;">Trip</span>
            </span>
        </a>
    </div>

    <div class="ft-simple-card">

        <h2 class="ft-fp-title">Reset Password</h2>
        <p class="ft-fp-sub">OTP sent to</p>
        <div class="ft-fp-email-badge">
            <span>{{ $email }}</span>
        </div>

        @if($errors->any())
        <div class="ft-fp-error">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('b2c.password.reset') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="ft-fp-field">
                <label>Enter OTP</label>
                <input type="text" name="otp" class="ft-otp-input"
                       placeholder="_ _ _ _ _ _" maxlength="6"
                       inputmode="numeric" pattern="\d{6}" required autofocus
                       value="{{ old('otp') }}">
            </div>

            <div class="ft-fp-field">
                <label>New Password</label>
                <div class="ft-pw-wrap3">
                    <input type="password" id="nPw" name="password"
                           placeholder="Min 8 characters" required minlength="8">
                    <button type="button" class="ft-pw-eye3" onclick="ftTogNPw()">
                        <i class="fas fa-eye" id="nPwIcon"></i>
                    </button>
                </div>
            </div>

            <div class="ft-fp-field">
                <label>Confirm New Password</label>
                <div class="ft-pw-wrap3">
                    <input type="password" id="nPw2" name="password_confirmation"
                           placeholder="Repeat password" required>
                    <button type="button" class="ft-pw-eye3" onclick="ftTogNPw2()">
                        <i class="fas fa-eye" id="nPwIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="ft-fp-submit">Reset Password</button>
        </form>

        <div class="ft-fp-resend">
            Didn't receive it? <a href="{{ route('b2c.password.request') }}">Resend OTP</a>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function ftTogNPw() {
    var i=document.getElementById('nPw'), ic=document.getElementById('nPwIcon');
    i.type=i.type==='password'?'text':'password';
    ic.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
function ftTogNPw2() {
    var i=document.getElementById('nPw2'), ic=document.getElementById('nPwIcon2');
    i.type=i.type==='password'?'text':'password';
    ic.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
// only allow digits in OTP field
document.querySelector('input[name=otp]').addEventListener('input', function(){
    this.value = this.value.replace(/\D/g,'').slice(0,6);
});
</script>
@endsection
