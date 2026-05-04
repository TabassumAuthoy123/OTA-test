@php
    use App\Models\CmsSiteSetting;
    $navSettings = CmsSiteSetting::allAsArray();
    $hotlineNum  = $navSettings['footer_phone']   ?? '';
    $hotlineNum2 = $navSettings['footer_phone_2'] ?? '';
    $navAnn = \Illuminate\Support\Facades\DB::table('announcements')
        ->where('is_active', 1)
        ->whereIn('target', ['all', 'b2c'])
        ->where(function($q){ $q->whereNull('show_from')->orWhere('show_from','<=',now()); })
        ->where(function($q){ $q->whereNull('show_until')->orWhere('show_until','>=',now()); })
        ->orderByDesc('id')->first();
    $annText  = $navAnn ? '<strong>'.e($navAnn->title).':</strong> '.e($navAnn->message) : 'Welcome to FaithTrip!! ✈ &nbsp; Your Trusted IATA-Certified Travel Agency &nbsp; ✈ &nbsp; Book Flights, Tours, Visa &amp; More';
    $socialLinks = \Illuminate\Support\Facades\DB::table('social_media_links')->orderBy('name')->get();
@endphp

{{-- ── Announcement Bar (marquee) ── --}}
<div class="ft-announcement-bar" id="ftAnnouncementBar">
    <div class="ft-ann-marquee-wrap">
        <div class="ft-ann-marquee">
            <span>{!! $annText !!} &nbsp;&nbsp; ✈ &nbsp;&nbsp; {!! $annText !!} &nbsp;&nbsp; ✈ &nbsp;&nbsp; {!! $annText !!} &nbsp;&nbsp; ✈ &nbsp;&nbsp; {!! $annText !!}</span>
        </div>
    </div>
    <button class="ft-announcement-close" onclick="document.getElementById('ftAnnouncementBar').style.display='none'" aria-label="Close">&times;</button>
</div>

