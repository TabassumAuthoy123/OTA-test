@extends('b2c.layouts.auth')

@section('title', 'Create Account')

@section('styles')
<style>
.ft-simple-wrap {
    width: 100%;
    max-width: 520px;
}
.ft-simple-card {
    background: #fff;
    border-radius: 16px;
    padding: 36px 40px 32px;
    box-shadow: 0 8px 40px rgba(0,0,0,.10);
    width: 100%;
}
.ft-sc-back {
    display: inline-flex; align-items: center; gap: 6px;
    color: #555; font-size: .85rem; font-weight: 600;
    text-decoration: none; margin-bottom: 20px; transition: color .15s;
}
.ft-sc-back:hover { color: #0D1B5E; }
.ft-sc-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.45rem; font-weight: 800;
    color: #111; margin-bottom: 24px;
}
.ft-sc-field { margin-bottom: 16px; }
.ft-sc-field input,
.ft-sc-field select {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 9px;
    font-size: .9rem;
    font-family: 'Inter', sans-serif;
    color: #111;
    background: #fafafa;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    appearance: none;
    -webkit-appearance: none;
}
.ft-sc-field input:focus,
.ft-sc-field select:focus {
    border-color: #0D1B5E;
    box-shadow: 0 0 0 3px rgba(13,27,94,.08);
    background: #fff;
}
.ft-sc-field input::placeholder { color: #bbb; }
.ft-sc-field select { color: #aaa; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
.ft-sc-field select.has-value { color: #111; }
.ft-sc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 480px) { .ft-sc-row { grid-template-columns: 1fr; } }
.ft-pw-wrap2 { position: relative; }
.ft-pw-wrap2 input { padding-right: 44px; }
.ft-pw-eye2 {
    position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #aaa; font-size: 14px; padding: 2px;
}
.ft-pw-eye2:hover { color: #0D1B5E; }
.ft-sc-submit {
    width: 100%; padding: 13px;
    background: #0D1B5E; color: #fff;
    border: none; border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem; font-weight: 700;
    cursor: pointer; transition: all .2s;
    margin-top: 4px;
}
.ft-sc-submit:hover { background: #1A3A8F; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(13,27,94,.25); }
.ft-sc-divider { border: none; border-top: 1px solid #f0f0f0; margin: 20px 0 16px; }
.ft-sc-login-link { font-size: .87rem; color: #666; }
.ft-sc-login-link a { color: #0D1B5E; font-weight: 700; text-decoration: none; }
.ft-sc-login-link a:hover { color: #F5A623; }
.ft-sc-copy { font-size: .72rem; color: #ccc; margin-top: 18px; }
.ft-auth-error-sm {
    background: #fef2f2; border: 1px solid #fecaca;
    color: #dc2626; padding: 9px 13px; border-radius: 8px;
    font-size: .83rem; margin-bottom: 14px;
}
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
            <span style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.3rem;"><span style="color:#0D1B5E;">Faith</span><span style="color:#F5A623;">Trip</span></span>
        </a>
    </div>

    <div class="ft-simple-card">

        <a href="{{ route('b2c.login') }}" class="ft-sc-back">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back
        </a>

        <div class="ft-sc-title">Create New Account</div>

        @if($errors->any())
        <div class="ft-auth-error-sm">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('b2c.register.submit') }}">
            @csrf

            <div class="ft-sc-row">
                <div class="ft-sc-field">
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           placeholder="First Name" required>
                </div>
                <div class="ft-sc-field">
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           placeholder="Last Name" required>
                </div>
            </div>

            <div class="ft-sc-field">
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="Email" required>
            </div>

            <div class="ft-sc-row">
                <div class="ft-sc-field">
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           placeholder="Mobile No" required>
                </div>
                <div class="ft-sc-field">
                    <select name="gender" id="genderSel" onchange="this.classList.add('has-value')">
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                        <option value="male"   {{ old('gender')=='male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender')=='other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="ft-sc-field ft-pw-wrap2">
                <input type="password" id="regPw" name="password"
                       placeholder="Password" required minlength="8">
                <button type="button" class="ft-pw-eye2" onclick="ftToggleRegPw()">
                    <i class="fas fa-eye" id="regPwIcon"></i>
                </button>
            </div>

            <div class="ft-sc-field ft-pw-wrap2">
                <input type="password" id="regPw2" name="password_confirmation"
                       placeholder="Confirm Password" required>
                <button type="button" class="ft-pw-eye2" onclick="ftToggleRegPw2()">
                    <i class="fas fa-eye" id="regPwIcon2"></i>
                </button>
            </div>

            <button type="submit" class="ft-sc-submit">Sign Up</button>
        </form>

        <hr class="ft-sc-divider">

        <div class="ft-sc-login-link">
            Already have one? <a href="{{ route('b2c.login') }}">Login here</a>
        </div>

        <div class="ft-sc-copy">&copy; {{ date('Y') }} FaithTrip — All Rights Reserved.</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function ftToggleRegPw() {
    var i = document.getElementById('regPw');
    var ic = document.getElementById('regPwIcon');
    i.type = i.type === 'password' ? 'text' : 'password';
    ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
function ftToggleRegPw2() {
    var i = document.getElementById('regPw2');
    var ic = document.getElementById('regPwIcon2');
    i.type = i.type === 'password' ? 'text' : 'password';
    ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
// Mark gender select as filled if old value exists
(function(){
    var s = document.getElementById('genderSel');
    if (s && s.value) s.classList.add('has-value');
})();
</script>
@endsection
