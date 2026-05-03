<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #084277; color: #fff; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0 0 8px; font-size: 28px; font-weight: 700; }
        .header p { margin: 0; font-size: 15px; opacity: 0.9; }
        .body { padding: 36px 30px; color: #333; }
        .body p { font-size: 15px; line-height: 1.6; }
        .cta-btn { display: inline-block; margin: 20px 0; background: #084277; color: #fff; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 600; }
        .features { background: #f8f9fa; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
        .features h3 { margin: 0 0 14px; font-size: 15px; color: #555; font-weight: 600; }
        .feature-item { display: flex; align-items: center; margin-bottom: 10px; font-size: 14px; color: #444; }
        .feature-item span.icon { font-size: 18px; margin-right: 10px; }
        .footer { padding: 20px 30px; text-align: center; color: #999; font-size: 12px; background: #f8f9fa; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✈️ Welcome to OTA Platform!</h1>
            <p>Now that you're here, why don't you have a look around?</p>
        </div>
        <div class="body">
            <p>Dear {{ $user->name }},</p>
            <p>Your account has been created successfully. You can now search and book flights at the best prices, anytime.</p>

            <div class="features">
                <h3>What you can do:</h3>
                <div class="feature-item"><span class="icon">🔍</span> Search hundreds of flights instantly</div>
                <div class="feature-item"><span class="icon">💰</span> Compare cheapest and direct flight options</div>
                <div class="feature-item"><span class="icon">📋</span> Manage your bookings from your account</div>
                <div class="feature-item"><span class="icon">🎫</span> Download e-tickets anytime</div>
            </div>

            <div style="text-align:center;">
                <a href="{{ url('/') }}" class="cta-btn">Search Flights</a>
            </div>

            <p style="font-size:13px; color:#888; margin-top:24px;">
                If you did not create this account, please ignore this email or contact our support team.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} OTA Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
