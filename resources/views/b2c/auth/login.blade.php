@extends('b2c.layouts.auth')

@section('title', 'Sign In')

@section('content')
@php
    use App\Models\CmsSiteSetting;
    $ls = CmsSiteSetting::allAsArray();
    $lsPhone = $ls['footer_phone'] ?? '';
    $lsEmail = $ls['footer_email'] ?? 'info@faithtrip.net';
@endphp

<div class="ft-auth-card">

    {{-- ── Left Panel ── --}}
    <div class="ft-auth-left">
        <a href="{{ url('/') }}" class="ft-auth-left-logo">
            <svg width="38" height="38" viewBox="0 0 42 42" fill="none">
                <circle cx="21" cy="21" r="21" fill="#0D1B5E"/>
                <path d="M7 30 Q21 17 35 30" stroke="#C62828" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                <polygon points="31,8 37,13 30,15" fill="#F5A623"/>
                <text x="9" y="20.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="white">Faith</text>
                <text x="9" y="29.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="#F5A623">Trip</text>
            </svg>
            <span class="faith">Faith</span><span class="trip">Trip</span>
        </a>

        <div class="ft-auth-left-body">
            <h2>Your journeys,<br><span>one account</span></h2>
            <p>Manage bookings, track trips, and access member-only deals — all in one secure place.</p>
        </div>

        <div class="ft-auth-contact">
            @if($lsPhone)
            <div class="ft-auth-contact-item">
                <span class="label">Hotline</span>
                <a href="tel:{{ preg_replace('/\s+/','',$lsPhone) }}" class="value">{{ $lsPhone }}</a>
            </div>
            @endif
            <div class="ft-auth-contact-item">
                <span class="label">Email</span>
                <a href="mailto:{{ $lsEmail }}" class="value">{{ $lsEmail }}</a>
            </div>
        </div>
    </div>

    {{-- ── Right Panel ── --}}
    <div class="ft-auth-right">
        <div class="ft-auth-right-top">
            <a href="{{ url('/') }}" class="ft-auth-back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <span class="ft-auth-secure-badge">
                <i class="fas fa-shield-alt"></i> Secure login
            </span>
        </div>

        <div class="ft-auth-right-body">
            <h3>Welcome back</h3>
            <p class="sub">Log in to continue planning your next trip.</p>

            @if($errors->any())
            <div class="ft-auth-error">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
            @endif

            @if(session('status'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:10px 14px;border-radius:8px;font-size:.84rem;margin-bottom:16px;">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('b2c.login.submit') }}">
                @csrf

                <div class="ft-auth-form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="you@email.com" required autofocus>
                </div>

                <div class="ft-auth-form-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <label for="password" style="margin:0;">Password</label>
                        @if(Route::has('b2c.password.request'))
                        <a href="{{ route('b2c.password.request') }}" class="ft-forgot-link">Forgot password?</a>
                        @endif
                    </div>
                    <div class="ft-pw-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="Enter your password" required>
                        <button type="button" class="ft-pw-eye" onclick="ftTogglePw()" title="Show/hide">
                            <i class="fas fa-eye" id="ftPwIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="ft-auth-row">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>

                <button type="submit" class="ft-auth-submit">Log In</button>
            </form>

            <div class="ft-auth-switch">
                New to FaithTrip? <a href="{{ route('b2c.register') }}">Create an account</a>
            </div>
        </div>
    </div>

</div>

<div class="ft-auth-foot">
    &copy; {{ date('Y') }} FaithTrip. All rights reserved.
</div>
@endsection

@section('scripts')
<script>
function ftTogglePw() {
    var inp  = document.getElementById('password');
    var icon = document.getElementById('ftPwIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else                         { inp.type = 'password'; icon.className = 'fas fa-eye'; }
}
</script>
@endsection
