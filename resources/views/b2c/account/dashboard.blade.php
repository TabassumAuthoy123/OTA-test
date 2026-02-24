@extends('b2c.layouts.master')

@section('title', 'My Account')

@section('styles')
    <style>
        .b2c-account-page {
            padding-top: 100px;
            padding-bottom: 60px;
            min-height: 80vh;
        }

        .b2c-account-header {
            background: var(--b2c-gradient-hero);
            border-radius: var(--b2c-radius-xl);
            padding: 40px;
            margin-bottom: 32px;
            color: #fff;
        }

        .b2c-account-header h2 {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .b2c-account-header p {
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
        }

        .b2c-account-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .b2c-account-card {
            background: var(--b2c-card);
            border-radius: var(--b2c-radius-lg);
            padding: 32px;
            border: 1px solid var(--b2c-card-border);
            text-align: center;
            transition: var(--b2c-transition);
        }

        .b2c-account-card:hover {
            box-shadow: var(--b2c-shadow-md);
            transform: translateY(-2px);
        }

        .b2c-account-card i {
            font-size: 2.5rem;
            margin-bottom: 16px;
        }

        .b2c-account-card h4 {
            font-family: var(--font-heading);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .b2c-account-card p {
            font-size: 0.85rem;
            color: var(--b2c-text-muted);
            margin-bottom: 16px;
        }

        .b2c-account-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 24px;
            border-radius: var(--b2c-radius-full);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--b2c-transition);
            border: 1.5px solid var(--b2c-accent);
            color: var(--b2c-accent);
            background: transparent;
        }

        .b2c-account-btn:hover {
            background: var(--b2c-accent);
            color: #fff;
        }

        .b2c-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: var(--b2c-radius-full);
            font-size: 0.85rem;
            background: rgba(239, 68, 68, 0.1);
            color: var(--b2c-danger);
            border: none;
            cursor: pointer;
            transition: var(--b2c-transition);
        }

        .b2c-logout-btn:hover {
            background: var(--b2c-danger);
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="b2c-account-page">
        <div class="container">
            {{-- Welcome Header --}}
            <div class="b2c-account-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2>Welcome, {{ auth()->user()->name }}! 👋</h2>
                        <p>Manage your bookings and profile from here</p>
                    </div>
                    <form method="POST" action="{{ route('b2c.logout') }}">
                        @csrf
                        <button type="submit" class="b2c-logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- Dashboard Cards --}}
            <div class="b2c-account-cards">
                <div class="b2c-account-card">
                    <i class="fas fa-plane" style="color: var(--b2c-accent);"></i>
                    <h4>My Bookings</h4>
                    <p>View all your flight bookings, download e-tickets, and manage reservations.</p>
                    <a href="#" class="b2c-account-btn">
                        <i class="fas fa-list"></i> View Bookings
                    </a>
                </div>

                <div class="b2c-account-card">
                    <i class="fas fa-search" style="color: var(--b2c-success);"></i>
                    <h4>Search Flights</h4>
                    <p>Find and book new flights at the best prices with instant confirmation.</p>
                    <a href="{{ route('b2c.home') }}" class="b2c-account-btn">
                        <i class="fas fa-search"></i> Search Now
                    </a>
                </div>

                <div class="b2c-account-card">
                    <i class="fas fa-user-circle" style="color: var(--b2c-cta);"></i>
                    <h4>My Profile</h4>
                    <p>Update your personal details, email, phone number, and password.</p>
                    <a href="#" class="b2c-account-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection