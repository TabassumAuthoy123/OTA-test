<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .wrap { max-width: 620px; margin: 0 auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.1); }
        .hdr  { background: linear-gradient(135deg,#1e3a5f,#2d6a9f); color: #fff; padding: 28px 32px; text-align: center; }
        .hdr h1 { margin: 0; font-size: 22px; letter-spacing: .5px; }
        .hdr p  { margin: 6px 0 0; font-size: 13px; opacity: .85; }
        .body { padding: 30px 32px; color: #333; }
        .greeting { font-size: 15px; margin-bottom: 18px; }
        .alert-box { background: #fff8e1; border-left: 4px solid #f0a500; padding: 12px 16px; border-radius: 4px; margin-bottom: 22px; font-size: 14px; color: #7a5200; }
        .route-box { background: #1e3a5f; color: #fff; border-radius: 8px; padding: 18px 24px; text-align: center; margin-bottom: 24px; }
        .route-box .airports { font-size: 28px; font-weight: 800; letter-spacing: 2px; }
        .route-box .route-arrow { font-size: 20px; margin: 0 10px; opacity: .7; }
        .route-box .dep-date { font-size: 14px; opacity: .85; margin-top: 6px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.info td { padding: 9px 12px; font-size: 13px; border-bottom: 1px solid #eee; }
        table.info td:first-child { font-weight: 700; color: #1e3a5f; width: 40%; }
        .tip { background: #e8f4fd; border-radius: 6px; padding: 14px 16px; font-size: 13px; color: #1e3a5f; margin-bottom: 20px; }
        .tip ul { margin: 8px 0 0; padding-left: 18px; }
        .tip li { margin-bottom: 4px; }
        .footer { background: #f8f9fa; padding: 18px 32px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .footer a { color: #2d6a9f; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrap">

    <div class="hdr">
        <h1>✈ Flight Departure Reminder</h1>
        <p>Your flight departs in approximately 10 hours</p>
    </div>

    <div class="body">

        <p class="greeting">
            Dear <strong>{{ $d['traveller_name'] ?? 'Passenger' }}</strong>,
        </p>

        <div class="alert-box">
            <strong>Reminder:</strong> Your upcoming flight departs in approximately 10 hours. Please ensure you are at the airport at least 2–3 hours before departure.
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
                <td><strong style="color:#1e3a5f;">{{ $d['booking_no'] ?? '—' }}</strong></td>
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
                <td>Passengers</td>
                <td>{{ $d['passenger_names'] ?? '—' }}</td>
            </tr>
            @if(!empty($d['contact']))
            <tr>
                <td>Contact</td>
                <td>{{ $d['contact'] }}</td>
            </tr>
            @endif
        </table>

        <div class="tip">
            <strong>Before you fly — checklist:</strong>
            <ul>
                <li>Carry valid passport / national ID</li>
                <li>Print or save your e-ticket / boarding pass</li>
                <li>Check baggage allowance for your booking</li>
                <li>Arrive at the airport 2–3 hours before departure</li>
                <li>Check web check-in availability with your airline</li>
            </ul>
        </div>

        <p style="font-size:13px;color:#666;">
            For any queries contact us at
            <a href="mailto:info@faithtrip.net" style="color:#2d6a9f;">info@faithtrip.net</a>
            or call our helpdesk. Have a safe and pleasant journey!
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
