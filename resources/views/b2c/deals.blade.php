@extends('b2c.layouts.master')

@section('title', 'Exclusive Deals & Offers - FaithTrip')
@section('meta_description', 'Discover exclusive flight deals, seasonal offers, and discounted fares on FaithTrip. Save big on domestic and international flights from Bangladesh.')

@section('content')

    {{-- Hero Banner --}}
    <section class="deals-hero">
        <div class="container">
            <div class="deals-hero-content">
                <span class="deals-hero-badge"><i class="fas fa-fire"></i> Hot Deals</span>
                <h1>Exclusive Deals & Offers</h1>
                <p class="deals-hero-subtitle">Grab amazing discounts on flights from Bangladesh. Limited time offers you
                    don't want to miss!</p>
                <div class="deals-hero-stats">
                    <div class="deals-stat">
                        <span class="deals-stat-num">{{ $promotions->count() }}</span>
                        <span class="deals-stat-label">Active Deals</span>
                    </div>
                    <div class="deals-stat-divider"></div>
                    <div class="deals-stat">
                        <span class="deals-stat-num">50+</span>
                        <span class="deals-stat-label">Destinations</span>
                    </div>
                    <div class="deals-stat-divider"></div>
                    <div class="deals-stat">
                        <span class="deals-stat-num">24/7</span>
                        <span class="deals-stat-label">Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Deals Grid --}}
    <section class="deals-section">
        <div class="container">
            @if($promotions->count() > 0)
                <div class="deals-grid">
                    @foreach($promotions as $index => $promo)
                        <div class="deal-card {{ $index === 0 ? 'deal-card-featured' : '' }}">
                            <div class="deal-card-image"
                                style="background: {{ $promo->badge_color ?? 'linear-gradient(135deg, #6366f1, #8b5cf6)' }}">
                                @if($promo->image)
                                    <img src="{{ $promo->image }}" alt="{{ $promo->title }}">
                                @else
                                    <div class="deal-card-icon">
                                        <i class="fas fa-plane-departure"></i>
                                    </div>
                                @endif
                                @if($promo->discount_text)
                                    <span class="deal-badge" style="background: {{ $promo->badge_color ?? '#dc3545' }}">
                                        {{ $promo->discount_text }}
                                    </span>
                                @endif
                                @if($promo->expires_at)
                                    <span class="deal-timer">
                                        <i class="fas fa-clock"></i>
                                        Ends {{ $promo->expires_at->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                            <div class="deal-card-body">
                                <h3 class="deal-card-title">{{ $promo->title }}</h3>
                                <p class="deal-card-desc">{{ $promo->description }}</p>
                                <div class="deal-card-footer">
                                    @if($promo->link)
                                        <a href="{{ $promo->link }}" class="deal-card-btn">
                                            <i class="fas fa-arrow-right"></i> Book Now
                                        </a>
                                    @else
                                        <a href="{{ url('/') }}" class="deal-card-btn">
                                            <i class="fas fa-search"></i> Search Flights
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="deals-empty">
                    <div class="deals-empty-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h2>New Deals Coming Soon!</h2>
                    <p>We're preparing exciting offers for you. Check back soon or subscribe to get notified.</p>
                    <a href="{{ url('/') }}" class="deals-empty-btn">
                        <i class="fas fa-plane"></i> Search Flights Now
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Why Book With Us --}}
    <section class="deals-why-section">
        <div class="container">
            <h2 class="deals-why-title">Why Book Deals with FaithTrip?</h2>
            <div class="deals-why-grid">
                <div class="deals-why-card">
                    <div class="deals-why-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>100% Verified Deals</h3>
                    <p>Every deal is verified and sourced directly from airlines & GDS systems.</p>
                </div>
                <div class="deals-why-card">
                    <div class="deals-why-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3>Best Price Guarantee</h3>
                    <p>We match the lowest available fares with exclusive OTA discounts on top.</p>
                </div>
                <div class="deals-why-card">
                    <div class="deals-why-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Instant Confirmation</h3>
                    <p>Book with deal prices and receive instant e-ticket confirmation.</p>
                </div>
                <div class="deals-why-card">
                    <div class="deals-why-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our travel experts are available around the clock to assist you.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter CTA --}}
    <section class="deals-newsletter">
        <div class="container">
            <div class="deals-newsletter-card">
                <div class="deals-newsletter-content">
                    <h2><i class="fas fa-bell"></i> Never Miss a Deal</h2>
                    <p>Get exclusive deals and flight offers delivered straight to your inbox.</p>
                </div>
                <div class="deals-newsletter-action">
                    <a href="{{ url('/') }}" class="deals-newsletter-btn">
                        <i class="fas fa-plane-departure"></i> Start Searching Flights
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ━━━ DEALS PAGE STYLES ━━━ */

        /* Hero */
        .deals-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1a1f3d 100%);
            padding: 120px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .deals-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(244, 114, 182, 0.1) 0%, transparent 50%);
        }

        .deals-hero-content {
            position: relative;
            z-index: 1;
        }

        .deals-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .deals-hero h1 {
            color: #fff;
            font-size: 2.6rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .deals-hero-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 32px;
            line-height: 1.6;
        }

        .deals-hero-stats {
            display: inline-flex;
            align-items: center;
            gap: 32px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px 40px;
            backdrop-filter: blur(10px);
        }

        .deals-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .deals-stat-num {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .deals-stat-label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .deals-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
        }

        /* Deals Section */
        .deals-section {
            background: #f1f5f9;
            padding: 60px 0 80px;
        }

        /* Grid */
        .deals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        /* Featured card spans 2 columns */
        .deal-card-featured {
            grid-column: span 2;
        }

        /* Card */
        .deal-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .deal-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            border-color: #6366f1;
        }

        .deal-card-image {
            position: relative;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .deal-card-featured .deal-card-image {
            height: 260px;
        }

        .deal-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .deal-card-icon {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .deal-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            color: #fff;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        }

        .deal-timer {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
        }

        .deal-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .deal-card-title {
            color: #1e293b;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .deal-card-featured .deal-card-title {
            font-size: 1.3rem;
        }

        .deal-card-desc {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
            flex: 1;
            margin-bottom: 16px;
        }

        .deal-card-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .deal-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #6366f1;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .deal-card-btn:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        /* Empty State */
        .deals-empty {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 20px;
            border: 2px dashed #e2e8f0;
        }

        .deals-empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 24px;
        }

        .deals-empty h2 {
            color: #1e293b;
            font-size: 1.5rem;
            margin-bottom: 12px;
        }

        .deals-empty p {
            color: #64748b;
            max-width: 400px;
            margin: 0 auto 24px;
        }

        .deals-empty-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #6366f1;
            color: #fff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .deals-empty-btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }

        /* Why Book Section */
        .deals-why-section {
            background: #fff;
            padding: 80px 0;
        }

        .deals-why-title {
            text-align: center;
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 48px;
        }

        .deals-why-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .deals-why-card {
            text-align: center;
            padding: 32px 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .deals-why-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .deals-why-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin: 0 auto 16px;
        }

        .deals-why-card h3 {
            color: #1e293b;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .deals-why-card p {
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.5;
            margin: 0;
        }

        /* Newsletter */
        .deals-newsletter {
            background: #f1f5f9;
            padding: 0 0 80px;
        }

        .deals-newsletter-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1a1f3d 100%);
            border-radius: 20px;
            padding: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        .deals-newsletter-content h2 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .deals-newsletter-content h2 i {
            color: #f59e0b;
            margin-right: 8px;
        }

        .deals-newsletter-content p {
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
        }

        .deals-newsletter-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #6366f1;
            color: #fff !important;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .deals-newsletter-btn:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .deals-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .deal-card-featured {
                grid-column: span 2;
            }

            .deals-why-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .deals-hero h1 {
                font-size: 1.8rem;
            }

            .deals-hero-stats {
                flex-direction: column;
                gap: 16px;
                padding: 20px 32px;
            }

            .deals-stat-divider {
                width: 40px;
                height: 1px;
            }

            .deals-grid {
                grid-template-columns: 1fr;
            }

            .deal-card-featured {
                grid-column: span 1;
            }

            .deals-why-grid {
                grid-template-columns: 1fr;
            }

            .deals-newsletter-card {
                flex-direction: column;
                text-align: center;
                padding: 32px;
            }
        }
    </style>
@endsection