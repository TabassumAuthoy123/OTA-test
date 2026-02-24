<!-- B2C Navbar -->
<nav class="b2c-navbar" id="b2cNavbar">
    <div class="container">
        <div class="b2c-navbar-inner">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="b2c-logo">
                <i class="fas fa-plane-departure"></i>
                <span>{{ config('app.name', 'SkyTrip') }}</span>
            </a>

            <!-- Nav Links (Desktop) -->
            <div class="b2c-nav-links d-none d-lg-flex">
                <a href="{{ url('/') }}" class="b2c-nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-plane"></i> Flights
                </a>
                <a href="#deals" class="b2c-nav-link">
                    <i class="fas fa-tags"></i> Deals
                </a>
                <a href="#" class="b2c-nav-link">
                    <i class="fas fa-headset"></i> Support
                </a>
            </div>

            <!-- Right Actions -->
            <div class="b2c-nav-actions">


                @auth
                    @if(auth()->user()->user_type == 3)
                        <a href="{{ url('/my-account') }}" class="b2c-nav-btn b2c-nav-btn-outline">
                            <i class="fas fa-user"></i> My Account
                        </a>
                    @endif
                @else
                    <a href="{{ route('b2c.login') }}" class="b2c-nav-btn b2c-nav-btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button class="b2c-menu-toggle d-lg-none" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="b2c-mobile-menu d-lg-none" id="mobileMenu">
            <a href="{{ url('/') }}" class="b2c-mobile-link">
                <i class="fas fa-plane"></i> Flights
            </a>
            <a href="#deals" class="b2c-mobile-link">
                <i class="fas fa-tags"></i> Deals
            </a>
            <a href="#" class="b2c-mobile-link">
                <i class="fas fa-headset"></i> Support
            </a>
            @guest
                <a href="{{ route('b2c.login') }}" class="b2c-mobile-link">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            @endguest
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        document.getElementById('mobileMenu').classList.toggle('open');
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('b2cNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>