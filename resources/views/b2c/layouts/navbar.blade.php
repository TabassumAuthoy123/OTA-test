@php
    use App\Models\CmsSiteSetting;
    $navSettings = CmsSiteSetting::allAsArray();
    $hotlineNum  = $navSettings['footer_phone'] ?? '';
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
                @if($hotlineNum)
                <div class="ft-hotline d-none d-xl-flex">
                    <span>Hotline:</span>
                    <a href="tel:{{ preg_replace('/\s+/','',$hotlineNum) }}" class="ft-hotline-number">{{ $hotlineNum }}</a>
                </div>
                @endif

                <span class="ft-currency-btn d-none d-lg-inline-flex">
                    BDT (৳) <i class="fas fa-chevron-down" style="font-size:8px;margin-left:3px;"></i>
                </span>

                {{-- Social icons (desktop) --}}
                <div class="ft-nav-social d-none d-xl-flex">
                    @php
                        $navSocials = $socialLinks->filter(fn($s) => in_array(strtolower($s->name), ['facebook','twitter','instagram','youtube']));
                    @endphp
                    @foreach($navSocials as $ns)
                    @php
                        $nsN = strtolower($ns->name);
                        $nsIcon = $nsN === 'facebook' ? 'fa-facebook-f' : ($nsN === 'twitter' ? 'fa-twitter' : ($nsN === 'instagram' ? 'fa-instagram' : ($nsN === 'youtube' ? 'fa-youtube' : 'fa-globe')));
                        $nsColor = $nsN === 'facebook' ? '#1877F2' : ($nsN === 'twitter' ? '#1DA1F2' : ($nsN === 'instagram' ? '#E1306C' : ($nsN === 'youtube' ? '#FF0000' : '#555')));
                    @endphp
                    <a href="{{ $ns->link ?? '#' }}" target="_blank" class="ft-nav-social-link" title="{{ $ns->name }}" style="--sc:{{ $nsColor }};">
                        <i class="fab {{ $nsIcon }}"></i>
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
                @foreach($socialLinks->whereIn('name',['Facebook','Twitter','Instagram','YouTube']) as $ms)
                @php
                    $msN = strtolower($ms->name);
                    $msIcon = $msN === 'facebook' ? 'fa-facebook-f' : ($msN === 'twitter' ? 'fa-twitter' : ($msN === 'instagram' ? 'fa-instagram' : 'fa-youtube'));
                    $msColor = $msN === 'facebook' ? '#1877F2' : ($msN === 'twitter' ? '#1DA1F2' : ($msN === 'instagram' ? '#E1306C' : '#FF0000'));
                @endphp
                <a href="{{ $ms->link ?? '#' }}" target="_blank" style="color:{{ $msColor }};font-size:1.1rem;"><i class="fab {{ $msIcon }}"></i></a>
                @endforeach
            </div>
            @guest
                <a href="{{ route('b2c.login') }}" class="ft-mobile-link">🔑 Login</a>
            @endguest
        </div>
    </div>
</nav>

<script>
function toggleFtMobile() {
    var m = document.getElementById('ftMobileMenu');
    if (m) m.classList.toggle('open');
}
</script>
