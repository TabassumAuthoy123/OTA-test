@php $currentRoute = request()->route()->getName(); @endphp
<style>
.b2b-topbar{
    background:#0f1f3d;
    display:flex;align-items:center;gap:0;
    padding:0 20px;
    height:56px;
    border-bottom:2px solid #1a3a6b;
    flex-shrink:0;
    position:sticky;top:0;z-index:900;
}
.tb-nav{display:flex;align-items:center;gap:4px;flex-shrink:0;}
.tb-btn{
    display:inline-flex;align-items:center;gap:7px;
    padding:8px 16px;border-radius:6px;
    font-size:13px;font-weight:600;
    text-decoration:none;transition:all .15s;
    color:rgba(255,255,255,.75);background:transparent;
    border:none;cursor:pointer;
    white-space:nowrap;
}
.tb-btn:hover{background:rgba(255,255,255,.1);color:#fff;text-decoration:none;}
.tb-btn.active{background:#1a3a6b;color:#fff;border:1px solid rgba(255,255,255,.15);}
.tb-btn i{font-size:13px;}
.tb-search{flex:1;margin:0 24px;display:flex;align-items:center;min-width:0;}
.tb-search-form{
    display:flex;align-items:center;
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.15);
    border-radius:6px;
    width:100%;max-width:500px;
    overflow:hidden;
    transition:border-color .15s;
}
.tb-search-form:focus-within{border-color:rgba(255,255,255,.4);}
.tb-search-form .tb-si{
    color:rgba(255,255,255,.45);font-size:13px;
    padding:0 10px;flex-shrink:0;
}
.tb-search-form input{
    flex:1;background:transparent;border:none;outline:none;
    color:#fff;font-size:13px;padding:8px 0;
    min-width:0;
}
.tb-search-form input::placeholder{color:rgba(255,255,255,.4);}
.tb-search-form button{
    background:#1a3a6b;color:#fff;border:none;
    padding:8px 18px;font-size:13px;font-weight:600;
    cursor:pointer;flex-shrink:0;
    transition:background .15s;white-space:nowrap;
}
.tb-search-form button:hover{background:#243f73;}
.tb-right{display:flex;align-items:center;gap:12px;margin-left:auto;flex-shrink:0;}
.tb-bell{
    background:rgba(255,255,255,.08);border:none;
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    color:rgba(255,255,255,.7);font-size:15px;cursor:pointer;
    transition:all .15s;
}
.tb-bell:hover{background:rgba(255,255,255,.15);color:#fff;}
.tb-user{display:flex;align-items:center;gap:10px;}
.tb-avatar{
    width:36px;height:36px;border-radius:50%;
    background:#1a3a6b;border:2px solid rgba(255,255,255,.2);
    display:flex;align-items:center;justify-content:center;
    font-size:15px;font-weight:700;color:#fff;flex-shrink:0;
}
.tb-user-info .tb-uname{
    color:#fff;font-size:12px;font-weight:700;
    line-height:1.2;letter-spacing:.3px;
    max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.tb-user-info .tb-ubal{color:#f0a500;font-size:11px;font-weight:700;}
</style>

<nav class="b2b-topbar">
    {{-- Left: Page Nav Buttons --}}
    <div class="tb-nav">
        <a href="{{ url('/home') }}" class="tb-btn {{ $currentRoute == 'home' ? 'active' : '' }}">
            <i class="fas fa-plane"></i> Flight Search
        </a>
        <a href="#" class="tb-btn {{ $currentRoute == 'MyTourBookings' ? 'active' : '' }}">
            <i class="fas fa-umbrella-beach"></i> Tours Search
        </a>
        <a href="{{ url('my/visa-applications') }}" class="tb-btn {{ $currentRoute == 'MyVisaApplications' ? 'active' : '' }}">
            <i class="far fa-id-card"></i> Visa Search
        </a>
    </div>

    {{-- Center: Global Search --}}
    <div class="tb-search">
        <form method="GET" action="{{ url('my/bookings') }}" class="tb-search-form">
            <span class="tb-si"><i class="fas fa-search"></i></span>
            <input type="text" name="search"
                   placeholder="PNR / Ticket NO / Booking Ref / Name / Passport No"
                   value="{{ request()->has('search') ? request('search') : '' }}"
                   autocomplete="off">
            <button type="submit">Search</button>
        </form>
    </div>

    {{-- Right: Bell + User --}}
    <div class="tb-right">
        <button class="tb-bell" title="Notifications">
            <i class="fas fa-bell"></i>
        </button>
        <div class="tb-user">
            <div class="tb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="tb-user-info">
                <div class="tb-uname">{{ strtoupper(Auth::user()->name) }}</div>
                <div class="tb-ubal">{{ number_format(Auth::user()->balance ?? 0, 2) }} BDT</div>
            </div>
        </div>
    </div>
</nav>
