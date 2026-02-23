<?php
    $companyProfile = App\Models\CompanyProfile::where('user_id', Auth::user()->id)->first();
?>

<nav class="sidebar sidebar-bunker" style="display: flex; flex-direction: column;">
    <div class="sidebar-header">
        <a href="<?php echo e(url('/')); ?>" class="sidebar-brand">
            <?php if($companyProfile && $companyProfile->logo && file_exists(public_path($companyProfile->logo))): ?>
                <img class="max-h-45" src="<?php echo e(url($companyProfile->logo)); ?>" />
            <?php else: ?>
                <img class="max-h-45" src="<?php echo e(url('assets')); ?>/img/logo.svg" />
            <?php endif; ?>
        </a>
    </div>
    <div class="sidebar-body" style="flex: 1; overflow-y: auto;">
        <?php
            $currentRoute = request()->route()->getName();
        ?>
        <nav class="sidebar-nav">
            <ul class="metismenu">

                
                <li class="sidebar-section-label">
                    <span>Main</span>
                </li>
                <li class="<?php if($currentRoute == 'Dashboard'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/dashboard')); ?>">
                        <i class="typcn typcn-chart-bar-outline"></i>
                        Dashboard
                    </a>
                </li>
                <li class="<?php if($currentRoute == 'home'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/home')); ?>">
                        <i class="typcn typcn-zoom-outline"></i>
                        Search Pad
                    </a>
                </li>

                
                <li class="sidebar-section-label">
                    <span>Booking & Tickets</span>
                </li>
                <li class="<?php if(in_array($currentRoute, ['ViewAllBooking', 'ViewCancelBooking'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-info-outline"></i> Booking Information
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if($currentRoute == 'ViewAllBooking'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/all/booking')); ?>">
                                Booking List
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewCancelBooking'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/cancel/booking')); ?>">
                                Cancelled Booking List
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="<?php if(in_array($currentRoute, ['ViewIssuedTickets', 'ViewCancelledTickets'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-ticket"></i> Ticket Information
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if($currentRoute == 'ViewIssuedTickets'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/issued/tickets')); ?>">
                                Issued Ticket List
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewCancelledTickets'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/cancelled/tickets')); ?>">
                                Cancelled Ticket List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if($currentRoute == 'ArchivedIssuedTickets'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/archived/issued/tickets')); ?>">
                        <i class="typcn typcn-folder-open"></i>
                        Archived Issued Tickets
                    </a>
                </li>
                <li class="<?php if($currentRoute == 'ViewSavedPassengers'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/view/saved/passengers')); ?>">
                        <i class="typcn typcn-user-outline"></i>
                        Saved Passengers
                    </a>
                </li>

                
                <li class="sidebar-section-label">
                    <span>Finance & Reports</span>
                </li>
                <li
                    class="<?php if(in_array($currentRoute, ['ViewBankAccounts', 'AddBankAccount', 'EditBankAccount', 'ViewMfsAccounts', 'AddMfsAccount', 'EditMfsAccount', 'ViewAccountDeductions', 'CreateTopupRequest', 'ViewRechargeRequests'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-credit-card"></i> Financial Transactions
                    </a>
                    <ul class="nav-second-level">
                        <li
                            class="<?php if(in_array($currentRoute, ['ViewBankAccounts', 'AddBankAccount', 'EditBankAccount'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/bank/accounts')); ?>">
                                Bank Accounts
                            </a>
                        </li>
                        <li
                            class="<?php if(in_array($currentRoute, ['ViewMfsAccounts', 'AddMfsAccount', 'EditMfsAccount'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/mfs/accounts')); ?>">
                                MFS Accounts
                            </a>
                        </li>
                        <li class="<?php if(in_array($currentRoute, ['ViewAccountDeductions'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/account/deductions')); ?>">
                                B2B Account Deductions
                            </a>
                        </li>
                        <li class="<?php if(in_array($currentRoute, ['CreateTopupRequest'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('create/topup/request')); ?>">
                                Submit Topup Request
                            </a>
                        </li>
                        <li class="<?php if(in_array($currentRoute, ['ViewRechargeRequests'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/recharge/requests')); ?>">
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
                        <li class="<?php if($currentRoute == 'FlightBookingReport'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('flight/booking/report')); ?>">
                                Flight Booking Report
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'B2bFinancialReport'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('b2b/financial/report')); ?>">
                                B2B Financial Report
                            </a>
                        </li>
                    </ul>
                </li>

                
                <li class="sidebar-section-label">
                    <span>Users</span>
                </li>
                <li
                    class="<?php if(in_array($currentRoute, ['CreateB2bUser', 'ViewB2bUser', 'EditB2bUser'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-lock-open-outline"></i> User Management
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if(in_array($currentRoute, ['CreateB2bUser'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('create/b2b/users')); ?>">
                                Create B2B User
                            </a>
                        </li>
                        <li class="<?php if(in_array($currentRoute, ['ViewB2bUser', 'EditB2bUser'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/b2b/users')); ?>">
                                View B2B Users
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if($currentRoute == 'ViewRegisteredCustomers'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/view/registered/customers')); ?>">
                        <i class="typcn typcn-group-outline"></i>
                        Registered Customers
                    </a>
                </li>

                
                <li class="sidebar-section-label">
                    <span>Settings</span>
                </li>
                <li
                    class="<?php if(in_array($currentRoute, ['SetupGds', 'EditGdsInfo', 'ViewExcludedAirlines'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-plane-outline"></i> Airline Setup
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if($currentRoute == 'SetupGds'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('setup/gds')); ?>">
                                GDS Setting
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewExcludedAirlines'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/excluded/airlines')); ?>">
                                Exclude Airlines
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewAirlinesComissions'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/airlines/comissions')); ?>">
                                Airlines Commissions
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="<?php if(in_array($currentRoute, ['ViewSmsGateways', 'ViewEmailConfig', 'SearchResultsViewConfig'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-cog-outline"></i> Application Setting
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if($currentRoute == 'SearchResultsViewConfig'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('search/results/view/config')); ?>">
                                Search Results View
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewEmailConfig'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/email/config')); ?>">
                                Mail Server
                            </a>
                        </li>
                        <li class="<?php if($currentRoute == 'ViewSmsGateways'): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('setup/sms/gateways')); ?>">
                                SMS Gateway
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if(in_array($currentRoute, ['ViewAllBanners', 'ViewOfficeAddress'])): ?> mm-active <?php endif; ?>">
                    <a class="has-arrow material-ripple" href="javascript:void(0);">
                        <i class="typcn typcn-tabs-outline"></i> Content Management
                    </a>
                    <ul class="nav-second-level">
                        <li class="<?php if(in_array($currentRoute, ['ViewAllBanners'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/all/banners')); ?>">
                                View All Banners
                            </a>
                        </li>
                        <li class="<?php if(in_array($currentRoute, ['ViewOfficeAddress'])): ?> mm-active <?php endif; ?>">
                            <a class="text-capitalize" href="<?php echo e(url('view/office/address')); ?>">
                                Office Addresses
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php if($currentRoute == 'ViewActivityLogs'): ?> mm-active <?php endif; ?>">
                    <a class="text-capitalize" href="<?php echo e(url('/view/activity/logs')); ?>">
                        <i class="typcn typcn-document-text"></i>
                        Activity Logs
                    </a>
                </li>

            </ul>
        </nav>
    </div>

    
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            <div class="sidebar-footer-avatar">
                <span class="avatar-initials"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
            </div>
            <div class="sidebar-footer-details">
                <span class="sidebar-footer-name"><?php echo e(Auth::user()->name); ?></span>
                <?php
                    $userType = \App\Enums\UserType::from(Auth::user()->user_type);
                    $roleBadgeClass = match ($userType) {
                        \App\Enums\UserType::SuperAdmin => 'role-super-admin',
                        \App\Enums\UserType::Admin => 'role-admin',
                        \App\Enums\UserType::B2B => 'role-b2b',
                        \App\Enums\UserType::B2C => 'role-b2c',
                    };
                ?>
                <span class="sidebar-role-badge <?php echo e($roleBadgeClass); ?>"><?php echo e($userType->label()); ?></span>
            </div>
        </div>
        <a href="<?php echo e(route('logout')); ?>" class="sidebar-logout-btn"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
            <i class="typcn typcn-power-outline"></i>
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?>
        </form>
    </div>
</nav><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/sidebar.blade.php ENDPATH**/ ?>