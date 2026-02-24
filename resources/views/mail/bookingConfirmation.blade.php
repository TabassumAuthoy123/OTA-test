<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #084277;
            color: #fff;
            padding: 20px 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .body {
            padding: 30px;
            color: #333;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #084277;
        }

        .footer {
            padding: 20px 30px;
            text-align: center;
            color: #999;
            font-size: 12px;
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✈️ Booking Confirmation</h1>
        </div>
        <div class="body">
            <p>Dear {{ $mailData['agent_name'] ?? 'Agent' }},</p>
            <p>Your flight booking has been confirmed successfully.</p>

            <div style="margin: 20px 0;">
                <div class="info-row">
                    <span class="info-label">Booking No:</span>
                    <span class="info-value">{{ $mailData['booking_no'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">PNR:</span>
                    <span class="info-value">{{ $mailData['pnr'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Route:</span>
                    <span class="info-value">{{ $mailData['route'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Passengers:</span>
                    <span class="info-value">{{ $mailData['passenger_count'] ?? '0' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="badge badge-success">{{ $mailData['status'] ?? 'Confirmed' }}</span>
                </div>
            </div>

            <p>Please log in to your dashboard for full details.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} OTA Platform. All rights reserved.
        </div>
    </div>
</body>

</html>