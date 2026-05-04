<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .wrap { max-width: 620px; margin: 0 auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.1); }
        .hdr  { background: linear-gradient(135deg,#0f1f3d,#1e3a5f); color: #fff; padding: 28px 32px; text-align: center; }
        .hdr h1 { margin: 0; font-size: 22px; letter-spacing: .5px; }
        .hdr p  { margin: 6px 0 0; font-size: 13px; opacity: .85; }
        .body { padding: 30px 32px; color: #333; }
        .greeting { font-size: 15px; margin-bottom: 18px; }
        .alert-box { background: #e8f5e9; border-left: 4px solid #2e7d32; padding: 12px 16px; border-radius: 4px; margin-bottom: 22px; font-size: 14px; color: #1b5e20; }
        .route-box { background: #0f1f3d; color: #fff; border-radius: 8px; padding: 18px 24px; text-align: center; margin-bottom: 24px; }
        .route-box .airports { font-size: 28px; font-weight: 800; letter-spacing: 2px; }
        .route-box .route-arrow { font-size: 20px; margin: 0 10px; opacity: .7; }
        .route-box .dep-date { font-size: 14px; opacity: .85; margin-top: 6px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.info td { padding: 9px 12px; font-size: 13px; border-bottom: 1px solid #eee; }
        table.info td:first-child { font-weight: 700; color: #0f1f3d; width: 40%; }
        .footer { background: #f8f9fa; padding: 18px 32px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .footer a { color: #2d6a9f; text-decoration: none; }
        .badge { display:inline-block; background:#f0a500; color:#0f1f3d; font-size:11px; font-weight:700; padding:3px 8px; border-radius:10px; }
    </style>
</head>
<body>
<div class="wrap">

    <div class="hdr">
        <h1>Flight Upcoming Alert — Agent Notice</h1>
        <p>One of your bookings departs in approximately 10 hours</p>
    </div>

    <div class="body">

        <p class="greeting">
            Dear <strong>{{ $d['agent_name'] ?? 'Agent' }}</strong>
            @if(!empty($d['agent_code'])) <span class="badge">{{ $d['agent_code'] }}</span> @endif,
        </p>

        <div class="alert-box">
            This is an automated reminder that a ticketed booking under your account departs in approximately <strong>10 hours</strong>. Please ensure the passenger is informed and prepared.
        </div>

        <div class="route-box">
            <div class="airports">
                {{ strtoupper($d['departure_location'] ?? '???') }}
                <span class="route-arrow">→</span>
                {{ strtoupper($d['arrival_location'] ?? '???') }}
            </div>
            <div class="dep-date">Departure: {{ $d['departure_date'] ?? '—' }}</div>
        </div>

        <table class="info">
            <tr>
                <td>Booking Reference</td>
                <td><strong style="color:#0f1f3d;">{{ $d['booking_no'] ?? '—' }}</strong></td>
            </tr>
            <tr>
                <td>GDS / PNR</td>
                <td>{{ $d['pnr'] ?? '—' }}</td>
            </tr>
            @if(!empty($d['airlines_pnr']))
            <tr>
                <td>Airlines PNR</td>
                <td>{{ $d['airlines_pnr'] }}</td>
            </tr>
            @endif
            <tr>
                <td>Airline</td>
                <td>{{ $d['governing_carriers'] ?? '—' }}</td>
            </tr>
            <tr>
                <td>Traveller</td>
                <td>{{ $d['traveller_name'] ?? '—' }}</td>
            </tr>
            <tr>
                <td>Passengers</td>
                <td>{{ $d['passenger_names'] ?? '—' }}</td>
            </tr>
            <tr>
                <td>Traveller Contact</td>
                <td>{{ $d['contact'] ?? '—' }}</td>
            </tr>
            <tr>
                <td>Total Fare</td>
                <td><strong>{{ !empty($d['total_fare']) ? 'BDT '.number_format($d['total_fare'],2) : '—' }}</strong></td>
            </tr>
        </table>

        <p style="font-size:13px;color:#666;">
            View full booking details in your
            <a href="{{ config('app.url').'/my/bookings/'.$d['booking_id'] }}" style="color:#2d6a9f;">B2B Portal Dashboard</a>.
        </p>
        <p style="font-size:13px;color:#666;">
            For support contact <a href="mailto:info@faithtrip.net" style="color:#2d6a9f;">info@faithtrip.net</a>.
        </p>

    </div>

    <div class="footer">
        &copy; {{ date('Y') }} FaithTrip. All rights reserved.<br>
        Abedin Tower (Level 5), 35 Kamal Ataturk Avenue, Banani, Dhaka-1213<br>
        <a href="mailto:info@faithtrip.net">info@faithtrip.net</a>
    </div>

</div>
</body>
</html>
