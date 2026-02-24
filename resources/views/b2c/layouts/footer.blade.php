@php
    use App\Models\CmsSiteSetting;
    use App\Models\CmsPaymentMethod;
    $footerSettings = CmsSiteSetting::allAsArray();
    $paymentMethods = CmsPaymentMethod::active()->get();
@endphp
<!-- B2C Footer -->
<footer class="b2c-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="b2c-footer-brand">
                    <a href="{{ url('/') }}" class="b2c-logo">
                        <i class="fas fa-plane-departure"></i>
                        <span>{{ config('app.name', 'SkyTrip') }}</span>
                    </a>
                    <p class="b2c-footer-desc">
                        {{ $footerSettings['footer_description'] ?? 'Your dream destination is just a few clicks away. Book flights at the best price with instant confirmation.' }}
                    </p>
                    <div class="b2c-social-links">
                        <a href="{{ $footerSettings['social_facebook'] ?? '#' }}" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="{{ $footerSettings['social_instagram'] ?? '#' }}" target="_blank"><i
                                class="fab fa-instagram"></i></a>
                        <a href="{{ $footerSettings['social_twitter'] ?? '#' }}" target="_blank"><i
                                class="fab fa-twitter"></i></a>
                        <a href="{{ $footerSettings['social_linkedin'] ?? '#' }}" target="_blank"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="b2c-footer-heading">Quick Links</h5>
                <ul class="b2c-footer-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/page/about') }}">About Us</a></li>
                    <li><a href="{{ url('/page/terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ url('/page/privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('/page/refund') }}">Refund Policy</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-md-6">
                <h5 class="b2c-footer-heading">Support</h5>
                <ul class="b2c-footer-links">
                    <li><a href="{{ url('/page/faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/page/contact') }}">Contact Us</a></li>
                    <li><a href="#">Live Chat</a></li>
                    <li><a href="#">Manage Booking</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="b2c-footer-heading">Contact</h5>
                <ul class="b2c-footer-contact">
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ $footerSettings['footer_phone'] ?? '+880-XXXX-XXXXXX' }}</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>{{ $footerSettings['footer_email'] ?? 'support@skytrip.com' }}</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $footerSettings['footer_address'] ?? 'Dhaka, Bangladesh' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="b2c-footer-payment">
            <span>We Accept</span>
            <div class="b2c-payment-icons">
                @if($paymentMethods->count())
                    @foreach($paymentMethods as $pm)
                        <img src="{{ asset($pm->image) }}" alt="{{ $pm->name }}">
                    @endforeach
                @else
                    {{-- Fallback: hardcoded logos until admin uploads custom ones --}}
                    <img src="https://cdn.jsdelivr.net/gh/nicepay-dev/nicepay-dev.github.io/assets/img/visa.svg" alt="Visa"
                        style="background:#fff; padding:4px 8px;">
                    <img src="https://cdn.jsdelivr.net/gh/nicepay-dev/nicepay-dev.github.io/assets/img/mastercard.svg"
                        alt="Mastercard" style="background:#fff; padding:4px 8px;">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/americanexpress.svg" alt="Amex"
                        style="background:#fff; padding:4px 8px;">
                    <img src="https://play-lh.googleusercontent.com/j-4r3CcOGgOw-eFBpz6MOJm_dMFvCSz6gg8LoUuRPMGpPj9HooBYMa9CJzDKPPxJkQ=w240-h480-rw"
                        alt="bKash">
                    <img src="https://play-lh.googleusercontent.com/unwiJPmSI7oAvhSzTnWo2a01-z8MKKdZ8mf2r8QWilXXU1xq5JJYotgQ7tCHq8GlFpBe=w240-h480-rw"
                        alt="Nagad">
                    <img src="https://play-lh.googleusercontent.com/eSIDXxaejjM0n-_ggXVwVJP9SzfEKVhG_UwTOOu4lp_QHaavTqwDQYqQi6KUlw9NmXE=w240-h480-rw"
                        alt="Rocket">
                    <img src="https://play-lh.googleusercontent.com/m-ipYC5sIfkT_NeOJwBNcIcmJ4jBqW6DhTLN1LvXWXOz87-8x6t7IEaYVbK5e3xiH7c=w240-h480-rw"
                        alt="Upay">
                    <img src="https://play-lh.googleusercontent.com/Oce5MLaAuFNxVuaVi-E-t72sRhLCL-mD5K1t3Nb5SKfqI0Lp1aBVKbFQdQfqJaPMYYE=w240-h480-rw"
                        alt="Tap">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/200px-MasterCard_Logo.svg.png"
                        alt="Debit" style="background:#fff; padding:4px 8px;">
                    <img src="https://logos-world.net/wp-content/uploads/2020/06/Visa-Logo-2006.png" alt="Visa Debit"
                        style="background:#fff; padding:2px 8px;">
                @endif
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="b2c-footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'SkyTrip') }}. All rights reserved.</p>
        </div>
    </div>
</footer>