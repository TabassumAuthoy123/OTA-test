<?php

namespace App\Http\Controllers;

use App\Models\FlightBooking;
use App\Models\User;
use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        // ── Booking Stats ──
        $totalBookings = FlightBooking::count();
        $todayBookings = FlightBooking::whereDate('created_at', $now->toDateString())->count();
        $yesterdayBookings = FlightBooking::whereDate('created_at', $now->copy()->subDay()->toDateString())->count();
        $monthBookings = FlightBooking::where('created_at', '>=', $startOfMonth)->count();

        // ── Last Month Stats (for comparison) ──
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
        $lastMonthBookings = FlightBooking::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $lastMonthRevenue = FlightBooking::whereIn('status', [1, 2])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_fare');

        // ── Booking Status Breakdown ──
        $statusCounts = FlightBooking::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendingBookings = $statusCounts[0] ?? 0;
        $confirmedBookings = $statusCounts[1] ?? 0;
        $issuedTickets = $statusCounts[2] ?? 0;
        $cancelledBookings = $statusCounts[3] ?? 0;
        $voidedTickets = $statusCounts[4] ?? 0;

        // ── Revenue ──
        $totalRevenue = FlightBooking::whereIn('status', [1, 2])->sum('total_fare');
        $todayRevenue = FlightBooking::whereIn('status', [1, 2])
            ->whereDate('created_at', $now->toDateString())
            ->sum('total_fare');
        $yesterdayRevenue = FlightBooking::whereIn('status', [1, 2])
            ->whereDate('created_at', $now->copy()->subDay()->toDateString())
            ->sum('total_fare');
        $monthRevenue = FlightBooking::whereIn('status', [1, 2])
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_fare');

        // Monthly revenue for last 6 months
        $monthlyRevenue = FlightBooking::whereIn('status', [1, 2])
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total_fare) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // ── Users ──
        $totalB2bAgents = User::where('user_type', UserType::B2B->value)->count();
        $totalCustomers = User::where('user_type', UserType::B2C->value)->count();

        // ── Pending Recharges ──
        $pendingRecharges = DB::table('recharge_requests')->where('status', 0)->count();

        // ── Recent Bookings ──
        $recentBookings = FlightBooking::select(
            'booking_no',
            'traveller_name',
            'gds',
            'departure_location',
            'arrival_location',
            'total_fare',
            'currency',
            'status',
            'created_at'
        )
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ── Top Routes ──
        $topRoutes = FlightBooking::select(
            'departure_location',
            'arrival_location',
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_fare) as total_revenue')
        )
            ->whereIn('status', [1, 2])
            ->groupBy('departure_location', 'arrival_location')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        // ── GDS Performance ──
        $gdsPerformance = FlightBooking::select(
            'gds',
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(CASE WHEN status IN (1,2) THEN 1 ELSE 0 END) as successful_bookings'),
            DB::raw('SUM(CASE WHEN status IN (1,2) THEN total_fare ELSE 0 END) as revenue'),
            DB::raw('SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as cancelled')
        )
            ->groupBy('gds')
            ->get()
            ->keyBy('gds');

        return view('dashboard', compact(
            'totalBookings',
            'todayBookings',
            'yesterdayBookings',
            'monthBookings',
            'lastMonthBookings',
            'lastMonthRevenue',
            'pendingBookings',
            'confirmedBookings',
            'issuedTickets',
            'cancelledBookings',
            'voidedTickets',
            'totalRevenue',
            'todayRevenue',
            'yesterdayRevenue',
            'monthRevenue',
            'monthlyRevenue',
            'totalB2bAgents',
            'totalCustomers',
            'pendingRecharges',
            'recentBookings',
            'topRoutes',
            'gdsPerformance'
        ));
    }
}
