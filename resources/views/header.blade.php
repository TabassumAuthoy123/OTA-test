<nav class="navbar-custom-menu navbar navbar-expand-lg m-0">
    <div class="sidebar-toggle-icon d-lg-none" id="sidebarCollapse">
        sidebar toggle<span></span>
    </div>
    <div class="d-none" id="typed-strings"></div>
    <div class="navbar-icon d-flex">
        <ul class="navbar-nav flex-row align-items-center">
            @if(Auth::user()->user_type == 1)
                <li class="nav-item me-2">
                    <a href="{{ url('/tasks') }}"
                        class="btn btn-sm {{ request()->routeIs('TaskBoard') ? 'btn-primary' : 'btn-outline-secondary' }}"
                        style="font-size: 12px; padding: 4px 12px; border-radius: 20px;">
                        <i class="fas fa-clipboard-list me-1"></i> Task Board
                    </a>
                </li>
            @endif
            <li class="nav-item notification user-header-menu">
                @include('sandbox_live')
            </li>
            <li class="nav-item dropdown notification user-header-menu">
                <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <span class="navbar-avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>

                </a>
                <div class="dropdown-menu">
                    <div class="dropdown-header d-sm-none">
                        <a href class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                    </div>
                    <div class="user-header">
                        <div class="img-user">

                            <span
                                class="navbar-avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>

                        </div>
                        <h6>{{ Auth::user()->name }}</h6>

                        @php
                            $companyProfile = App\Models\CompanyProfile::where('user_id', Auth::user()->id)->first();
                        @endphp

                        @if($companyProfile && $companyProfile->name)
                            <span>
                                <a href="#" class="__cf_email__">
                                    {{ $companyProfile->name }}
                                </a>
                            </span>
                        @endif

                    </div>

                    @if(Auth::user()->user_type == 1)
                        <a href="{{url('my/profile')}}" class="dropdown-item">
                            <i class="typcn typcn-user-outline"></i>
                            My profile
                        </a>
                        <a href="{{url('company/profile')}}" class="dropdown-item">
                            <i class="typcn typcn-edit"></i>
                            Edit company profile
                        </a>
                    @endif

                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="typcn typcn-key-outline"></i>
                        Sign out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </div>
            </li>
        </ul>
    </div>
</nav>