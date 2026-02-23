

<?php $__env->startSection('header_css'); ?>
<style>
    .dashboard-container { padding: 20px 15px; }

    /* ─── Stat Cards ─── */
    .stat-card {
        border: none;
        border-radius: 12px;
        padding: 22px 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card .stat-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 48px;
        opacity: 0.2;
    }
    .stat-card .stat-value { font-size: 32px; font-weight: 700; line-height: 1.1; }
    .stat-card .stat-label { font-size: 13px; opacity: 0.85; margin-top: 4px; }
    .stat-card .stat-sub { font-size: 12px; opacity: 0.7; margin-top: 8px; }

    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .bg-gradient-info    { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

    /* ─── Status Badges ─── */
    .status-bar { display: flex; gap: 10px; flex-wrap: wrap; }
    .status-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;
        background: #f0f2f5; color: #444;
    }
    .status-chip .count { font-weight: 700; font-size: 15px; }
    .status-chip.pending   { background: #fff3cd; color: #856404; }
    .status-chip.confirmed { background: #d1e7dd; color: #0f5132; }
    .status-chip.issued    { background: #cff4fc; color: #055160; }
    .status-chip.cancelled { background: #f8d7da; color: #842029; }
    .status-chip.voided    { background: #e2e3e5; color: #41464b; }

    /* ─── Section Cards ─── */
    .section-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .section-card .section-title {
        font-size: 16px; font-weight: 600; color: #333;
        margin-bottom: 16px; padding-bottom: 10px;
        border-bottom: 2px solid #f0f2f5;
    }
    .section-card .section-title i { margin-right: 6px; color: #667eea; }

    /* ─── Tables ─── */
    .dashboard-table { width: 100%; font-size: 13px; }
    .dashboard-table th {
        background: #f8f9fa; color: #666; font-weight: 600;
        padding: 10px 12px; border-bottom: 2px solid #e9ecef; font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .dashboard-table td { padding: 10px 12px; border-bottom: 1px solid #f0f2f5; color: #444; }
    .dashboard-table tbody tr:hover { background: #f8f9fa; }

    /* ─── Status Badges in Table ─── */
    .badge-status {
        padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;
    }
    .badge-pending   { background: #fff3cd; color: #856404; }
    .badge-booked    { background: #d1e7dd; color: #0f5132; }
    .badge-ticketed  { background: #cff4fc; color: #055160; }
    .badge-cancelled { background: #f8d7da; color: #842029; }
    .badge-voided    { background: #e2e3e5; color: #41464b; }

    /* ─── Route Badge ─── */
    .route-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-weight: 600; font-size: 14px; color: #333;
    }
    .route-badge .arrow { color: #667eea; font-size: 16px; }

    /* ─── GDS Cards ─── */
    .gds-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 18px; position: relative; overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .gds-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
    .gds-card .gds-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f2f5;
    }
    .gds-card .gds-name { font-size: 18px; font-weight: 700; }
    .gds-card .gds-stat { text-align: center; }
    .gds-card .gds-stat-value { font-size: 20px; font-weight: 700; line-height: 1.2; }
    .gds-card .gds-stat-label { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; }
    .gds-sabre .gds-name { color: #dc3545; }
    .gds-flyhub .gds-name { color: #fd7e14; }

    @media (max-width: 767px) {
        .stat-card .stat-value { font-size: 24px; }
        .stat-card .stat-icon { font-size: 36px; }
        .quick-actions { gap: 8px !important; }
        .quick-action-btn { padding: 8px 14px !important; font-size: 12px !important; }
    }

    /* ─── Quick Actions ─── */
    .quick-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .quick-action-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600;
        border: 1.5px solid #e2e5ea; background: #fff; color: #444;
        text-decoration: none; transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .quick-action-btn:hover {
        transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.15);
        border-color: #667eea; color: #667eea;
    }
    .quick-action-btn i { font-size: 20px; }
    .quick-action-btn.primary { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border-color: transparent; }
    .quick-action-btn.primary:hover { box-shadow: 0 4px 15px rgba(102,126,234,0.35); color: #fff; }

    /* ─── Trend Indicators ─── */
    .trend-badge {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 8px; font-size: 11px; font-weight: 700;
        margin-left: 6px;
    }
    .trend-badge.up { background: rgba(255,255,255,0.25); color: #fff; }
    .trend-badge.down { background: rgba(255,255,255,0.25); color: rgba(255,255,255,0.9); }
    .trend-badge.neutral { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.7); }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Dashboard</h4>
            <p class="text-muted mb-0 fs-14">Welcome back, <?php echo e(Auth::user()->name); ?>. Here's your business overview.</p>
        </div>
        <span class="text-muted fs-13"><?php echo e(now()->format('l, d M Y')); ?></span>
    </div>

    
    <div class="quick-actions mb-4">
        <a href="<?php echo e(url('/home')); ?>" class="quick-action-btn primary">
            <i class="typcn typcn-plane-outline"></i> Search Flights
        </a>
        <a href="<?php echo e(url('view/all/booking')); ?>" class="quick-action-btn">
            <i class="typcn typcn-th-list-outline"></i> View Bookings
        </a>
        <a href="<?php echo e(url('view/saved/passengers')); ?>" class="quick-action-btn">
            <i class="typcn typcn-user-outline"></i> Saved Passengers
        </a>
        <a href="<?php echo e(url('flight/booking/report')); ?>" class="quick-action-btn">
            <i class="typcn typcn-chart-bar-outline"></i> Reports
        </a>
    </div>

    
    <?php
        // Calculate comparison percentages
        $bookingChange = $yesterdayBookings > 0 ? round((($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100) : ($todayBookings > 0 ? 100 : 0);
        $revenueChange = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100) : ($todayRevenue > 0 ? 100 : 0);
        $monthBookingChange = $lastMonthBookings > 0 ? round((($monthBookings - $lastMonthBookings) / $lastMonthBookings) * 100) : ($monthBookings > 0 ? 100 : 0);
    ?>
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card bg-gradient-primary">
                <i class="typcn typcn-plane-outline stat-icon"></i>
                <div class="stat-value"><?php echo e(number_format($totalBookings)); ?></div>
                <div class="stat-label">Total Bookings
                    <?php if($bookingChange != 0): ?>
                        <span class="trend-badge <?php echo e($bookingChange > 0 ? 'up' : 'down'); ?>">
                            <?php echo e($bookingChange > 0 ? '↑' : '↓'); ?><?php echo e(abs($bookingChange)); ?>%
                        </span>
                    <?php else: ?>
                        <span class="trend-badge neutral">→ 0%</span>
                    <?php endif; ?>
                </div>
                <div class="stat-sub">Today: <?php echo e($todayBookings); ?> • Yesterday: <?php echo e($yesterdayBookings); ?> • Month: <?php echo e($monthBookings); ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card bg-gradient-success">
                <i class="typcn typcn-ticket stat-icon"></i>
                <div class="stat-value"><?php echo e(number_format($issuedTickets)); ?></div>
                <div class="stat-label">Issued Tickets</div>
                <div class="stat-sub"><?php echo e($confirmedBookings); ?> awaiting • <?php echo e($pendingBookings); ?> pending • <?php echo e($cancelledBookings); ?> cancelled</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card bg-gradient-warning">
                <i class="typcn typcn-chart-bar-outline stat-icon"></i>
                <div class="stat-value">৳<?php echo e(number_format($totalRevenue, 0)); ?></div>
                <div class="stat-label">Total Revenue
                    <?php if($revenueChange != 0): ?>
                        <span class="trend-badge <?php echo e($revenueChange > 0 ? 'up' : 'down'); ?>">
                            <?php echo e($revenueChange > 0 ? '↑' : '↓'); ?><?php echo e(abs($revenueChange)); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <div class="stat-sub">Today: ৳<?php echo e(number_format($todayRevenue, 0)); ?> • Month: ৳<?php echo e(number_format($monthRevenue, 0)); ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card bg-gradient-info">
                <i class="typcn typcn-user-outline stat-icon"></i>
                <div class="stat-value"><?php echo e($totalCustomers); ?></div>
                <div class="stat-label">Registered Customers
                    <?php if($monthBookingChange != 0): ?>
                        <span class="trend-badge <?php echo e($monthBookingChange > 0 ? 'up' : 'down'); ?>">
                            <?php echo e($monthBookingChange > 0 ? '↑' : '↓'); ?><?php echo e(abs($monthBookingChange)); ?>% MoM
                        </span>
                    <?php endif; ?>
                </div>
                <div class="stat-sub">Active customers on the platform</div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        
        <div class="col-lg-5">
            <div class="section-card h-100">
                <div class="section-title"><i class="typcn typcn-info-outline"></i> Booking Status Breakdown</div>
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    
                    <div style="position: relative; width: 160px; height: 160px; flex-shrink: 0;">
                        <canvas id="statusDonutChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #333; line-height: 1;"><?php echo e($totalBookings); ?></div>
                            <div style="font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 0.5px;">Total</div>
                        </div>
                    </div>
                    
                    <div class="status-bar" style="flex: 1;">
                        <div class="status-chip pending">
                            <span class="count"><?php echo e($pendingBookings); ?></span> Pending
                        </div>
                        <div class="status-chip confirmed">
                            <span class="count"><?php echo e($confirmedBookings); ?></span> Booked
                        </div>
                        <div class="status-chip issued">
                            <span class="count"><?php echo e($issuedTickets); ?></span> Ticketed
                        </div>
                        <div class="status-chip cancelled">
                            <span class="count"><?php echo e($cancelledBookings); ?></span> Cancelled
                        </div>
                        <div class="status-chip voided">
                            <span class="count"><?php echo e($voidedTickets); ?></span> Voided
                        </div>
                    </div>
                </div>

                <?php if($topRoutes->count() > 0): ?>
                <div class="mt-4">
                    <div class="section-title" style="border-bottom: none; margin-bottom: 10px; padding-bottom: 0;">
                        <i class="typcn typcn-location-outline"></i> Top Routes
                    </div>
                    <?php $__currentLoopData = $topRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #f0f2f5;">
                        <div class="route-badge">
                            <?php echo e($route->departure_location); ?>

                            <span class="arrow">→</span>
                            <?php echo e($route->arrival_location); ?>

                        </div>
                        <div>
                            <span class="badge bg-light text-dark"><?php echo e($route->total_bookings); ?> bookings</span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="section-card h-100">
                <div class="section-title d-flex justify-content-between align-items-center">
                    <span><i class="typcn typcn-chart-area-outline"></i> Revenue Overview</span>
                    <span class="text-muted" style="font-size: 12px; font-weight: 400;">Last 6 Months</span>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="revenueChart"></canvas>
                </div>
                <?php if($monthlyRevenue->count() > 0): ?>
                <div class="d-flex justify-content-around mt-3 pt-3" style="border-top: 1px solid #f0f2f5;">
                    <div class="text-center">
                        <div style="font-size: 20px; font-weight: 700; color: #667eea;"><?php echo e(number_format($monthlyRevenue->sum('bookings'))); ?></div>
                        <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px;">Total Bookings</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size: 20px; font-weight: 700; color: #11998e;">৳<?php echo e(number_format($monthlyRevenue->sum('revenue'), 0)); ?></div>
                        <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px;">Total Revenue</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size: 20px; font-weight: 700; color: #f5576c;">৳<?php echo e($monthlyRevenue->count() > 0 ? number_format($monthlyRevenue->avg('revenue'), 0) : 0); ?></div>
                        <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px;">Avg / Month</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="section-card">
        <div class="section-title"><i class="typcn typcn-globe-outline"></i> GDS Performance</div>
        <div class="row g-3">
            <?php
                $gdsProviders = [
                    'Sabre' => ['class' => 'gds-sabre', 'icon' => '✈️'],
                    'Flyhub' => ['class' => 'gds-flyhub', 'icon' => '🌐'],
                ];
            ?>
            <?php $__currentLoopData = $gdsProviders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gdsName => $gdsConfig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $gds = $gdsPerformance->get($gdsName);
                    $gdsTotal = $gds->total_bookings ?? 0;
                    $gdsSuccess = $gds->successful_bookings ?? 0;
                    $gdsRevenue = $gds->revenue ?? 0;
                    $gdsCancelled = $gds->cancelled ?? 0;
                    $successRate = $gdsTotal > 0 ? round(($gdsSuccess / $gdsTotal) * 100) : 0;
                ?>
                <div class="col-lg-6 col-md-6">
                    <div class="gds-card <?php echo e($gdsConfig['class']); ?>">
                        <div class="gds-header">
                            <span class="gds-name"><?php echo e($gdsConfig['icon']); ?> <?php echo e($gdsName); ?></span>
                            <span class="badge bg-light text-dark" style="font-size: 11px;"><?php echo e($gdsTotal); ?> total</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="gds-stat">
                                <div class="gds-stat-value text-success"><?php echo e($gdsSuccess); ?></div>
                                <div class="gds-stat-label">Successful</div>
                            </div>
                            <div class="gds-stat">
                                <div class="gds-stat-value" style="color: #667eea;">৳<?php echo e(number_format($gdsRevenue, 0)); ?></div>
                                <div class="gds-stat-label">Revenue</div>
                            </div>
                            <div class="gds-stat">
                                <div class="gds-stat-value text-danger"><?php echo e($gdsCancelled); ?></div>
                                <div class="gds-stat-label">Cancelled</div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size: 11px; color: #666;">Success Rate</span>
                                <span style="font-size: 11px; font-weight: 700; color: <?php echo e($successRate >= 70 ? '#198754' : ($successRate >= 40 ? '#ffc107' : '#dc3545')); ?>;"><?php echo e($successRate); ?>%</span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar" style="width: <?php echo e($successRate); ?>%; border-radius: 3px; background: <?php echo e($successRate >= 70 ? '#198754' : ($successRate >= 40 ? '#ffc107' : '#dc3545')); ?>;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="section-card">
        <div class="section-title d-flex justify-content-between align-items-center">
            <span><i class="typcn typcn-time"></i> Recent Bookings</span>
            <a href="<?php echo e(url('view/all/booking')); ?>" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">View All</a>
        </div>
        <div class="table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Booking No</th>
                        <th>Traveller</th>
                        <th>Route</th>
                        <th>GDS</th>
                        <th class="text-end">Fare</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(url('flight/booking/details/' . $booking->booking_no)); ?>" class="text-decoration-none fw-bold" style="color: #667eea;">
                                <?php echo e($booking->booking_no); ?>

                            </a>
                        </td>
                        <td><?php echo e(Str::limit($booking->traveller_name, 25)); ?></td>
                        <td>
                            <span class="route-badge" style="font-size: 13px;">
                                <?php echo e($booking->departure_location); ?>

                                <span class="arrow" style="font-size: 13px;">→</span>
                                <?php echo e($booking->arrival_location); ?>

                            </span>
                        </td>
                        <td><span class="badge bg-light text-dark"><?php echo e($booking->gds); ?></span></td>
                        <td class="text-end fw-bold"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_fare, 0)); ?></td>
                        <td>
                            <?php switch($booking->status):
                                case (0): ?>
                                    <span class="badge-status badge-pending">Pending</span>
                                    <?php break; ?>
                                <?php case (1): ?>
                                    <span class="badge-status badge-booked">Booked</span>
                                    <?php break; ?>
                                <?php case (2): ?>
                                    <span class="badge-status badge-ticketed">Ticketed</span>
                                    <?php break; ?>
                                <?php case (3): ?>
                                    <span class="badge-status badge-cancelled">Cancelled</span>
                                    <?php break; ?>
                                <?php case (4): ?>
                                    <span class="badge-status badge-voided">Voided</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td class="text-muted"><?php echo e($booking->created_at->format('d M, h:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No bookings yet</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Gradient for bars
    const barGradient = ctx.createLinearGradient(0, 0, 0, 260);
    barGradient.addColorStop(0, 'rgba(102, 126, 234, 0.85)');
    barGradient.addColorStop(1, 'rgba(118, 75, 162, 0.4)');

    // Gradient for line fill
    const lineGradient = ctx.createLinearGradient(0, 0, 0, 260);
    lineGradient.addColorStop(0, 'rgba(17, 153, 142, 0.3)');
    lineGradient.addColorStop(1, 'rgba(56, 239, 125, 0.02)');

    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthlyRevenue->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m->month)->format('M Y'))->values()); ?>,
            datasets: [
                {
                    label: 'Revenue (৳)',
                    data: <?php echo json_encode($monthlyRevenue->pluck('revenue')->values()); ?>,
                    backgroundColor: barGradient,
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Bookings',
                    data: <?php echo json_encode($monthlyRevenue->pluck('bookings')->values()); ?>,
                    type: 'line',
                    borderColor: '#11998e',
                    backgroundColor: lineGradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#11998e',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        borderRadius: 3,
                        useBorderRadius: true,
                        padding: 16,
                        font: { size: 11, family: 'inherit' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 30, 50, 0.92)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label.includes('Revenue')) {
                                return ' Revenue: ৳' + context.parsed.y.toLocaleString();
                            }
                            return ' Bookings: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '500' }, color: '#888' }
                },
                y: {
                    position: 'left',
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: {
                        font: { size: 10 },
                        color: '#aaa',
                        callback: function(value) {
                            if (value >= 1000000) return '৳' + (value/1000000).toFixed(1) + 'M';
                            if (value >= 1000) return '৳' + (value/1000).toFixed(0) + 'K';
                            return '৳' + value;
                        }
                    },
                    border: { display: false }
                },
                y1: {
                    position: 'right',
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        color: '#11998e',
                        stepSize: 1
                    },
                    border: { display: false }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // ── Status Donut Chart ──
    const donutCtx = document.getElementById('statusDonutChart').getContext('2d');
    const statusData = [
        <?php echo e($pendingBookings); ?>,
        <?php echo e($confirmedBookings); ?>,
        <?php echo e($issuedTickets); ?>,
        <?php echo e($cancelledBookings); ?>,
        <?php echo e($voidedTickets); ?>

    ];
    const hasData = statusData.some(v => v > 0);

    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: hasData ? ['Pending', 'Booked', 'Ticketed', 'Cancelled', 'Voided'] : ['No Data'],
            datasets: [{
                data: hasData ? statusData : [1],
                backgroundColor: hasData
                    ? ['#ffc107', '#198754', '#0dcaf0', '#dc3545', '#6c757d']
                    : ['#e9ecef'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: hasData,
                    backgroundColor: 'rgba(30, 30, 50, 0.92)',
                    padding: 10,
                    cornerRadius: 8,
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                            return ` ${context.label}: ${context.parsed} (${pct}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 800,
                easing: 'easeOutQuart'
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/dashboard.blade.php ENDPATH**/ ?>