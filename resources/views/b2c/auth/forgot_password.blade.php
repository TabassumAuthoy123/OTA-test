@extends('b2c.layouts.auth')

@section('title', 'Forgot Password')

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
    color:#111; margin:0 0 6px;
    text-align:center;
}
.ft-fp-sub {
    text-align:center; font-size:.84rem;
    color:#999; margin:0 0 28px;
}
.ft-fp-sub a { color:#0D1B5E; font-weight:600; text-decoration:none; }
.ft-fp-sub a:hover { color:#F5A623; }
.ft-fp-field { margin-bottom:18px; }
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
.ft-fp-submit {
    width:100%; padding:13px;
    background:#0D1B5E; color:#fff;
    border:none; border-radius:10px;
    font-family:'Poppins',sans-serif;
    font-size:1rem; font-weight:700;
    cursor:pointer; transition:all .2s;
    letter-spacing:.3px;
}
.ft-fp-submit:hover { background:#1A3A8F; transform:translateY(-1px); box-shadow:0 6px 20px rgba(13,27,94,.25); }
.ft-fp-error {
    background:#fef2f2; border:1px solid #fecaca;
    color:#dc2626; padding:9px 13px; border-radius:8px;
    font-size:.83rem; margin-bottom:14px;
}
.ft-fp-success {
    background:#f0fdf4; border:1px solid #bbf7d0;
    color:#16a34a; padding:10px 14px; border-radius:8px;
    font-size:.84rem; margin-bottom:16px; text-align:center;
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
            <span style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.3rem;">
                <span style="color:#0D1B5E;">Faith</span><span style="color:#F5A623;">Trip</span>
            </span>
        </a>
    </div>

    <div class="ft-simple-card">

        <h2 class="ft-fp-title">Forgot Password ?</h2>
        <p class="ft-fp-sub">
            <a href="{{ route('b2c.login') }}">Back to login</a>
        </p>

        @if($errors->any())
        <div class="ft-fp-error">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
        @endif

        @if(session('otp_sent'))
        <div class="ft-fp-success">
            <i class="fas fa-check-circle me-1"></i> OTP sent! Check your email.
        </div>
        @endif

        <form method="POST" action="{{ route('b2c.password.send-otp') }}">
            @csrf
            <div class="ft-fp-field">
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="Provide your email" required autofocus>
            </div>
            <button type="submit" class="ft-fp-submit">Send OTP</button>
        </form>

    </div>
</div>
@endsection
