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
                                <i class="typcn typcn-th-list-outline"></i> Booking List
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewCancelBooking') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/cancel/booking')}}">
                                <i class="typcn typcn-times-outline"></i> Cancelled Booking List
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
                                <i class="typcn typcn-tick-outline"></i> Issued Ticket List
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewCancelledTickets') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/cancelled/tickets')}}">
                                <i class="typcn typcn-delete-outline"></i> Cancelled Ticket List
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
                <li class="@if($currentRoute == 'SavedPassengers') mm-active @endif">
                    <a class="text-capitalize" href="{{ url('/view/saved/passengers') }}">
                        <i class="typcn typcn-user-outline"></i>
                        Saved Passengers
                    </a>
                </li>

                {{-- ═══ B2B ═══ --}}
                <li class="@if(in_array($currentRoute, ['B2bFlightBookings','B2bTourBookings','B2bRegistrationRequests','B2bPartialPayBookings','B2bPendingTicketIssuance','B2bAgencyList','B2bUpcomingFlights'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-briefcase"></i> B2B
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'B2bFlightBookings') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/flight-bookings') }}">
                                <i class="typcn typcn-plane-outline"></i> Flight Bookings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bTourBookings') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/tour-bookings') }}">
                                <i class="typcn typcn-map"></i> Tour Bookings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bRegistrationRequests') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/registration-requests') }}">
                                <i class="typcn typcn-document-text"></i> Registration Requests
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bPartialPayBookings') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/partial-pay-bookings') }}">
                                <i class="typcn typcn-credit-card"></i> Partial Pay Bookings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bPendingTicketIssuance') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/pending-ticket-issuance') }}">
                                <i class="typcn typcn-ticket"></i> Pending Ticket Issuance
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bAgencyList') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/agency-list') }}">
                                <i class="typcn typcn-group-outline"></i> Agency
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bUpcomingFlights') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2b/upcoming-flights') }}">
                                <i class="typcn typcn-time"></i> Upcoming Flights
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ═══ B2C ═══ --}}
                <li class="@if(in_array($currentRoute, ['B2cFlightBookings','B2cTourBookings','B2cUserList','B2cUpcomingFlights'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-user-outline"></i> B2C
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'B2cFlightBookings') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2c/flight-bookings') }}">
                                <i class="typcn typcn-plane-outline"></i> Flight Bookings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2cTourBookings') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2c/tour-bookings') }}">
                                <i class="typcn typcn-map"></i> Tour Bookings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2cUserList') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2c/user-list') }}">
                                <i class="typcn typcn-group-outline"></i> Users
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2cUpcomingFlights') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('b2c/upcoming-flights') }}">
                                <i class="typcn typcn-time"></i> Upcoming Flights
                            </a>
                        </li>
                    </ul>
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
                                <i class="typcn typcn-briefcase"></i> Bank Accounts
                            </a>
                        </li>
                        <li
                            class="@if(in_array($currentRoute, ['ViewMfsAccounts', 'AddMfsAccount', 'EditMfsAccount'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/mfs/accounts')}}">
                                <i class="typcn typcn-phone-outline"></i> MFS Accounts
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewAccountDeductions'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/account/deductions')}}">
                                <i class="typcn typcn-minus-outline"></i> B2B Account Deductions
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['CreateTopupRequest'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('create/topup/request')}}">
                                <i class="typcn typcn-plus-outline"></i> Submit Topup Request
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewRechargeRequests'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/recharge/requests')}}">
                                <i class="typcn typcn-eye-outline"></i> View Topup Requests
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
                                <i class="typcn typcn-chart-area-outline"></i> Flight Booking Report
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'B2bFinancialReport') mm-active @endif">
                            <a class="text-capitalize" href="{{url('b2b/financial/report')}}">
                                <i class="typcn typcn-chart-pie-outline"></i> B2B Financial Report
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
                                <i class="typcn typcn-user-add-outline"></i> Create B2B User
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewB2bUser', 'EditB2bUser'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/b2b/users')}}">
                                <i class="typcn typcn-group-outline"></i> View B2B Users
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
                    class="@if(in_array($currentRoute, ['SetupGds', 'EditGdsInfo', 'ViewExcludedAirlines', 'ArchivedGds'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-plane-outline"></i> Airline Setup
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'SetupGds') mm-active @endif">
                            <a class="text-capitalize" href="{{url('setup/gds')}}">
                                <i class="typcn typcn-spanner-outline"></i> GDS Setting
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ArchivedGds') mm-active @endif">
                            <a class="text-capitalize" href="{{url('archived/gds')}}">
                                <i class="typcn typcn-archive"></i> Archived GDS
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewExcludedAirlines') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/excluded/airlines')}}">
                                <i class="typcn typcn-cancel-outline"></i> Exclude Airlines
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewAirlinesComissions') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/airlines/comissions')}}">
                                <i class="typcn typcn-calculator"></i> Airlines Commissions
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['ViewSmsGateways', 'ViewEmailConfig', 'SearchResultsViewConfig', 'PricingConfig', 'RulesEngine'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-cog-outline"></i> Application Setting
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if(in_array($currentRoute, ['RulesEngine'])) mm-active @endif">
                            <a class="text-capitalize" href="{{route('RulesEngine')}}">
                                <i class="typcn typcn-map"></i> Rules Engine
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'PricingConfig') mm-active @endif">
                            <a class="text-capitalize" href="{{url('pricing/config')}}">
                                <i class="typcn typcn-tags"></i> Pricing & Margins
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'SearchResultsViewConfig') mm-active @endif">
                            <a class="text-capitalize" href="{{url('search/results/view/config')}}">
                                <i class="typcn typcn-zoom-outline"></i> Search Results View
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewEmailConfig') mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/email/config')}}">
                                <i class="typcn typcn-mail"></i> Mail Server
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ViewSmsGateways') mm-active @endif">
                            <a class="text-capitalize" href="{{url('setup/sms/gateways')}}">
                                <i class="typcn typcn-message"></i> SMS Gateway
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
                                <i class="typcn typcn-image-outline"></i> View All Banners
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['ViewOfficeAddress'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('view/office/address')}}">
                                <i class="typcn typcn-location-outline"></i> Office Addresses
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="@if(in_array($currentRoute, ['CmsBanners', 'CmsPromotions', 'CmsRoutes', 'CmsTestimonials', 'CmsPages', 'CmsEditPage', 'CmsSiteSettings', 'CmsFaqs', 'B2cCommission', 'B2cTermsConditions', 'B2cPrivacyPolicy', 'B2cCoinConfig', 'B2cGallery', 'B2cSocialMedia', 'B2cYoutubeLinks', 'B2cFilmWatch', 'B2cPopularDestinations', 'B2cSpecialOfferList', 'B2cCreateOffer', 'B2cDetailsOffer', 'B2cEditOffer', 'B2cBannerList', 'B2cEditBanner', 'B2cFooterInfo'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-globe-outline"></i> B2C CMS
                    </a>
                    <ul class="nav-second-level">

                        {{-- ─── B2C Configuration nested dropdown ─── --}}
                        <li class="@if(in_array($currentRoute, ['B2cCommission', 'B2cTermsConditions', 'B2cPrivacyPolicy', 'B2cCoinConfig', 'B2cGallery', 'B2cSocialMedia', 'B2cYoutubeLinks', 'B2cFilmWatch', 'B2cPopularDestinations', 'B2cSpecialOfferList', 'B2cCreateOffer', 'B2cDetailsOffer', 'B2cEditOffer', 'B2cBannerList', 'B2cEditBanner', 'B2cFooterInfo'])) mm-active @endif">
                            <a class="has-arrow material-ripple" href="javascript:void(0);">
                                <i class="typcn typcn-cog-outline"></i> B2C Configuration
                            </a>
                            <ul class="nav-third-level">
                                <li class="@if($currentRoute == 'B2cCommission') mm-active @endif">
                                    <a href="{{ url('b2c/config/commission') }}">
                                        <i class="typcn typcn-calculator"></i> B2C Commission
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cTermsConditions') mm-active @endif">
                                    <a href="{{ url('b2c/config/terms-conditions') }}">
                                        <i class="typcn typcn-document-text"></i> Terms &amp; Conditions
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cPrivacyPolicy') mm-active @endif">
                                    <a href="{{ url('b2c/config/privacy-policy') }}">
                                        <i class="typcn typcn-lock-closed-outline"></i> Privacy Policy
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cCoinConfig') mm-active @endif">
                                    <a href="{{ url('b2c/config/coin-config') }}">
                                        <i class="typcn typcn-gift-outline"></i> Coin Configuration
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cGallery') mm-active @endif">
                                    <a href="{{ url('b2c/config/gallery') }}">
                                        <i class="typcn typcn-image-outline"></i> Gallery
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cSocialMedia') mm-active @endif">
                                    <a href="{{ url('b2c/config/social-media') }}">
                                        <i class="typcn typcn-social-twitter"></i> Social Media
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cYoutubeLinks') mm-active @endif">
                                    <a href="{{ url('b2c/config/youtube-links') }}">
                                        <i class="typcn typcn-media-play-outline"></i> YouTube Links
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cFilmWatch') mm-active @endif">
                                    <a href="{{ url('b2c/config/film-watch') }}">
                                        <i class="typcn typcn-video-outline"></i> Films
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cPopularDestinations') mm-active @endif">
                                    <a href="{{ url('b2c/config/popular-destinations') }}">
                                        <i class="typcn typcn-location-outline"></i> Popular Destinations
                                    </a>
                                </li>
                                <li class="@if(str_starts_with(request()->path(), 'b2c/config/offers/hot-deal')) mm-active @endif">
                                    <a href="{{ url('b2c/config/offers/hot-deal') }}">
                                        <i class="typcn typcn-fire-outline"></i> Hot Deals
                                    </a>
                                </li>
                                <li class="@if(str_starts_with(request()->path(), 'b2c/config/offers/ad')) mm-active @endif">
                                    <a href="{{ url('b2c/config/offers/ad') }}">
                                        <i class="typcn typcn-chart-bar-outline"></i> AD
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cBannerList') mm-active @endif">
                                    <a href="{{ url('b2c/config/banners') }}">
                                        <i class="typcn typcn-tabs-outline"></i> Banner
                                    </a>
                                </li>
                                <li class="@if($currentRoute == 'B2cFooterInfo') mm-active @endif">
                                    <a href="{{ url('b2c/config/footer-info') }}">
                                        <i class="typcn typcn-th-small-outline"></i> B2C Footer Info
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="@if($currentRoute == 'CmsPromotions') mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/promotions')}}">
                                <i class="typcn typcn-tags"></i> Promotions
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'CmsRoutes') mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/routes')}}">
                                <i class="typcn typcn-plane-outline"></i> Popular Routes
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'CmsTestimonials') mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/testimonials')}}">
                                <i class="typcn typcn-star-outline"></i> Testimonials
                            </a>
                        </li>
                        <li class="@if(in_array($currentRoute, ['CmsPages', 'CmsEditPage'])) mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/pages')}}">
                                <i class="typcn typcn-document-text"></i> Static Pages
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'CmsSiteSettings') mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/site-settings')}}">
                                <i class="typcn typcn-cog-outline"></i> Site Settings
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'CmsFaqs') mm-active @endif">
                            <a class="text-capitalize" href="{{url('cms/faqs')}}">
                                <i class="typcn typcn-messages"></i> FAQs
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

                {{-- ═══ CONFIGURATION ═══ --}}
                <li class="sidebar-section-label">
                    <span>Configuration</span>
                </li>
                <li class="@if(in_array($currentRoute, ['ConfigDynamicFareRules','ConfigPartialPaymentRules','ConfigBlockRoutes','ConfigAirports','ConfigAirlines','ConfigTracking','ConfigCities','ConfigAnnouncements'])) mm-active @endif">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-cog-outline"></i> Configuration
                    </a>
                    <ul class="nav-second-level">
                        <li class="@if($currentRoute == 'ConfigDynamicFareRules') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/dynamic-fare-rules') }}">
                                <i class="typcn typcn-calculator"></i> Dynamic Fare Rules
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigPartialPaymentRules') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/partial-payment-rules') }}">
                                <i class="typcn typcn-credit-card"></i> Partial Payment Rules
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigBlockRoutes') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/block-routes') }}">
                                <i class="typcn typcn-cancel-outline"></i> Block Route
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigAirports') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/airports') }}">
                                <i class="typcn typcn-plane-outline"></i> Airports
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigAirlines') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/airlines') }}">
                                <i class="typcn typcn-flight"></i> Airlines
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigTracking') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/tracking') }}">
                                <i class="typcn typcn-chart-bar-outline"></i> Tracking
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigCities') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/cities') }}">
                                <i class="typcn typcn-location-outline"></i> City
                            </a>
                        </li>
                        <li class="@if($currentRoute == 'ConfigAnnouncements') mm-active @endif">
                            <a class="text-capitalize" href="{{ url('configuration/announcements') }}">
                                <i class="typcn typcn-bell"></i> Announcement
                            </a>
                        </li>
                    </ul>
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