{{-- ── Main Navbar ── --}}
<nav class="ft-navbar" id="ftNavbar">
    <div class="container">
        <div class="ft-navbar-inner">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="ft-logo">
                <svg width="38" height="38" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="21" cy="21" r="21" fill="#0D1B5E"/>
                    <path d="M7 30 Q21 17 35 30" stroke="#C62828" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                    <polygon points="31,8 37,13 30,15" fill="#F5A623"/>
                    <text x="9" y="20.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="white">Faith</text>
                    <text x="9" y="29.5" font-family="Arial,sans-serif" font-weight="700" font-size="7.5" fill="#F5A623">Trip</text>
                </svg>
                <span class="ft-logo-text"><span class="ft-logo-faith">Faith</span><span class="ft-logo-trip">Trip</span></span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="ft-nav-links d-none d-xl-flex">
                <a href="{{ url('/') }}" class="ft-nav-link {{ request()->is('/') ? 'active' : '' }}">Flights</a>
                <a href="#" class="ft-nav-link">Visa</a>
                <a href="#" class="ft-nav-link">Tours</a>
                <a href="#" class="ft-nav-link">Blog</a>
                <a href="#" class="ft-nav-link">Gift Cards</a>
                <a href="#" class="ft-nav-link">Transport</a>
                <a href="#" class="ft-nav-link">E-Sim</a>
                <a href="#" class="ft-nav-link">Medical</a>
                <a href="#" class="ft-nav-link">Gallery <i class="fas fa-chevron-down" style="font-size:9px;margin-left:2px;"></i></a>
            </div>

            {{-- Right Side --}}
            <div class="ft-nav-right">

                {{-- Hotline (1 or 2 numbers) --}}
                @if($hotlineNum)
                <div class="ft-hotline d-none d-xl-flex">
                    <span>Hotline:</span>
                    <a href="tel:{{ preg_replace('/\s+/','',$hotlineNum) }}" class="ft-hotline-number">{{ $hotlineNum }}</a>
                    @if($hotlineNum2)
                    <span class="ft-hotline-sep">/</span>
                    <a href="tel:{{ preg_replace('/\s+/','',$hotlineNum2) }}" class="ft-hotline-number">{{ $hotlineNum2 }}</a>
                    @endif
                </div>
                @endif

                {{-- Currency Dropdown --}}
                <div class="ft-currency-wrap d-none d-lg-flex" id="ftCurrencyWrap">
                    <button class="ft-currency-btn" onclick="ftToggleCurrency(event)" id="ftCurrencyBtn" type="button">
                        <span id="ftCurrencyLabel">BDT (৳)</span>
                        <i class="fas fa-chevron-down ft-curr-arrow"></i>
                    </button>
                    <div class="ft-currency-dropdown" id="ftCurrencyDropdown">
                        <div class="ft-currency-option" data-code="USD" data-label="USD ($)"    onclick="ftSelectCurrency(this)">USD ($)</div>
                        <div class="ft-currency-option" data-code="BDT" data-label="BDT (৳)"   onclick="ftSelectCurrency(this)">BDT (৳)</div>
                        <div class="ft-currency-option" data-code="GBP" data-label="GBP (£)"   onclick="ftSelectCurrency(this)">GBP (£)</div>
                        <div class="ft-currency-option" data-code="MYR" data-label="MYR (RM)"  onclick="ftSelectCurrency(this)">MYR (RM)</div>
                    </div>
                </div>

                {{-- Social icons (desktop) --}}
                <div class="ft-nav-social d-none d-xl-flex">
                    @php
                        $allSocialNames = ['facebook','twitter','instagram','youtube','pinterest','tiktok'];
                        $navSocials = $socialLinks->filter(fn($s) => in_array(strtolower($s->name), $allSocialNames));
                        $socialIconMap = [
                            'facebook'  => ['icon'=>'fa-facebook-f',  'color'=>'#1877F2'],
                            'twitter'   => ['icon'=>'fa-twitter',      'color'=>'#1DA1F2'],
                            'instagram' => ['icon'=>'fa-instagram',    'color'=>'#E1306C'],
                            'youtube'   => ['icon'=>'fa-youtube',      'color'=>'#FF0000'],
                            'pinterest' => ['icon'=>'fa-pinterest',    'color'=>'#E60023'],
                            'tiktok'    => ['icon'=>'fa-tiktok',       'color'=>'#000000'],
                        ];
                    @endphp
                    @foreach($navSocials as $ns)
                    @php
                        $nsKey = strtolower($ns->name);
                        $nsInfo = $socialIconMap[$nsKey] ?? ['icon'=>'fa-globe','color'=>'#555'];
                    @endphp
                    <a href="{{ $ns->link ?? '#' }}" target="_blank" class="ft-nav-social-link" title="{{ $ns->name }}" data-sc="{{ $nsInfo['color'] }}">
                        <i class="fab {{ $nsInfo['icon'] }}"></i>
                    </a>
                    @endforeach
                </div>

                @auth
                    @if(auth()->user()->user_type == 3)
                        <a href="{{ url('/my-account') }}" class="ft-login-pill d-none d-sm-inline-flex">
                            <i class="fas fa-user" style="font-size:.75rem;"></i> Account
                        </a>
                    @endif
                @else
                    <a href="{{ route('b2c.login') }}" class="ft-login-pill d-none d-sm-inline-flex">
                        Login
                    </a>
                @endauth

                <button class="ft-menu-toggle d-xl-none" onclick="toggleFtMobile()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="ft-mobile-menu d-xl-none" id="ftMobileMenu">
            <a href="{{ url('/') }}" class="ft-mobile-link {{ request()->is('/') ? 'active' : '' }}">✈ Flights</a>
            <a href="#" class="ft-mobile-link">🛂 Visa</a>
            <a href="#" class="ft-mobile-link">🏖 Tours</a>
            <a href="#" class="ft-mobile-link">📰 Blog</a>
            <a href="#" class="ft-mobile-link">🎁 Gift Cards</a>
            <a href="#" class="ft-mobile-link">🚌 Transport</a>
            <a href="#" class="ft-mobile-link">📱 E-Sim</a>
            <a href="#" class="ft-mobile-link">🏥 Medical</a>
            {{-- Mobile social --}}
            <div style="display:flex;gap:14px;padding:10px 8px 6px;border-top:1px solid #f0f0f0;margin-top:4px;">
                @foreach($navSocials as $ms)
                @php
                    $msKey  = strtolower($ms->name);
                    $msInfo = $socialIconMap[$msKey] ?? ['icon'=>'fa-globe','color'=>'#555'];
                @endphp
                <a href="{{ $ms->link ?? '#' }}" target="_blank" class="ft-mob-social-link" data-color="{{ $msInfo['color'] }}" style="font-size:1.1rem;" title="{{ $ms->name }}">
                    <i class="fab {{ $msInfo['icon'] }}"></i>
                </a>
                @endforeach
            </div>
            @guest
                <a href="{{ route('b2c.login') }}" class="ft-mobile-link">🔑 Login</a>
            @endguest
        </div>
    </div>
