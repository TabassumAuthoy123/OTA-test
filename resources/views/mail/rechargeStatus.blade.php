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
            padding: 20px 30px;
            color: #fff;
            text-align: center;
        }

        .header-approved {
            background: #28a745;
        }

        .header-denied {
            background: #dc3545;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header {{ ($mailData['status'] ?? '') == 'Approved' ? 'header-approved' : 'header-denied' }}">
            <h1>💳 Recharge Request {{ $mailData['status'] ?? 'Update' }}</h1>
        </div>
        <div class="body">
            <p>Dear {{ $mailData['user_name'] ?? 'User' }},</p>
            <p>Your recharge request has been <strong>{{ strtolower($mailData['status'] ?? 'updated') }}</strong>.</p>

            <div style="margin: 20px 0;">
                <div class="info-row">
                    <span class="info-label">Amount:</span>
                    <span class="info-value">৳{{ number_format($mailData['amount'] ?? 0, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transaction ID:</span>
                    <span class="info-value">{{ $mailData['transaction_id'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><strong>{{ $mailData['status'] ?? 'N/A' }}</strong></span>
                </div>
                @if(isset($mailData['new_balance']))
                    <div class="info-row">
                        <span class="info-label">New Balance:</span>
                        <span class="info-value">৳{{ number_format($mailData['new_balance'], 2) }}</span>
                    </div>
                @endif
            </div>

            <p>Log in to your dashboard to view details.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} OTA Platform. All rights reserved.
        </div>
    </div>
</body>

</html>