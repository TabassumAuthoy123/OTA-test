@php
    $companyProfile = App\Models\CompanyProfile::where('user_id', Auth::user()->id)->first();
    $currentRoute = request()->route()->getName();
    $currentPath  = request()->path();
@endphp

<style>
/* ─── B2B Sidebar ─── */
.b2b-sidebar{
    width:240px;height:100vh;
    background:#0f1f3d;
    display:flex;flex-direction:column;
    position:fixed;top:0;left:0;
    z-index:1000;
    box-shadow:3px 0 15px rgba(0,0,0,.3);
    overflow:hidden;
}
.b2b-sidebar-inner{
    display:flex;flex-direction:column;
    height:100%;overflow-y:auto;
    scrollbar-width:thin;scrollbar-color:#2a4a7f transparent;
}
.b2b-sidebar-inner::-webkit-scrollbar{width:4px;}
.b2b-sidebar-inner::-webkit-scrollbar-thumb{background:#2a4a7f;border-radius:4px;}

.b2b-logo{
    padding:14px 18px 12px;
    border-bottom:1px solid rgba(255,255,255,.08);
    display:flex;align-items:center;gap:10px;
    text-decoration:none;flex-shrink:0;
}
.b2b-logo img{height:34px;max-width:130px;object-fit:contain;}
.b2b-logo-text{color:#fff;font-size:19px;font-weight:800;letter-spacing:.5px;}
.b2b-logo-text span{color:#f0a500;}

.b2b-user-card{
    margin:10px 12px;
    background:rgba(255,255,255,.07);
    border-radius:8px;padding:10px 12px;
    display:flex;align-items:center;gap:10px;flex-shrink:0;
}
.b2b-user-avatar{
    width:34px;height:34px;border-radius:50%;
    background:#f0a500;display:flex;align-items:center;
    justify-content:center;font-size:14px;font-weight:700;
    color:#0f1f3d;flex-shrink:0;
}
.b2b-user-info .uname{color:#fff;font-size:12px;font-weight:600;line-height:1.2;}
.b2b-user-info .ubal{color:#f0a500;font-size:11px;font-weight:700;margin-top:2px;}

.b2b-nav{padding:4px 0 16px;flex:1;}
.b2b-nav ul{list-style:none;margin:0;padding:0;}

.b2b-nav-item>a,
.b2b-nav-item>.b2b-nav-toggle{
    display:flex;align-items:center;gap:10px;
    padding:9px 16px;
    color:rgba(255,255,255,.78);
    text-decoration:none;font-size:13px;font-weight:500;
    transition:all .15s;cursor:pointer;
    background:none;border:none;width:100%;text-align:left;
}
.b2b-nav-item>a:hover,
.b2b-nav-item>.b2b-nav-toggle:hover{background:rgba(255,255,255,.07);color:#fff;}
.b2b-nav-item.active>a,
.b2b-nav-item.active>.b2b-nav-toggle{
    background:rgba(240,165,0,.15);color:#f0a500;
    font-weight:700;border-left:3px solid #f0a500;
}
.b2b-nav-item>a i.ni,.b2b-nav-item>.b2b-nav-toggle i.ni{
    font-size:14px;width:16px;text-align:center;
    color:rgba(255,255,255,.4);flex-shrink:0;
}
.b2b-nav-item.active>a i.ni,
.b2b-nav-item.active>.b2b-nav-toggle i.ni{color:#f0a500;}

.b2b-chevron{margin-left:auto;font-size:10px;transition:transform .2s;color:rgba(255,255,255,.3)!important;}
.b2b-chevron.open{transform:rotate(90deg);}

.b2b-submenu{display:none;background:rgba(0,0,0,.18);}
.b2b-submenu.open{display:block;}
.b2b-submenu a{
    display:flex;align-items:center;gap:8px;
    padding:8px 16px 8px 42px;
    color:rgba(255,255,255,.5);
    text-decoration:none;font-size:12px;
    transition:all .15s;border-left:3px solid transparent;
}
.b2b-submenu a::before{content:'→';font-size:10px;color:rgba(255,255,255,.2);}
.b2b-submenu a:hover{background:rgba(255,255,255,.05);color:rgba(255,255,255,.85);}
.b2b-submenu a.active{color:#f0a500;font-weight:600;border-left-color:#f0a500;background:rgba(240,165,0,.08);}
.b2b-submenu a.active::before{color:#f0a500;}

.b2b-logout{
    flex-shrink:0;
    padding:10px 12px;
    background:#0a1628;
    border-top:1px solid rgba(255,255,255,.08);
}
.b2b-logout a{
    display:flex;align-items:center;gap:8px;
    color:rgba(255,255,255,.5);text-decoration:none;
    font-size:13px;padding:7px 12px;border-radius:5px;
    transition:all .15s;
}
.b2b-logout a:hover{background:rgba(220,53,69,.12);color:#ff6b6b;}

.b2b-content-wrapper{margin-left:240px;}
</style>

<aside class="b2b-sidebar">
  <div class="b2b-sidebar-inner">

    {{-- Logo --}}
    <a href="{{ url('my/dashboard') }}" class="b2b-logo">
        @if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo)))
            <img src="{{ url($companyProfile->logo) }}" alt="logo">
        @else
            <span class="b2b-logo-text">{{ env('APP_NAME','OTA') }}</span>
        @endif
    </a>

    {{-- User card --}}
    <div class="b2b-user-card">
        <div class="b2b-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div class="b2b-user-info">
            <div class="uname">{{ Str::limit(Auth::user()->name, 20) }}</div>
            <div class="ubal">{{ number_format(Auth::user()->balance ?? 0, 2) }} BDT</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="b2b-nav">
      <ul>

        {{-- 1. Dashboard --}}
        <li class="b2b-nav-item {{ $currentRoute == 'MyDashboard' ? 'active' : '' }}">
          <a href="{{ url('my/dashboard') }}">
            <i class="fas fa-th-large ni"></i> Dashboard
          </a>
        </li>

        {{-- 2. My Bookings --}}
        @php $bookActive = in_array($currentRoute, ['MyBookings','MyPendingBookings','MyApprovedBookings','MyBookingDetail']); @endphp
        <li class="b2b-nav-item {{ $bookActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-mybookings',this)">
            <i class="fas fa-ticket-alt ni"></i> My Bookings
            <i class="fas fa-chevron-right b2b-chevron {{ $bookActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $bookActive ? 'open' : '' }}" id="sub-mybookings">
            <a href="{{ url('my/bookings') }}" class="{{ $currentRoute=='MyBookings'?'active':'' }}">All Request Booking</a>
            <a href="{{ url('my/bookings/pending') }}" class="{{ $currentRoute=='MyPendingBookings'?'active':'' }}">Pending Booking</a>
            <a href="{{ url('my/bookings/approved') }}" class="{{ $currentRoute=='MyApprovedBookings'?'active':'' }}">Approved Booking</a>
          </div>
        </li>

        {{-- 3. Reissued --}}
        @php $reissueActive = in_array($currentRoute, ['MyReissueNew','MyReissueInProcess','MyReissueConfirmed','MyCreateReissue']); @endphp
        <li class="b2b-nav-item {{ $reissueActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-reissued',this)">
            <i class="fas fa-redo ni"></i> Reissued
            <i class="fas fa-chevron-right b2b-chevron {{ $reissueActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $reissueActive ? 'open' : '' }}" id="sub-reissued">
            <a href="{{ url('my/reissue/new') }}" class="{{ $currentRoute=='MyReissueNew'?'active':'' }}">New Request</a>
            <a href="{{ url('my/reissue/in-process') }}" class="{{ $currentRoute=='MyReissueInProcess'?'active':'' }}">In Process</a>
            <a href="{{ url('my/reissue/confirmed') }}" class="{{ $currentRoute=='MyReissueConfirmed'?'active':'' }}">Confirm</a>
          </div>
        </li>

        {{-- 4. Refunded --}}
        @php $refundActive = in_array($currentRoute, ['MyRefundNew','MyRefundInProcess','MyRefundConfirmed','MyCreateRefund']); @endphp
        <li class="b2b-nav-item {{ $refundActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-refunded',this)">
            <i class="fas fa-hand-holding-usd ni"></i> Refunded
            <i class="fas fa-chevron-right b2b-chevron {{ $refundActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $refundActive ? 'open' : '' }}" id="sub-refunded">
            <a href="{{ url('my/refund/new') }}" class="{{ $currentRoute=='MyRefundNew'?'active':'' }}">New Request</a>
            <a href="{{ url('my/refund/in-process') }}" class="{{ $currentRoute=='MyRefundInProcess'?'active':'' }}">In Process</a>
            <a href="{{ url('my/refund/confirmed') }}" class="{{ $currentRoute=='MyRefundConfirmed'?'active':'' }}">Confirm</a>
          </div>
        </li>

        {{-- 5. Void Request --}}
        @php $voidActive = in_array($currentRoute, ['MyVoidNew','MyVoidInProcess','MyVoidConfirmed','MyCreateVoid']); @endphp
        <li class="b2b-nav-item {{ $voidActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-void',this)">
            <i class="fas fa-ban ni"></i> Void Request
            <i class="fas fa-chevron-right b2b-chevron {{ $voidActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $voidActive ? 'open' : '' }}" id="sub-void">
            <a href="{{ url('my/void/new') }}" class="{{ $currentRoute=='MyVoidNew'?'active':'' }}">New Request</a>
            <a href="{{ url('my/void/in-process') }}" class="{{ $currentRoute=='MyVoidInProcess'?'active':'' }}">In Process</a>
            <a href="{{ url('my/void/confirmed') }}" class="{{ $currentRoute=='MyVoidConfirmed'?'active':'' }}">Confirm</a>
          </div>
        </li>

        {{-- 6. Tour Bookings --}}
        @php $tourActive = in_array($currentRoute, ['MyTourBookings','MyTourApproved','MyTourPending']); @endphp
        <li class="b2b-nav-item {{ $tourActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-tour',this)">
            <i class="fas fa-umbrella-beach ni"></i> Tour Bookings
            <i class="fas fa-chevron-right b2b-chevron {{ $tourActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $tourActive ? 'open' : '' }}" id="sub-tour">
            <a href="{{ url('my/tour-bookings') }}" class="{{ $currentRoute=='MyTourBookings'?'active':'' }}">All Booking</a>
            <a href="{{ url('my/tour-bookings/approved') }}" class="{{ $currentRoute=='MyTourApproved'?'active':'' }}">Approved Booking</a>
            <a href="{{ url('my/tour-bookings/pending') }}" class="{{ $currentRoute=='MyTourPending'?'active':'' }}">Pending Booking</a>
          </div>
        </li>

        {{-- 7. Visa Application List --}}
        <li class="b2b-nav-item {{ $currentRoute=='MyVisaApplications'?'active':'' }}">
          <a href="{{ url('my/visa-applications') }}">
            <i class="fas fa-passport ni"></i> Visa Application List
          </a>
        </li>

        {{-- 8. TopUp Request --}}
        <li class="b2b-nav-item {{ in_array($currentRoute,['CreateTopupRequest','ViewRechargeRequests'])?'active':'' }}">
          <a href="{{ url('create/topup/request') }}">
            <i class="fas fa-wallet ni"></i> TopUp Request
          </a>
        </li>

        {{-- 9. Invoice --}}
        <li class="b2b-nav-item {{ $currentRoute=='FlightBookingReport'?'active':'' }}">
          <a href="{{ url('flight/booking/report') }}">
            <i class="fas fa-file-invoice ni"></i> Invoice
          </a>
        </li>

        {{-- 10. Upcoming Flights --}}
        <li class="b2b-nav-item {{ $currentRoute=='MyUpcomingFlights'?'active':'' }}">
          <a href="{{ url('my/upcoming-flights') }}">
            <i class="fas fa-plane-departure ni"></i> Upcoming Flights
          </a>
        </li>

        {{-- 11. Partial Pay Booking --}}
        <li class="b2b-nav-item {{ $currentRoute=='MyPartialPayBookings'?'active':'' }}">
          <a href="{{ url('my/partial-pay-bookings') }}">
            <i class="fas fa-credit-card ni"></i> Partial Pay Booking
          </a>
        </li>

        {{-- 12. Travelers --}}
        <li class="b2b-nav-item {{ $currentRoute=='SavedPassengers'?'active':'' }}">
          <a href="{{ url('view/saved/passengers') }}">
            <i class="fas fa-users ni"></i> Travelers
          </a>
        </li>

        {{-- 13. Booking Support --}}
        <li class="b2b-nav-item {{ in_array($currentRoute,['MyBookingSupport','MyCreateSupportTicket'])?'active':'' }}">
          <a href="{{ url('my/booking-support') }}">
            <i class="fas fa-headset ni"></i> Booking Support
          </a>
        </li>

        {{-- 14. Administrator --}}
        @php $adminActive = in_array($currentRoute, ['MyAgencyUsers','MyAgencyRoles']); @endphp
        <li class="b2b-nav-item {{ $adminActive ? 'active' : '' }}">
          <button class="b2b-nav-toggle" onclick="toggleSub('sub-administrator',this)">
            <i class="fas fa-user-shield ni"></i> Administrator
            <i class="fas fa-chevron-right b2b-chevron {{ $adminActive ? 'open' : '' }}"></i>
          </button>
          <div class="b2b-submenu {{ $adminActive ? 'open' : '' }}" id="sub-administrator">
            <a href="{{ url('my/agency/users') }}" class="{{ $currentRoute=='MyAgencyUsers'?'active':'' }}">User</a>
            <a href="{{ url('my/agency/roles') }}" class="{{ $currentRoute=='MyAgencyRoles'?'active':'' }}">Role</a>
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