</nav>

<style>
.ft-hotline-sep { color: rgba(255,255,255,.45); margin: 0 3px; font-size: .8rem; }

/* Currency dropdown */
.ft-currency-wrap { position: relative; align-items: center; }
.ft-currency-btn {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px; padding: 5px 12px; cursor: pointer;
    font-size: .82rem; font-weight: 600; color: #fff;
    transition: background .15s;
}
.ft-currency-btn:hover { background: rgba(255,255,255,.22); }
.ft-curr-arrow { font-size: 7px; margin-left: 2px; transition: transform .2s; }
.ft-currency-wrap.open .ft-curr-arrow { transform: rotate(180deg); }
.ft-currency-dropdown {
    display: none; position: absolute; top: calc(100% + 8px); right: 0;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.13); min-width: 130px; z-index: 9999; overflow: hidden;
}
.ft-currency-wrap.open .ft-currency-dropdown { display: block; }
.ft-currency-option {
    padding: 10px 16px; font-size: .87rem; font-weight: 500;
    color: #333; cursor: pointer; transition: background .1s;
}
.ft-currency-option:hover { background: #f5f7ff; }
.ft-currency-option.active { background: #EEF1FF; color: #0D1B5E; font-weight: 700; }
</style>

<script>
function toggleFtMobile() {
    var m = document.getElementById('ftMobileMenu');
    if (m) m.classList.toggle('open');
}

// ── Currency dropdown ──
function ftToggleCurrency(e) {
    e.stopPropagation();
    document.getElementById('ftCurrencyWrap').classList.toggle('open');
}
function ftSelectCurrency(el) {
    var code  = el.dataset.code;
    var label = el.dataset.label;
    document.getElementById('ftCurrencyLabel').textContent = label;
    document.getElementById('ftCurrencyWrap').classList.remove('open');
    localStorage.setItem('ft_currency', code);
    document.querySelectorAll('.ft-currency-option').forEach(function(o){
        o.classList.toggle('active', o.dataset.code === code);
    });
}
document.addEventListener('click', function(e) {
    var wrap = document.getElementById('ftCurrencyWrap');
    if (wrap && !wrap.contains(e.target)) wrap.classList.remove('open');
});
// Apply data-sc (CSS custom prop) and data-color to social links
document.querySelectorAll('[data-sc]').forEach(function(el){
    el.style.setProperty('--sc', el.dataset.sc);
});
document.querySelectorAll('.ft-mob-social-link[data-color]').forEach(function(el){
    el.style.color = el.dataset.color;
});

// init from localStorage
(function() {
    var saved = localStorage.getItem('ft_currency');
    if (!saved) { saved = 'BDT'; }
    var labels = { USD:'USD ($)', BDT:'BDT (৳)', GBP:'GBP (£)', MYR:'MYR (RM)' };
    if (labels[saved]) {
        var lbl = document.getElementById('ftCurrencyLabel');
        if (lbl) lbl.textContent = labels[saved];
    }
    document.querySelectorAll('.ft-currency-option').forEach(function(o){
        o.classList.toggle('active', o.dataset.code === saved);
    });
})();
</script>
