@php
    $companyProfile = App\Models\CompanyProfile::where('user_id', Auth::user()->id)->first();
@endphp

<nav class="sidebar sidebar-bunker" style="display: flex; flex-direction: column;">
    <div class="sidebar-header">
        <a href="{{url('/')}}" class="sidebar-brand">
            @if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo)))
                <img class="max-h-45" src="{{url($companyProfile->logo)}}" />
            @else
                <img class="max-h-45" src="{{ url('assets') }}/img/logo.svg" />
            @endif
        </a>
    </div>
    <div class="sidebar-body" style="flex: 1; overflow-y: auto;">
        @php
            $currentRoute = request()->route()->getName();
        @endphp
        <nav class="sidebar-nav">
            <ul class="metismenu">

                {{-- ═══ MAIN ═══ --}}
                <li class="sidebar-section-label">
                    <span>Main</span>
                </li>
                <li class="@if($currentRoute == 'Dashboard') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/dashboard') }}">
                        <i class="typcn typcn-chart-bar-outline"></i>
                        Dashboard
                    </a>
                </li>
                <li class="@if($currentRoute == 'home') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/home') }}">
                        <i class="typcn typcn-zoom-outline"></i>
                        Search Pad
                    </a>
                </li>

                {{-- ═══ BOOKING & TICKETS ═══ --}}
                <li class="sidebar-section-label">
                    <span>Booking & Tickets</span>
                </li>
                <li class="@if(in_array($currentRoute, ['ViewAllBooking', 'ViewCancelBooking'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-info-outline"></i> Booking Information
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'ViewAllBooking') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/all/booking')}}">
                                Booking List
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewCancelBooking') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/cancel/booking')}}">
                                Cancelled Booking List
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['ViewIssuedTickets', 'ViewCancelledTickets'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-ticket"></i> Ticket Information
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'ViewIssuedTickets') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/issued/tickets')}}">
                                Issued Ticket List
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewCancelledTickets') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/cancelled/tickets')}}">
                                Cancelled Ticket List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="@if($currentRoute == 'ArchivedIssuedTickets') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/archived/issued/tickets') }}">
                        <i class="typcn typcn-folder-open"></i>
                        Archived Issued Tickets
                    </a>
                </li>
                <li class="@if($currentRoute == 'ViewSavedPassengers') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/view/saved/passengers') }}">
                        <i class="typcn typcn-user-outline"></i>
                        Saved Passengers
                    </a>
                </li>

                {{-- ═══ FINANCE & REPORTS ═══ --}}
                <li class="sidebar-section-label">
                    <span>Finance & Reports</span>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['ViewBankAccounts', 'AddBankAccount', 'EditBankAccount', 'ViewMfsAccounts', 'AddMfsAccount', 'EditMfsAccount', 'ViewAccountDeductions', 'CreateTopupRequest', 'ViewRechargeRequests'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-credit-card"></i> Financial Transactions
                    </a>
                    <ul class="nav-second-level">
                        <li
                            class="@if(in_array($currentRoute, ['ViewBankAccounts', 'AddBankAccount', 'EditBankAccount'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/bank/accounts')}}">
                                Bank Accounts
                            </a>
                        </li>
                        <li
                            class="@if(in_array($currentRoute, ['ViewMfsAccounts', 'AddMfsAccount', 'EditMfsAccount'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/mfs/accounts')}}">
                                MFS Accounts
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewAccountDeductions'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/account/deductions')}}">
                                B2B Account Deductions
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['CreateTopupRequest'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('create/topup/request')}}">
                                Submit Topup Request
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewRechargeRequests'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/recharge/requests')}}">
                                View Topup Requests
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-chart-bar-outline"></i> Reports
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'FlightBookingReport') mm-active @endif">
                            <a class="text-capitalize" href="{{url('flight/booking/report')}}">
                                Flight Booking Report
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bFinancialReport') mm-active @endif">
                            <a class="text-capitalize" href="{{url('b2b/financial/report')}}">
                                B2B Financial Report
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ═══ USERS ═══ --}}
                <li class="sidebar-section-label">
                    <span>Users</span>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['CreateB2bUser', 'ViewB2bUser', 'EditB2bUser'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-lock-open-outline"></i> User Management
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if(in_array($currentRoute, ['CreateB2bUser'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('create/b2b/users')}}">
                                Create B2B User
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewB2bUser', 'EditB2bUser'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/b2b/users')}}">
                                View B2B Users
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="@if($currentRoute == 'ViewRegisteredCustomers') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/view/registered/customers') }}">
                        <i class="typcn typcn-group-outline"></i>
                        Registered Customers
                    </a>
                </li>

                {{-- ═══ SETTINGS ═══ --}}
                <li class="sidebar-section-label">
                    <span>Settings</span>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['SetupGds', 'EditGdsInfo', 'ViewExcludedAirlines'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-plane-outline"></i> Airline Setup
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'SetupGds') mm-active @endif">
                            <a class="text-capitalize" href="{{url('setup/gds')}}">
                                GDS Setting
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewExcludedAirlines') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/excluded/airlines')}}">
                                Exclude Airlines
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewAirlinesComissions') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/airlines/comissions')}}">
                                Airlines Commissions
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['ViewSmsGateways', 'ViewEmailConfig', 'SearchResultsViewConfig'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-cog-outline"></i> Application Setting
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'SearchResultsViewConfig') mm-active @endif">
                            <a class="text-capitalize" href="{{url('search/results/view/config')}}">
                                Search Results View
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewEmailConfig') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/email/config')}}">
                                Mail Server
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewSmsGateways') mm-active @endif">
                            <a class="text-capitalize" href="{{url('setup/sms/gateways')}}">
                                SMS Gateway
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="@if(in_array($currentRoute, ['ViewAllBanners', 'ViewOfficeAddress'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-tabs-outline"></i> Content Management
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if(in_array($currentRoute, ['ViewAllBanners'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/all/banners')}}">
                                View All Banners
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewOfficeAddress'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/office/address')}}">
                                Office Addresses
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="@if($currentRoute == 'ViewActivityLogs') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/view/activity/logs') }}">
                        <i class="typcn typcn-document-text"></i>
                        Activity Logs
                    </a>
                </li>

            </ul>
        </nav>
    </div>

    {{-- ═══ SIDEBAR FOOTER: User Info + Logout ═══ --}}
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            <div class="sidebar-footer-avatar">
                <span class="avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="sidebar-footer-details">
                <span class="sidebar-footer-name">{{ Auth::user()->name }}</span>
                @php
                    $userType = \App\Enums\UserType::from(Auth::user()->user_type);
                    $roleBadgeClass = match ($userType) {
                        \App\Enums\UserType::SuperAdmin => 'role-super-admin',
                        \App\Enums\UserType::Admin => 'role-admin',
                        \App\Enums\UserType::B2B => 'role-b2b',
                        \App\Enums\UserType::B2C => 'role-b2c',
                    };
                @endphp
                <span class="sidebar-role-badge {{ $roleBadgeClass }}">{{ $userType->label() }}</span>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="sidebar-logout-btn"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
            <i class="typcn typcn-power-outline"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</nav>