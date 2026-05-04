@extends('b2c.layouts.auth')

@section('title', 'Sign In')

@section('styles')
<style>
.ft-auth-card { max-width: 960px; min-height: 560px; }

/* expanded contact section */
.ft-auth-contact { display: flex; flex-direction: column; gap: 9px; }
.ft-auth-contact-item { display: flex; flex-direction: column; gap: 2px; }
.ft-auth-contact-item .label {
    font-size: .63rem; font-weight: 700; letter-spacing: 1.2px;
    color: rgba(255,255,255,.5); text-transform: uppercase;
}
.ft-auth-contact-item .value {
    font-size: .83rem; font-weight: 600; color: #fff; text-decoration: none; line-height: 1.5;
}
.ft-auth-contact-item .value:hover { color: #F5A623; }
.ft-auth-contact-item .value-sm {
    font-size: .76rem; font-weight: 500; color: rgba(255,255,255,.85); text-decoration: none; line-height: 1.5; display: block;
}
.ft-auth-contact-item .value-sm:hover { color: #F5A623; }
.ft-auth-contact-phones { display: flex; flex-wrap: wrap; gap: 4px 10px; }
.ft-auth-social-row { display: flex; gap: 10px; flex-wrap: wrap; padding-top: 2px; }
.ft-auth-social-icon {
    width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center;
    justify-content: center; font-size: .78rem; text-decoration: none;
    background: rgba(255,255,255,.12); color: #fff; transition: background .15s, transform .15s;
}
.ft-auth-social-icon:hover { background: rgba(255,255,255,.28); transform: scale(1.1); }
.ft-auth-address { font-size: .76rem; color: rgba(255,255,255,.72); line-height: 1.5; }
</style>
@endsection

@section('content')
@php
    use App\Models\CmsSiteSetting;
    use Illuminate\Support\Facades\DB;

    $ls       = CmsSiteSetting::allAsArray();
    $lsPhone  = $ls['footer_phone']   ?? '';
    $lsPhone2 = $ls['footer_phone_2'] ?? '';
    $lsEmail  = $ls['footer_email']   ?? 'info@faithtrip.net';
    $lsEmail2 = $ls['footer_email_2'] ?? '';
    $lsEmail3 = $ls['footer_email_3'] ?? '';
    $lsEmail4 = $ls['footer_email_4'] ?? '';
    $lsAddr   = $ls['footer_address'] ?? '';

    $loginSocials = DB::table('social_media_links')->orderBy('name')->get();
    $socialIconMap = [
        'facebook'  => ['icon' => 'fa-facebook-f',  'color' => '#1877F2'],
        'twitter'   => ['icon' => 'fa-twitter',      'color' => '#1DA1F2'],
        'instagram' => ['icon' => 'fa-instagram',    'color' => '#E1306C'],
        'youtube'   => ['icon' => 'fa-youtube',      'color' => '#FF0000'],
        'pinterest' => ['icon' => 'fa-pinterest',    'color' => '#E60023'],
        'tiktok'    => ['icon' => 'fa-tiktok',       'color' => '#ffffff'],
    ];
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

            {{-- Hotlines --}}
            @if($lsPhone)
            <div class="ft-auth-contact-item">
                <span class="label">Hotline</span>
                <div class="ft-auth-contact-phones">
                    <a href="tel:{{ preg_replace('/\s+/','',$lsPhone) }}" class="value">{{ $lsPhone }}</a>
                    @if($lsPhone2)
                    <a href="tel:{{ preg_replace('/\s+/','',$lsPhone2) }}" class="value">{{ $lsPhone2 }}</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Emails --}}
            <div class="ft-auth-contact-item">
                <span class="label">Email</span>
                @foreach(array_filter([$lsEmail,$lsEmail2,$lsEmail3,$lsEmail4]) as $em)
                <a href="mailto:{{ $em }}" class="value-sm">{{ $em }}</a>
                @endforeach
            </div>

            {{-- Social icons --}}
            @if($loginSocials->isNotEmpty())
            <div class="ft-auth-contact-item">
                <span class="label">Follow Us</span>
                <div class="ft-auth-social-row">
                    @foreach($loginSocials as $ls_soc)
                    @php
                        $socKey  = strtolower($ls_soc->name);
                        $socInfo = $socialIconMap[$socKey] ?? ['icon'=>'fa-globe','color'=>'#fff'];
                    @endphp
                    <a href="{{ $ls_soc->link ?? '#' }}" target="_blank"
                       class="ft-auth-social-icon"
                       title="{{ $ls_soc->name }}"
                       data-bg="{{ $socInfo['color'] }}">
                        <i class="fab {{ $socInfo['icon'] }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Address --}}
            @if($lsAddr)
            <div class="ft-auth-contact-item">
                <span class="label">Office</span>
                <span class="ft-auth-address">{{ $lsAddr }}</span>
            </div>
            @endif

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
// Apply social icon bg colors from data-bg
document.querySelectorAll('.ft-auth-social-icon[data-bg]').forEach(function(el){
    el.style.backgroundColor = el.dataset.bg;
});
</script>
@endsection
