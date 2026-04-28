<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GdsController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\OfficeAddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CmsCrudController;
use App\Http\Controllers\PricingConfigController;
use App\Http\Controllers\B2c\HomeController as B2cHomeController;
use App\Http\Controllers\B2c\AuthController as B2cAuthController;
use App\Http\Controllers\B2c\FlightSearchController as B2cFlightSearchController;
use App\Models\SabreFlightBooking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| B2C Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

// Root route: B2C landing on faithtrip.net, admin login on app.faithtrip.net
Route::get('/', function () {
    $host = request()->getHost();
    if (str_starts_with($host, 'app.')) {
        return redirect('/login');
    }
    return app(B2cHomeController::class)->index();
})->name('b2c.home');

Route::get('/page/{slug}', [B2cHomeController::class, 'page'])->name('b2c.page');
Route::get('/deals', [B2cHomeController::class, 'deals'])->name('b2c.deals');

// B2C Auth
Route::get('/customer/login', [B2cAuthController::class, 'showLogin'])->name('b2c.login');
Route::post('/customer/login', [B2cAuthController::class, 'login'])->name('b2c.login.submit');
Route::get('/customer/register', [B2cAuthController::class, 'showRegister'])->name('b2c.register');
Route::post('/customer/register', [B2cAuthController::class, 'register'])->name('b2c.register.submit');
Route::post('/customer/logout', [B2cAuthController::class, 'logout'])->name('b2c.logout');

// B2C Flight Search (Public - no auth required for search)
Route::post('/flights/search', [B2cFlightSearchController::class, 'search'])->name('b2c.flights.search');
Route::get('/flights/results', [B2cFlightSearchController::class, 'results'])->name('b2c.flights.results');
Route::post('/flights/price-filter', [B2cFlightSearchController::class, 'priceFilter'])->name('b2c.flights.priceFilter');
Route::post('/flights/airline-filter', [B2cFlightSearchController::class, 'airlineFilter'])->name('b2c.flights.airlineFilter');
Route::get('/flights/select/{index}', [B2cFlightSearchController::class, 'selectFlight'])->name('b2c.flights.select');
Route::get('/b2c/city-airport-search', [B2cFlightSearchController::class, 'cityAirportSearch'])->name('b2c.cityAirportSearch');

// B2C Protected Routes (Customer must be logged in)
Route::middleware(['auth', \App\Http\Middleware\CheckCustomer::class])->group(function () {
    Route::get('/my-account', function () {
        return view('b2c.account.dashboard');
    })->name('b2c.account');
});


// cron job to auto cancel flight booking start
// Route::get('/check/last/ticketing/datetime', function () {

//     ini_set('max_execution_time', 300); // 5 minutes
//     ini_set('memory_limit', '4096M');   // 4 GB

//     $data = DB::table('flight_bookings')->select('booking_no', 'pnr_id', 'last_ticket_datetime')->where('status', 1)->where('departure_date', '>=', date("Y-m-d"))->whereNotNull('last_ticket_datetime')->get();
//     foreach($data as $item){

//         $lastTicketTime = strtotime($item->last_ticket_datetime);
//         $now = time();
//         $twoHoursLater = $now + (2 * 60 * 60); // 2 hours in seconds

//         if($lastTicketTime > $now && $lastTicketTime <= $twoHoursLater){
//             $cancelResponse = json_decode(SabreFlightBooking::cancelBooking($item->booking_no), true);
//             if(isset($cancelResponse['booking']['bookingId']) && $cancelResponse['booking']['bookingId'] == $item->pnr_id){
//                 DB::table('flight_bookings')->where('pnr_id', $item->pnr_id)->update([
//                     'status' => 3,
//                     'booking_cancelled_at' => Carbon::now(),
//                     'updated_at' => Carbon::now(),
//                 ]);
//             }
//         }
//     }
//     return response(null, 204);
// });
// cron job to auto cancel flight booking end


