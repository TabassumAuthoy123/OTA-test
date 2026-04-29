@php
    $companyProfile = App\Models\CompanyProfile::where('user_id', Auth::user()->id)->first();
    $currentRoute = request()->route()->getName();
    $currentPath  = request()->path();
@endphp

<style>
/* ─── B2B Sidebar ─── */
.b2b-sidebar {
    width: 240px;
    min-height: 100vh;
    background: #0f1f3d;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0;
    z-index: 1000;
    overflow-y: auto;
    box-shadow: 3px 0 15px rgba(0,0,0,.3);
}
.b2b-sidebar::-webkit-scrollbar { width: 4px; }
.b2b-sidebar::-webkit-scrollbar-thumb { background: #2a4a7f; border-radius: 4px; }

/* Logo */
.b2b-logo {
    padding: 18px 20px 14px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: 10px;
    text-decoration: none;
}
.b2b-logo img { height: 36px; max-width: 140px; object-fit: contain; }
.b2b-logo-text { color: #fff; font-size: 20px; font-weight: 800; letter-spacing: .5px; }
.b2b-logo-text span { color: #f0a500; }

/* User info */
.b2b-user-card {
    margin: 12px 14px;
    background: rgba(255,255,255,.07);
    border-radius: 8px;
    padding: 12px 14px;
    display: flex; align-items: center; gap: 10px;
}
.b2b-user-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: #f0a500; display: flex; align-items: center;
    justify-content: center; font-size: 16px; font-weight: 700; color: #0f1f3d;
    flex-shrink: 0;
}
.b2b-user-info .name { color: #fff; font-size: 13px; font-weight: 600; line-height: 1.2; }
.b2b-user-info .balance { color: #f0a500; font-size: 12px; font-weight: 600; margin-top: 2px; }
.b2b-user-info .role { color: rgba(255,255,255,.5); font-size: 10px; }

/* Nav */
.b2b-nav { padding: 8px 0 80px; }
.b2b-nav ul { list-style: none; margin: 0; padding: 0; }
.b2b-nav-label {
    padding: 14px 18px 6px;
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    color: rgba(255,255,255,.3); letter-spacing: 1px;
}

/* Nav items */
.b2b-nav-item > a,
.b2b-nav-item > .b2b-nav-toggle {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 18px;
    color: rgba(255,255,255,.75);
    text-decoration: none;
    font-size: 13px; font-weight: 500;
    border-radius: 6px;
    margin: 1px 8px;
    transition: all .2s;
    cursor: pointer;
    background: none; border: none; width: calc(100% - 16px); text-align: left;
}
.b2b-nav-item > a:hover,
.b2b-nav-item > .b2b-nav-toggle:hover {
    background: rgba(255,255,255,.08);
    color: #fff;
}
.b2b-nav-item.active > a,
.b2b-nav-item.active > .b2b-nav-toggle {
    background: #f0a500;
    color: #0f1f3d;
    font-weight: 700;
}
.b2b-nav-item.active > a i,
.b2b-nav-item.active > .b2b-nav-toggle i { color: #0f1f3d; }
.b2b-nav-item > a i,
.b2b-nav-item > .b2b-nav-toggle i { font-size: 15px; width: 18px; text-align: center; color: rgba(255,255,255,.5); }
.b2b-nav-item.active > a i { color: #0f1f3d; }
.b2b-chevron { margin-left: auto; font-size: 11px; transition: transform .2s; }
.b2b-chevron.open { transform: rotate(90deg); }

/* Sub menu */
.b2b-submenu { display: none; }
.b2b-submenu.open { display: block; }
.b2b-submenu a {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 18px 8px 44px;
    color: rgba(255,255,255,.6);
    text-decoration: none; font-size: 12px;
    margin: 1px 8px;
    border-radius: 5px;
    transition: all .2s;
}
.b2b-submenu a:hover { background: rgba(255,255,255,.06); color: #fff; }
.b2b-submenu a.active { color: #f0a500; font-weight: 600; }
.b2b-submenu a::before { content: '•'; font-size: 10px; color: rgba(255,255,255,.3); }
.b2b-submenu a.active::before { color: #f0a500; }

/* Badge */
.b2b-badge {
    margin-left: auto;
    background: #f0a500; color: #0f1f3d;
    font-size: 10px; font-weight: 700;
    padding: 2px 6px; border-radius: 10px;
    line-height: 1.4;
}
.b2b-badge.coming {
    background: rgba(255,255,255,.15); color: rgba(255,255,255,.5);
    font-size: 9px;
}

/* Logout footer */
.b2b-logout {
    position: fixed;
    bottom: 0; left: 0; width: 240px;
    padding: 12px 14px;
    background: #0a1628;
    border-top: 1px solid rgba(255,255,255,.08);
}
.b2b-logout a {
    display: flex; align-items: center; gap: 8px;
    color: rgba(255,255,255,.6); text-decoration: none;
    font-size: 13px; padding: 8px 12px; border-radius: 6px;
    transition: all .2s;
}
.b2b-logout a:hover { background: rgba(220,53,69,.15); color: #ff6b6b; }

/* Content offset */
.b2b-content-wrapper { margin-left: 240px; }
</style>

<aside class="b2b-sidebar">

    {{-- Logo --}}
    <a href="{{ url('/home') }}" class="b2b-logo">
        @if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo)))
            <img src="{{ url($companyProfile->logo) }}" alt="logo">
        @else
            <span class="b2b-logo-text">OTA<span>platform</span></span>
        @endif
    </a>

    {{-- User card --}}
    <div class="b2b-user-card">
        <div class="b2b-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div class="b2b-user-info">
            <div class="name">{{ Str::limit(Auth::user()->name, 18) }}</div>
            <div class="balance">{{ number_format(Auth::user()->balance, 2) }} BDT</div>
            <div class="role">B2B Agent</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="b2b-nav">
        <ul>

            {{-- MAIN --}}
            <li class="b2b-nav-label">Main</li>

            <li class="b2b-nav-item {{ $currentRoute == 'home' ? 'active' : '' }}">
                <a href="{{ url('/home') }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>

            {{-- MY BOOKINGS --}}
            <li class="b2b-nav-label">Bookings & Tickets</li>

            <li class="b2b-nav-item {{ in_array($currentRoute, ['ViewAllBooking','ViewCancelBooking','ViewIssuedTickets','ViewCancelledTickets','ArchivedIssuedTickets']) ? 'active' : '' }}">
                <button class="b2b-nav-toggle" onclick="toggleSub('sub-bookings', this)">
                    <i class="fas fa-ticket-alt"></i>
                    My Bookings
                    <i class="fas fa-chevron-right b2b-chevron {{ in_array($currentRoute, ['ViewAllBooking','ViewCancelBooking','ViewIssuedTickets','ViewCancelledTickets','ArchivedIssuedTickets']) ? 'open' : '' }}"></i>
                </button>
                <div class="b2b-submenu {{ in_array($currentRoute, ['ViewAllBooking','ViewCancelBooking','ViewIssuedTickets','ViewCancelledTickets','ArchivedIssuedTickets']) ? 'open' : '' }}" id="sub-bookings">
                    <a href="{{ url('view/all/booking') }}" class="{{ $currentRoute == 'ViewAllBooking' ? 'active' : '' }}">All Bookings</a>
                    <a href="{{ url('view/issued/tickets') }}" class="{{ $currentRoute == 'ViewIssuedTickets' ? 'active' : '' }}">Issued Tickets</a>
                    <a href="{{ url('view/cancel/booking') }}" class="{{ $currentRoute == 'ViewCancelBooking' ? 'active' : '' }}">Cancelled Bookings</a>
                    <a href="{{ url('view/cancelled/tickets') }}" class="{{ $currentRoute == 'ViewCancelledTickets' ? 'active' : '' }}">Void / Cancelled Tickets</a>
                    <a href="{{ url('archived/issued/tickets') }}" class="{{ $currentRoute == 'ArchivedIssuedTickets' ? 'active' : '' }}">Archived Tickets</a>
                </div>
            </li>

            <li class="b2b-nav-item {{ $currentRoute == 'MyUpcomingFlights' ? 'active' : '' }}">
                <a href="{{ url('my/upcoming-flights') }}">
                    <i class="fas fa-plane-departure"></i> Upcoming Flights
                </a>
            </li>

            <li class="b2b-nav-item {{ $currentRoute == 'MyPartialPayBookings' ? 'active' : '' }}">
                <a href="{{ url('my/partial-pay-bookings') }}">
                    <i class="fas fa-credit-card"></i> Partial Pay Bookings
                </a>
            </li>

            <li class="b2b-nav-item {{ $currentRoute == 'SavedPassengers' ? 'active' : '' }}">
                <a href="{{ url('view/saved/passengers') }}">
                    <i class="fas fa-users"></i> Travelers
                </a>
            </li>

            {{-- FINANCE --}}
            <li class="b2b-nav-label">Finance</li>

            <li class="b2b-nav-item {{ in_array($currentRoute, ['CreateTopupRequest','ViewRechargeRequests']) ? 'active' : '' }}">
                <button class="b2b-nav-toggle" onclick="toggleSub('sub-topup', this)">
                    <i class="fas fa-wallet"></i>
                    TopUp / Recharge
                    <i class="fas fa-chevron-right b2b-chevron {{ in_array($currentRoute, ['CreateTopupRequest','ViewRechargeRequests']) ? 'open' : '' }}"></i>
                </button>
                <div class="b2b-submenu {{ in_array($currentRoute, ['CreateTopupRequest','ViewRechargeRequests']) ? 'open' : '' }}" id="sub-topup">
                    <a href="{{ url('create/topup/request') }}" class="{{ $currentRoute == 'CreateTopupRequest' ? 'active' : '' }}">Submit TopUp Request</a>
                    <a href="{{ url('view/recharge/requests') }}" class="{{ $currentRoute == 'ViewRechargeRequests' ? 'active' : '' }}">TopUp History</a>
                </div>
            </li>

            <li class="b2b-nav-item {{ $currentRoute == 'FlightBookingReport' ? 'active' : '' }}">
                <a href="{{ url('flight/booking/report') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>

            {{-- ACCOUNT --}}
            <li class="b2b-nav-label">Account</li>

            <li class="b2b-nav-item {{ $currentRoute == 'MyProfile' ? 'active' : '' }}">
                <a href="{{ url('/my/profile') }}">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </li>

            <li class="b2b-nav-item {{ $currentRoute == 'CompanyProfile' ? 'active' : '' }}">
                <a href="{{ url('/company/profile') }}">
                    <i class="fas fa-building"></i> Company Profile
                </a>
            </li>

            {{-- PAYMENT METHODS --}}
            <li class="b2b-nav-item {{ in_array($currentRoute, ['ViewBankAccounts','ViewMfsAccounts']) ? 'active' : '' }}">
                <button class="b2b-nav-toggle" onclick="toggleSub('sub-payments', this)">
                    <i class="fas fa-university"></i>
                    Payment Accounts
                    <i class="fas fa-chevron-right b2b-chevron {{ in_array($currentRoute, ['ViewBankAccounts','ViewMfsAccounts']) ? 'open' : '' }}"></i>
                </button>
                <div class="b2b-submenu {{ in_array($currentRoute, ['ViewBankAccounts','ViewMfsAccounts']) ? 'open' : '' }}" id="sub-payments">
                    <a href="{{ url('view/bank/accounts') }}" class="{{ $currentRoute == 'ViewBankAccounts' ? 'active' : '' }}">Bank Accounts</a>
                    <a href="{{ url('view/mfs/accounts') }}" class="{{ $currentRoute == 'ViewMfsAccounts' ? 'active' : '' }}">MFS Accounts</a>
                </div>
            </li>

        </ul>
    </nav>

    {{-- Logout --}}
    <div class="b2b-logout">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('b2b-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
        <form id="b2b-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

</aside>

<script>
function toggleSub(id, btn) {
    const sub = document.getElementById(id);
    const chevron = btn.querySelector('.b2b-chevron');
    const isOpen = sub.classList.contains('open');
    sub.classList.toggle('open', !isOpen);
    chevron.classList.toggle('open', !isOpen);
}
</script>
