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
.tb-right{display:flex;align-items:center;gap:12px;margin-left:auto;flex-shrink:0;}
.tb-bell{
    background:rgba(255,255,255,.08);border:none;
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    color:rgba(255,255,255,.7);font-size:15px;cursor:pointer;
    transition:all .15s;
}
.tb-bell:hover{background:rgba(255,255,255,.15);color:#fff;}

/* Profile dropdown */
.tb-profile-wrap{position:relative;}
.tb-user-btn{
    display:flex;align-items:center;gap:10px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    border-radius:8px;
    padding:5px 12px 5px 5px;
    cursor:pointer;transition:all .15s;
}
.tb-user-btn:hover{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.25);}
.tb-avatar{
    width:34px;height:34px;border-radius:50%;
    background:#1a3a6b;border:2px solid rgba(255,255,255,.2);
    display:flex;align-items:center;justify-content:center;
    font-size:14px;font-weight:700;color:#fff;flex-shrink:0;
    overflow:hidden;
}
.tb-avatar img{width:100%;height:100%;object-fit:cover;}
.tb-user-info .tb-uname{
    color:#fff;font-size:12px;font-weight:700;
    line-height:1.2;letter-spacing:.3px;
    max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.tb-user-info .tb-ubal{color:#f0a500;font-size:11px;font-weight:700;}

.tb-dropdown{
    display:none;position:absolute;top:calc(100% + 8px);right:0;
    background:#fff;border:1px solid #e5e7eb;border-radius:10px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    min-width:190px;z-index:9999;overflow:hidden;
}
.tb-dropdown.show{display:block;}
.tb-dropdown-header{
    padding:14px 16px 10px;
    border-bottom:1px solid #f3f4f6;
}
.tb-dropdown-header .dh-name{font-size:14px;font-weight:700;color:#0f1f3d;}
.tb-dropdown-header .dh-email{font-size:11px;color:#6b7280;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.tb-dropdown-item{
    display:flex;align-items:center;gap:10px;
    padding:10px 16px;font-size:13px;color:#374151;
    text-decoration:none;transition:background .12s;
    border:none;background:transparent;width:100%;cursor:pointer;
}
.tb-dropdown-item:hover{background:#f9fafb;text-decoration:none;}
.tb-dropdown-item i{font-size:14px;width:16px;color:#6b7280;}
.tb-dropdown-divider{border-top:1px solid #f3f4f6;margin:2px 0;}
.tb-dropdown-item.danger{color:#dc2626;}
.tb-dropdown-item.danger i{color:#dc2626;}
.tb-dropdown-item.danger:hover{background:#fef2f2;}
</style>

<nav class="b2b-topbar">
    {{-- Left: Page Nav Buttons --}}
    <div class="tb-nav">
        <a href="{{ url('/home') }}" class="tb-btn {{ $currentRoute == 'home' ? 'active' : '' }}">
            <i class="fas fa-plane"></i> Flight Search
        </a>
        <a href="{{ url('tours-search') }}" class="tb-btn {{ $currentRoute == 'TourSearch' ? 'active' : '' }}">
            <i class="fas fa-umbrella-beach"></i> Tours Search
        </a>
        <a href="{{ url('visa-search') }}" class="tb-btn {{ $currentRoute == 'VisaSearch' ? 'active' : '' }}">
            <i class="far fa-id-card"></i> Visa Search
        </a>
    </div>

    {{-- Center: Global Search --}}
    <div class="tb-search">
        <form method="GET" action="{{ url('my/bookings') }}" class="tb-search-form">
            <span class="tb-si"><i class="fas fa-search"></i></span>
            <input type="text" name="search"
                   placeholder="PNR / Ticket NO / Booking Ref"
                   value="{{ request()->has('search') ? request('search') : '' }}"
                   autocomplete="off">
        </form>
    </div>

    {{-- Right: Bell + Profile Dropdown --}}
    <div class="tb-right">
        <button class="tb-bell" title="Notifications">
            <i class="fas fa-bell"></i>
        </button>

        <div class="tb-profile-wrap" id="tbProfileWrap">
            <div class="tb-user-btn" onclick="toggleTbDropdown()" id="tbUserBtn">
                <div class="tb-avatar">
                    @if(Auth::user()->image && file_exists(public_path(Auth::user()->image)))
                        <img src="{{ asset(Auth::user()->image) }}" alt="">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="tb-user-info">
                    <div class="tb-uname">{{ strtoupper(Str::limit(Auth::user()->name, 14)) }}</div>
                    <div class="tb-ubal">{{ number_format(Auth::user()->balance ?? 0, 2) }} BDT</div>
                </div>
            </div>

            <div class="tb-dropdown" id="tbDropdown">
                <div class="tb-dropdown-header">
                    <div class="dh-name">{{ Auth::user()->name }}</div>
                    <div class="dh-email">{{ Auth::user()->email }}</div>
                </div>
                <a href="{{ url('my/account') }}" class="tb-dropdown-item">
                    <i class="fas fa-user-circle"></i> My Account
                </a>
                <a href="{{ url('my/account') }}#change-password" class="tb-dropdown-item" onclick="openChangePassword(event)">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <div class="tb-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" id="tb-logout-form">
                    @csrf
                    <button type="submit" class="tb-dropdown-item danger">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleTbDropdown() {
    document.getElementById('tbDropdown').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('tbProfileWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('tbDropdown')?.classList.remove('show');
    }
});
function openChangePassword(e) {
    if (window.location.pathname === '/my/account') {
        e.preventDefault();
        document.getElementById('tbDropdown')?.classList.remove('show');
        const btn = document.getElementById('changePwdBtn');
        if (btn) btn.click();
    }
}
</script>