Auth::routes([
    'login' => true,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

// Admin shortcut — redirects to login or dashboard
Route::get('/admin', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
})->name('admin.redirect');

// GET logout fallback (in case user types /logout in browser)
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

Route::get('ckeditor', [CkeditorController::class, 'index']);
Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');


// ssl commerz payment routes
Route::get('sslcommerz/order', [PaymentController::class, 'order'])->name('payment.order');
Route::post('sslcommerz/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('sslcommerz/failure', [PaymentController::class, 'failure'])->name('sslc.failure');
Route::post('sslcommerz/cancel', [PaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('sslcommerz/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');


Route::group(['middleware' => ['auth', 'CheckUserStatus']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/live/city/airport/search', [HomeController::class, 'liveCityAirportSearch'])->name('LiveCityAirportSearch');
    Route::get('/live/airline/search', [HomeController::class, 'liveAirlineSearch'])->name('LiveAirlineSearch');
    Route::post('/passenger/live/search', [HomeController::class, 'passengerLiveSearch'])->name('PassengerLiveSearch');

    // Payment Methods
    Route::get('/payment-methods', [HomeController::class, 'paymentMethods'])->name('payment.methods');

    // search flights
    Route::post('/search/flights', [FlightSearchController::class, 'searchFlights'])->name('SearchFlights');
    Route::post('/search/multi-city/flights', [FlightSearchController::class, 'searchMultiCityFlights'])->name('SearchMultiCityFlights');
    Route::get('/flight/search-results', [FlightSearchController::class, 'showFlightSearchResults'])->name('ShowFlightSearchResults');
    Route::get('select/flight/{session_index}', [FlightSearchController::class, 'revalidateFlight'])->name('RevalidateFlight');

    // search next and previous
    Route::get('/search/next/day', [FlightSearchController::class, 'searchNextDay'])->name('SearchNextDay');
    Route::get('/search/prev/day', [FlightSearchController::class, 'searchPreviousDay'])->name('SearchPreviousDay');

    // filter routes
    Route::post('/price/range/filter', [FlightSearchController::class, 'priceRangeFilter'])->name('PriceRangeFilter');
    Route::get('/clear/price/range/filter', [FlightSearchController::class, 'clearPriceRangeFilter'])->name('ClearPriceRangeFilter');
    Route::post('/airline/carrier/filter', [FlightSearchController::class, 'airlineCarrierFilter'])->name('AirlineCarrierFilter');
    Route::get('/clear/airline/carrier/filter', [FlightSearchController::class, 'clearAirlineCarrierFilter'])->name('ClearAirlineCarrierFilter');

    // company profile routes
    Route::get('/company/profile', [ProfileController::class, 'companyProfile'])->name('CompanyProfile');
    Route::post('/update/company/profile', [ProfileController::class, 'updateCompanyProfile'])->name('UpdateCompanyProfile');
    Route::get('/remove/company/logo', [ProfileController::class, 'removeCompanyLogo'])->name('RemoveCompanyLogo');

    // Pricing Config
    Route::get('/pricing/config', [PricingConfigController::class, 'index'])->name('PricingConfig');
    Route::post('/update/pricing/config', [PricingConfigController::class, 'update'])->name('UpdatePricingConfig');

    // user profile routes
    Route::get('/my/profile', [ProfileController::class, 'myProfile'])->name('MyProfile');
    Route::post('update/user/profile', [ProfileController::class, 'updateProfile'])->name('UpdateProfile');
    Route::get('/remove/user/image', [ProfileController::class, 'removeUserImage'])->name('RemoveUserImage');

    // flight booking routes
    Route::post('create/pnr/with/booking/sabre', [FlightBookingController::class, 'bookFlightWithPnrSabre'])->name('BookFlightWithPnrSabre');
    Route::post('create/pnr/with/booking', [FlightBookingController::class, 'bookFlightWithPnr'])->name('bookFlightWithPnr');
    Route::get('view/all/booking', [FlightBookingController::class, 'viewAllBooking'])->name('ViewAllBooking');
    Route::get('view/cancel/booking', [FlightBookingController::class, 'viewCancelBooking'])->name('ViewCancelBooking');
    Route::get('flight/booking/details/{booking_no}', [FlightBookingController::class, 'flightBookingDetails'])->name('FlightBookingDetails');
    Route::get('cancel/flight/booking/{booking_no}', [FlightBookingController::class, 'cancelFlightBooking'])->name('CancelFlightBooking');
    Route::get('cancel/issued/ticket/{booking_no}', [FlightBookingController::class, 'cancelIssuedTicket'])->name('cancelIssuedTicket');
    Route::get('booking/preview/{pnr_id}', [FlightBookingController::class, 'bookingPreview'])->name('BookingPreview');
    Route::get('issue/flight/ticket/{booking_no}', [FlightBookingController::class, 'issueFlightTicket'])->name('IssueFlightTicket');
    Route::get('view/issued/tickets', [FlightBookingController::class, 'viewIssuedTickets'])->name('ViewIssuedTickets');
    Route::get('archived/issued/tickets', [FlightBookingController::class, 'archivedIssuedTickets'])->name('ArchivedIssuedTickets');
    Route::get('view/cancelled/tickets', [FlightBookingController::class, 'viewCancelledTickets'])->name('ViewCancelledTickets');
    Route::post('update/pnr/booking', [FlightBookingController::class, 'updatePnrBooking'])->name('UpdatePnrBooking');
    Route::get('export/bookings/csv', [FlightBookingController::class, 'exportBookingsCsv'])->name('ExportBookingsCsv');

    // recharge
    Route::get('create/topup/request', [PaymentController::class, 'createTopupRequest'])->name('CreateTopupRequest');
    Route::post('submit/recharge/request', [PaymentController::class, 'submitRechargeRequest'])->name('SubmitRechargeRequest');
    Route::get('view/recharge/requests', [PaymentController::class, 'viewRechargeRequests'])->name('ViewRechargeRequests');
    Route::get('delete/recharge/request/{slug}', [PaymentController::class, 'deleteRechargeRequest'])->name('DeleteRechargeRequest');


    // report
    Route::get('flight/booking/report', [ReportController::class, 'flightBookingReport'])->name('FlightBookingReport');
    Route::post('generate/flight/booking/report', [ReportController::class, 'generateFlightBookingReport'])->name('GenerateFlightBookingReport');

    Route::group(['middleware' => ['CheckUserType']], function () {

        // Dashboard Analytics
        Route::get('dashboard', [DashboardController::class, 'index'])->name('Dashboard');

        // Activity Logs
        Route::get('view/activity/logs', [HomeController::class, 'viewActivityLogs'])->name('ViewActivityLogs');

        // office address
        Route::get('view/office/address', [OfficeAddressController::class, 'viewOfficeAddress'])->name('ViewOfficeAddress');
        Route::post('save/office/address', [OfficeAddressController::class, 'saveOfficeAddress'])->name('SaveOfficeAddress');
        Route::get('delete/office/address/{slug}', [OfficeAddressController::class, 'deleteOfficeAddress'])->name('DeleteOfficeAddress');
        Route::get('get/office/address/{slug}', [OfficeAddressController::class, 'getOfficeAddress'])->name('GetOfficeAddress');
        Route::post('update/office/address', [OfficeAddressController::class, 'updateOfficeAddress'])->name('UpdateOfficeAddress');

        // banner
        Route::get('view/all/banners', [BannerController::class, 'viewAllBanners'])->name('ViewAllBanners');
        Route::get('add/new/banner', [BannerController::class, 'addNewBanner'])->name('AddNewBanner');
        Route::post('save/banner', [BannerController::class, 'saveBanner'])->name('SaveBanner');
        Route::get('edit/banner/{slug}', [BannerController::class, 'editBanner'])->name('EditBanner');
        Route::post('update/banner', [BannerController::class, 'updateBanner'])->name('UpdateBanner');
        Route::get('delete/banner/{slug}', [BannerController::class, 'deleteBanner'])->name('DeleteBanner');

        // b2b account deductions
        Route::get('view/account/deductions', [PaymentController::class, 'viewAccountDeductions'])->name('ViewAccountDeductions');
        Route::get('submit/b2b/account/deduction', [PaymentController::class, 'submitAccountDeduction'])->name('SubmitAccountDeduction');
        Route::get('get/user/balance/{id}', [PaymentController::class, 'getUserBalance'])->name('GetUserBalance');
        Route::post('deduct/b2b/account', [PaymentController::class, 'deductB2bAccount'])->name('DeductB2bAccount');
        Route::get('delete/b2b/account/deduction/{slug}', [PaymentController::class, 'deleteDeductionHistory'])->name('DeleteDeductionHistory');

        // recharge
        Route::get('approve/recharge/request/{slug}', [PaymentController::class, 'approveRechargeRequest'])->name('ApproveRechargeRequest');
        Route::get('deny/recharge/request/{slug}', [PaymentController::class, 'denyRechargeRequest'])->name('DenyRechargeRequest');

        // setup gds routes
        Route::get('setup/gds', [GdsController::class, 'setupGds'])->name('SetupGds');
        Route::get('archived/gds', [GdsController::class, 'archivedGds'])->name('ArchivedGds');
        Route::post('gds/status/update', [GdsController::class, 'gdsStatusUpdate'])->name('GdsStatusUpdate');
        Route::post('gds/archive/{code}', [GdsController::class, 'archiveGds'])->name('ArchiveGds');
        Route::post('gds/restore/{code}', [GdsController::class, 'restoreGds'])->name('RestoreGds');
        Route::get('edit/gds/{code}', [GdsController::class, 'editGdsInfo'])->name('EditGdsInfo');
        Route::post('update/sabre/gds/info', [GdsController::class, 'updateSabreGdsInfo'])->name('UpdateSabreGdsInfo');
        Route::post('update/flyhub/gds/info', [GdsController::class, 'updateFlyhubGdsInfo'])->name('UpdateFlyhubGdsInfo');
        Route::get('view/excluded/airlines', [GdsController::class, 'viewExcludedAirlines'])->name('ViewExcludedAirlines');
        Route::post('save/excluded/airline', [GdsController::class, 'saveExcludedAirline'])->name('SaveExcludedAirline');
        Route::get('delete/excluded/airline/{id}', [GdsController::class, 'deleteExcludedAirline'])->name('DeleteExcludedAirline');
        Route::get('excluded/airline/info/{id}', [GdsController::class, 'excludedAirlineInfo'])->name('ExcludedAirlineInfo');
        Route::get('view/airlines/comissions', [GdsController::class, 'viewAirlinesComissions'])->name('ViewAirlinesComissions');
        Route::get('airline/info/{id}', [GdsController::class, 'airlineInfo'])->name('AirlineInfo');
        Route::post('update/airline/comission', [GdsController::class, 'updateAirlineComission'])->name('UpdateAirlineComission');

        // system route for sms & email
        Route::get('/setup/sms/gateways', [SystemController::class, 'viewSmsGateways'])->name('ViewSmsGateways');
        Route::post('/update/sms/gateway/info', [SystemController::class, 'updateSmsGatewayInfo'])->name('UpdateSmsGatewayInfo');
        Route::get('/change/gateway/status/{provider}', [SystemController::class, 'changeGatewayStatus'])->name('ChangeGatewayStatus');
        Route::get('/view/email/config', [SystemController::class, 'viewEmailConfig'])->name('ViewEmailConfig');
        Route::post('/update/email/config', [SystemController::class, 'updateEmailConfig'])->name('UpdateEmailConfig');
        Route::get('/search/results/view/config', [SystemController::class, 'searchResultsViewConfig'])->name('SearchResultsViewConfig');
        Route::get('/change/search/results/view/{value}', [SystemController::class, 'changeSearchResultsView'])->name('ChangeSearchResultsView');

        // bank accounts
        Route::get('view/bank/accounts', [PaymentController::class, 'viewBankAccounts'])->name('ViewBankAccounts');
        Route::get('add/bank/account', [PaymentController::class, 'addBankAccount'])->name('AddBankAccount');
        Route::post('save/bank/account', [PaymentController::class, 'saveBankAccount'])->name('SaveBankAccount');
        Route::get('delete/bank/account/{slug}', [PaymentController::class, 'deleteBankAccount'])->name('DeleteBankAccount');
        Route::get('edit/bank/account/{slug}', [PaymentController::class, 'editBankAccount'])->name('EditBankAccount');
        Route::post('update/bank/account', [PaymentController::class, 'updateBankAccount'])->name('UpdateBankAccount');

        // mfs accounts
        Route::get('view/mfs/accounts', [PaymentController::class, 'viewMfsAccounts'])->name('ViewMfsAccounts');
        Route::get('add/mfs/account', [PaymentController::class, 'addMfsAccount'])->name('AddMfsAccount');
        Route::post('save/mfs/account', [PaymentController::class, 'saveMfsAccount'])->name('SaveMfsAccount');
        Route::get('delete/mfs/account/{slug}', [PaymentController::class, 'deleteMfsAccount'])->name('DeleteMfsAccount');
        Route::get('edit/mfs/account/{slug}', [PaymentController::class, 'editMfsAccount'])->name('EditMfsAccount');
        Route::post('update/mfs/account', [PaymentController::class, 'updateMfsAccount'])->name('UpdateMfsAccount');

        // b2b user management
        Route::get('create/b2b/users', [UserController::class, 'createB2bUser'])->name('CreateB2bUser');
        Route::post('save/b2b/user', [UserController::class, 'saveB2bUser'])->name('SaveB2bUser');
        Route::get('view/b2b/users', [UserController::class, 'viewB2bUser'])->name('ViewB2bUser');
        Route::get('delete/b2b/user/{id}', [UserController::class, 'deleteB2bUser'])->name('DeleteB2bUser');
        Route::get('edit/b2b/user/{id}', [UserController::class, 'editB2bUser'])->name('EditB2bUser');
        Route::post('update/b2b/user', [UserController::class, 'updateB2bUser'])->name('UpdateB2bUser');
        Route::get('view/saved/passengers', [UserController::class, 'savedPassengers'])->name('SavedPassengers');
        Route::get('delete/saved/passenger/{id}', [UserController::class, 'deleteSavedPassenger'])->name('DeleteSavedPassenger');
        Route::get('view/registered/customers', [UserController::class, 'viewRegisteredCustomers'])->name('ViewRegisteredCustomers');

        // Task Board
        Route::get('tasks', [TaskController::class, 'index'])->name('TaskBoard');
        Route::post('tasks', [TaskController::class, 'store'])->name('StoreTask');
        Route::get('tasks/{id}', [TaskController::class, 'show'])->name('ShowTask');
        Route::put('tasks/{id}', [TaskController::class, 'update'])->name('UpdateTask');
        Route::patch('tasks/{id}/status/{status}', [TaskController::class, 'updateStatus'])->name('UpdateTaskStatus');
        Route::delete('tasks/{id}', [TaskController::class, 'destroy'])->name('DestroyTask');
        Route::post('tasks/seed-audit', [TaskController::class, 'seedAuditTasks'])->name('SeedAuditTasks');

        // CMS Management
        Route::get('cms/banners', [CmsCrudController::class, 'banners'])->name('CmsBanners');
        Route::post('cms/banners', [CmsCrudController::class, 'storeBanner'])->name('CmsStoreBanner');
        Route::put('cms/banners/{id}', [CmsCrudController::class, 'updateBanner'])->name('CmsUpdateBanner');
        Route::delete('cms/banners/{id}', [CmsCrudController::class, 'deleteBanner'])->name('CmsDeleteBanner');

        Route::get('cms/promotions', [CmsCrudController::class, 'promotions'])->name('CmsPromotions');
        Route::post('cms/promotions', [CmsCrudController::class, 'storePromotion'])->name('CmsStorePromotion');
        Route::put('cms/promotions/{id}', [CmsCrudController::class, 'updatePromotion'])->name('CmsUpdatePromotion');
        Route::delete('cms/promotions/{id}', [CmsCrudController::class, 'deletePromotion'])->name('CmsDeletePromotion');

        Route::get('cms/routes', [CmsCrudController::class, 'popularRoutes'])->name('CmsRoutes');
        Route::post('cms/routes', [CmsCrudController::class, 'storeRoute'])->name('CmsStoreRoute');
        Route::put('cms/routes/{id}', [CmsCrudController::class, 'updateRoute'])->name('CmsUpdateRoute');
        Route::delete('cms/routes/{id}', [CmsCrudController::class, 'deleteRoute'])->name('CmsDeleteRoute');

        Route::get('cms/testimonials', [CmsCrudController::class, 'testimonials'])->name('CmsTestimonials');
        Route::post('cms/testimonials', [CmsCrudController::class, 'storeTestimonial'])->name('CmsStoreTestimonial');
        Route::put('cms/testimonials/{id}', [CmsCrudController::class, 'updateTestimonial'])->name('CmsUpdateTestimonial');
        Route::delete('cms/testimonials/{id}', [CmsCrudController::class, 'deleteTestimonial'])->name('CmsDeleteTestimonial');

        Route::get('cms/pages', [CmsCrudController::class, 'pages'])->name('CmsPages');
        Route::post('cms/pages', [CmsCrudController::class, 'storePage'])->name('CmsStorePage');
        Route::get('cms/pages/{id}/edit', [CmsCrudController::class, 'editPage'])->name('CmsEditPage');
        Route::put('cms/pages/{id}', [CmsCrudController::class, 'updatePage'])->name('CmsUpdatePage');
        Route::delete('cms/pages/{id}', [CmsCrudController::class, 'deletePage'])->name('CmsDeletePage');

        Route::get('cms/site-settings', [CmsCrudController::class, 'siteSettings'])->name('CmsSiteSettings');
        Route::post('cms/site-settings', [CmsCrudController::class, 'updateSiteSettings'])->name('CmsUpdateSiteSettings');
        Route::post('cms/payment-methods', [CmsCrudController::class, 'storePaymentMethod'])->name('CmsStorePaymentMethod');
        Route::delete('cms/payment-methods/{id}', [CmsCrudController::class, 'deletePaymentMethod'])->name('CmsDeletePaymentMethod');

        Route::get('cms/faqs', [CmsCrudController::class, 'faqs'])->name('CmsFaqs');
        Route::post('cms/faqs', [CmsCrudController::class, 'storeFaq'])->name('CmsStoreFaq');
        Route::put('cms/faqs/{id}', [CmsCrudController::class, 'updateFaq'])->name('CmsUpdateFaq');
        Route::delete('cms/faqs/{id}', [CmsCrudController::class, 'deleteFaq'])->name('CmsDeleteFaq');



        // Report
        Route::get('b2b/financial/report', [ReportController::class, 'b2bFinancialReport'])->name('B2bFinancialReport');
        Route::post('generate/b2b/financial/report', [ReportController::class, 'generateB2bFinancialReport'])->name('GenerateB2bFinancialReport');

        // ─── Rules Engine ───
        Route::get('rules/engine', [\App\Http\Controllers\RulesEngineController::class, 'index'])->name('RulesEngine');

        // Commission Rules
        Route::get('rules/commission/list', [\App\Http\Controllers\RulesEngineController::class, 'commissionRules'])->name('CommissionRulesList');
        Route::post('rules/commission/store', [\App\Http\Controllers\RulesEngineController::class, 'storeCommission'])->name('CommissionRuleStore');
        Route::get('rules/commission/{id}', [\App\Http\Controllers\RulesEngineController::class, 'getCommission'])->name('CommissionRuleGet');
        Route::delete('rules/commission/{id}', [\App\Http\Controllers\RulesEngineController::class, 'deleteCommission'])->name('CommissionRuleDelete');

        // Markup Rules
        Route::get('rules/markup/list', [\App\Http\Controllers\RulesEngineController::class, 'markupRules'])->name('MarkupRulesList');
        Route::post('rules/markup/store', [\App\Http\Controllers\RulesEngineController::class, 'storeMarkup'])->name('MarkupRuleStore');
        Route::get('rules/markup/{id}', [\App\Http\Controllers\RulesEngineController::class, 'getMarkup'])->name('MarkupRuleGet');
        Route::delete('rules/markup/{id}', [\App\Http\Controllers\RulesEngineController::class, 'deleteMarkup'])->name('MarkupRuleDelete');

        // Blocking Rules
        Route::get('rules/blocking/list', [\App\Http\Controllers\RulesEngineController::class, 'blockingRules'])->name('BlockingRulesList');
        Route::post('rules/blocking/store', [\App\Http\Controllers\RulesEngineController::class, 'storeBlocking'])->name('BlockingRuleStore');
        Route::get('rules/blocking/{id}', [\App\Http\Controllers\RulesEngineController::class, 'getBlocking'])->name('BlockingRuleGet');
        Route::delete('rules/blocking/{id}', [\App\Http\Controllers\RulesEngineController::class, 'deleteBlocking'])->name('BlockingRuleDelete');

        // ─── B2B Section ────────────────────────────────────────────────────────
        Route::get('b2b/flight-bookings', [\App\Http\Controllers\B2bController::class, 'flightBookings'])->name('B2bFlightBookings');
        Route::get('b2b/tour-bookings', [\App\Http\Controllers\B2bController::class, 'tourBookings'])->name('B2bTourBookings');
        Route::get('b2b/registration-requests', [\App\Http\Controllers\B2bController::class, 'registrationRequests'])->name('B2bRegistrationRequests');
        Route::post('b2b/registration-requests/{id}/update', [\App\Http\Controllers\B2bController::class, 'updateRegistrationRequest'])->name('B2bUpdateRegistrationRequest');
        Route::get('b2b/partial-pay-bookings', [\App\Http\Controllers\B2bController::class, 'partialPayBookings'])->name('B2bPartialPayBookings');
        Route::get('b2b/pending-ticket-issuance', [\App\Http\Controllers\B2bController::class, 'pendingTicketIssuance'])->name('B2bPendingTicketIssuance');
        Route::get('b2b/agency-list', [\App\Http\Controllers\B2bController::class, 'agencyList'])->name('B2bAgencyList');
        Route::post('b2b/agency/{userId}/add-money', [\App\Http\Controllers\B2bController::class, 'addMoneyToAgency'])->name('B2bAddMoneyToAgency');
        Route::get('b2b/upcoming-flights', [\App\Http\Controllers\B2bController::class, 'upcomingFlights'])->name('B2bUpcomingFlights');

        // ─── B2C Section ────────────────────────────────────────────────────────
        Route::get('b2c/flight-bookings', [\App\Http\Controllers\B2cAdminController::class, 'flightBookings'])->name('B2cFlightBookings');
        Route::get('b2c/flight-bookings/{id}', [\App\Http\Controllers\B2cAdminController::class, 'flightBookingDetail'])->name('B2cFlightBookingDetail');
        Route::get('b2c/tour-bookings', [\App\Http\Controllers\B2cAdminController::class, 'tourBookings'])->name('B2cTourBookings');
        Route::get('b2c/tour-bookings/{id}', [\App\Http\Controllers\B2cAdminController::class, 'tourBookingDetail'])->name('B2cTourBookingDetail');
        Route::get('b2c/user-list', [\App\Http\Controllers\B2cAdminController::class, 'userList'])->name('B2cUserList');
        Route::get('b2c/user/{id}', [\App\Http\Controllers\B2cAdminController::class, 'userDetail'])->name('B2cUserDetail');
        Route::get('b2c/upcoming-flights', [\App\Http\Controllers\B2cAdminController::class, 'upcomingFlights'])->name('B2cUpcomingFlights');

        // ─── B2C Configuration ───────────────────────────────────────────────────
        Route::get('b2c/config/commission', [\App\Http\Controllers\B2cAdminController::class, 'commission'])->name('B2cCommission');
        Route::post('b2c/config/commission', [\App\Http\Controllers\B2cAdminController::class, 'assignCommission'])->name('B2cAssignCommission');

        Route::get('b2c/config/terms-conditions', [\App\Http\Controllers\B2cAdminController::class, 'termsConditions'])->name('B2cTermsConditions');
        Route::post('b2c/config/terms-conditions', [\App\Http\Controllers\B2cAdminController::class, 'saveTermsConditions'])->name('B2cSaveTermsConditions');

        Route::get('b2c/config/privacy-policy', [\App\Http\Controllers\B2cAdminController::class, 'privacyPolicy'])->name('B2cPrivacyPolicy');
        Route::post('b2c/config/privacy-policy', [\App\Http\Controllers\B2cAdminController::class, 'savePrivacyPolicy'])->name('B2cSavePrivacyPolicy');

        Route::get('b2c/config/coin-config', [\App\Http\Controllers\B2cAdminController::class, 'coinConfig'])->name('B2cCoinConfig');
        Route::post('b2c/config/coin-config', [\App\Http\Controllers\B2cAdminController::class, 'saveCoinConfig'])->name('B2cSaveCoinConfig');

        // Gallery
        Route::get('b2c/config/gallery', [\App\Http\Controllers\B2cAdminController::class, 'gallery'])->name('B2cGallery');
        Route::post('b2c/config/gallery', [\App\Http\Controllers\B2cAdminController::class, 'storeGallery'])->name('B2cStoreGallery');
        Route::put('b2c/config/gallery/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateGallery'])->name('B2cUpdateGallery');
        Route::delete('b2c/config/gallery/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteGallery'])->name('B2cDeleteGallery');

        // Social Media
        Route::get('b2c/config/social-media', [\App\Http\Controllers\B2cAdminController::class, 'socialMedia'])->name('B2cSocialMedia');
        Route::post('b2c/config/social-media', [\App\Http\Controllers\B2cAdminController::class, 'storeSocialMedia'])->name('B2cStoreSocialMedia');
        Route::put('b2c/config/social-media/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateSocialMedia'])->name('B2cUpdateSocialMedia');
        Route::delete('b2c/config/social-media/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteSocialMedia'])->name('B2cDeleteSocialMedia');

        // YouTube Links
        Route::get('b2c/config/youtube-links', [\App\Http\Controllers\B2cAdminController::class, 'youtubeLinks'])->name('B2cYoutubeLinks');
        Route::post('b2c/config/youtube-links', [\App\Http\Controllers\B2cAdminController::class, 'storeYoutubeLink'])->name('B2cStoreYoutubeLink');
        Route::put('b2c/config/youtube-links/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateYoutubeLink'])->name('B2cUpdateYoutubeLink');
        Route::delete('b2c/config/youtube-links/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteYoutubeLink'])->name('B2cDeleteYoutubeLink');

        // Film Watch
        Route::get('b2c/config/film-watch', [\App\Http\Controllers\B2cAdminController::class, 'filmWatch'])->name('B2cFilmWatch');
        Route::post('b2c/config/film-watch', [\App\Http\Controllers\B2cAdminController::class, 'storeFilmWatch'])->name('B2cStoreFilm');
        Route::put('b2c/config/film-watch/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateFilmWatch'])->name('B2cUpdateFilm');
        Route::delete('b2c/config/film-watch/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteFilmWatch'])->name('B2cDeleteFilm');

        // Popular Destinations
        Route::get('b2c/config/popular-destinations', [\App\Http\Controllers\B2cAdminController::class, 'popularDestinations'])->name('B2cPopularDestinations');
        Route::post('b2c/config/popular-destinations', [\App\Http\Controllers\B2cAdminController::class, 'storeDestination'])->name('B2cStoreDestination');
        Route::put('b2c/config/popular-destinations/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateDestination'])->name('B2cUpdateDestination');
        Route::delete('b2c/config/popular-destinations/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteDestination'])->name('B2cDeleteDestination');

        // Special Offers (Hot Deals & AD)
        Route::get('b2c/config/offers/{type}', [\App\Http\Controllers\B2cAdminController::class, 'specialOfferList'])->name('B2cSpecialOfferList');
        Route::get('b2c/config/offers/{type}/create', [\App\Http\Controllers\B2cAdminController::class, 'createOffer'])->name('B2cCreateOffer');
        Route::post('b2c/config/offers/{type}', [\App\Http\Controllers\B2cAdminController::class, 'storeOffer'])->name('B2cStoreOffer');
        Route::get('b2c/config/offer/{id}/details', [\App\Http\Controllers\B2cAdminController::class, 'detailsOffer'])->name('B2cDetailsOffer');
        Route::get('b2c/config/offers/{type}/{id}/edit', [\App\Http\Controllers\B2cAdminController::class, 'editOffer'])->name('B2cEditOffer');
        Route::put('b2c/config/offers/{type}/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateOffer'])->name('B2cUpdateOffer');
        Route::delete('b2c/config/offer/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteOffer'])->name('B2cDeleteOffer');
        Route::post('b2c/config/offer/{id}/toggle', [\App\Http\Controllers\B2cAdminController::class, 'toggleOfferActive'])->name('B2cToggleOffer');

        // Banners (type=banner via offers + dedicated routes)
        Route::get('b2c/config/banners', [\App\Http\Controllers\B2cAdminController::class, 'bannerList'])->name('B2cBannerList');
        Route::get('b2c/config/banners/{id}/edit', [\App\Http\Controllers\B2cAdminController::class, 'editBanner'])->name('B2cEditBanner');
        Route::put('b2c/config/banners/{id}', [\App\Http\Controllers\B2cAdminController::class, 'updateBanner'])->name('B2cUpdateBanner');
        Route::post('b2c/config/banners', [\App\Http\Controllers\B2cAdminController::class, 'storeBanner'])->name('B2cStoreBanner');
        Route::delete('b2c/config/banners/{id}', [\App\Http\Controllers\B2cAdminController::class, 'deleteBanner'])->name('B2cDeleteBanner');

        // Footer Info
        Route::get('b2c/config/footer-info', [\App\Http\Controllers\B2cAdminController::class, 'footerInfo'])->name('B2cFooterInfo');
        Route::post('b2c/config/footer-info', [\App\Http\Controllers\B2cAdminController::class, 'saveFooterInfo'])->name('B2cSaveFooterInfo');

        // ── Configuration ──────────────────────────────────────────────────────

        // Dynamic Fare Rules
        Route::get('configuration/dynamic-fare-rules', [\App\Http\Controllers\ConfigurationController::class, 'dynamicFareRules'])->name('ConfigDynamicFareRules');
        Route::post('configuration/dynamic-fare-rules', [\App\Http\Controllers\ConfigurationController::class, 'storeDynamicFareRule'])->name('ConfigStoreDynamicFareRule');
        Route::put('configuration/dynamic-fare-rules/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateDynamicFareRule'])->name('ConfigUpdateDynamicFareRule');
        Route::delete('configuration/dynamic-fare-rules/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteDynamicFareRule'])->name('ConfigDeleteDynamicFareRule');
        Route::post('configuration/dynamic-fare-rules/{id}/toggle', [\App\Http\Controllers\ConfigurationController::class, 'toggleDynamicFareRule'])->name('ConfigToggleDynamicFareRule');

        // Partial Payment Rules
        Route::get('configuration/partial-payment-rules', [\App\Http\Controllers\ConfigurationController::class, 'partialPaymentRules'])->name('ConfigPartialPaymentRules');
        Route::post('configuration/partial-payment-rules', [\App\Http\Controllers\ConfigurationController::class, 'storePartialPaymentRule'])->name('ConfigStorePartialPaymentRule');
        Route::put('configuration/partial-payment-rules/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updatePartialPaymentRule'])->name('ConfigUpdatePartialPaymentRule');
        Route::delete('configuration/partial-payment-rules/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deletePartialPaymentRule'])->name('ConfigDeletePartialPaymentRule');

        // Block Routes
        Route::get('configuration/block-routes', [\App\Http\Controllers\ConfigurationController::class, 'blockRoutes'])->name('ConfigBlockRoutes');
        Route::post('configuration/block-routes', [\App\Http\Controllers\ConfigurationController::class, 'storeBlockRoute'])->name('ConfigStoreBlockRoute');
        Route::put('configuration/block-routes/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateBlockRoute'])->name('ConfigUpdateBlockRoute');
        Route::delete('configuration/block-routes/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteBlockRoute'])->name('ConfigDeleteBlockRoute');

        // Airports
        Route::get('configuration/airports', [\App\Http\Controllers\ConfigurationController::class, 'airports'])->name('ConfigAirports');
        Route::post('configuration/airports', [\App\Http\Controllers\ConfigurationController::class, 'storeAirport'])->name('ConfigStoreAirport');
        Route::put('configuration/airports/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateAirport'])->name('ConfigUpdateAirport');
        Route::delete('configuration/airports/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteAirport'])->name('ConfigDeleteAirport');

        // Airlines
        Route::get('configuration/airlines', [\App\Http\Controllers\ConfigurationController::class, 'airlines'])->name('ConfigAirlines');
        Route::post('configuration/airlines', [\App\Http\Controllers\ConfigurationController::class, 'storeAirline'])->name('ConfigStoreAirline');
        Route::put('configuration/airlines/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateAirline'])->name('ConfigUpdateAirline');
        Route::delete('configuration/airlines/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteAirline'])->name('ConfigDeleteAirline');

        // Tracking
        Route::get('configuration/tracking', [\App\Http\Controllers\ConfigurationController::class, 'tracking'])->name('ConfigTracking');
        Route::post('configuration/tracking/{type}/update', [\App\Http\Controllers\ConfigurationController::class, 'updateTrackingByType'])->name('ConfigUpdateTrackingByType');
        Route::post('configuration/tracking', [\App\Http\Controllers\ConfigurationController::class, 'storeTracking'])->name('ConfigStoreTracking');
        Route::put('configuration/tracking/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateTracking'])->name('ConfigUpdateTracking');
        Route::delete('configuration/tracking/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteTracking'])->name('ConfigDeleteTracking');

        // Cities
        Route::get('configuration/cities', [\App\Http\Controllers\ConfigurationController::class, 'cities'])->name('ConfigCities');
        Route::post('configuration/cities', [\App\Http\Controllers\ConfigurationController::class, 'storeCity'])->name('ConfigStoreCity');
        Route::delete('configuration/cities', [\App\Http\Controllers\ConfigurationController::class, 'deleteCity'])->name('ConfigDeleteCity');

        // Announcements
        Route::get('configuration/announcements', [\App\Http\Controllers\ConfigurationController::class, 'announcements'])->name('ConfigAnnouncements');
        Route::post('configuration/announcements', [\App\Http\Controllers\ConfigurationController::class, 'storeAnnouncement'])->name('ConfigStoreAnnouncement');
        Route::put('configuration/announcements/{id}', [\App\Http\Controllers\ConfigurationController::class, 'updateAnnouncement'])->name('ConfigUpdateAnnouncement');
        Route::delete('configuration/announcements/{id}', [\App\Http\Controllers\ConfigurationController::class, 'deleteAnnouncement'])->name('ConfigDeleteAnnouncement');
        Route::post('configuration/announcements/{id}/toggle', [\App\Http\Controllers\ConfigurationController::class, 'toggleAnnouncement'])->name('ConfigToggleAnnouncement');

    });

});
