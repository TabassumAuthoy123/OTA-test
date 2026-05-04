<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Password Reset OTP</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;padding:40px 16px;">
  <tr><td align="center">
    <table width="520" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.10);">

      {{-- Header --}}
      <tr>
        <td style="background:linear-gradient(135deg,#0D1B5E,#1A3A8F);padding:28px 32px;text-align:center;">
          <div style="display:inline-flex;align-items:center;gap:8px;">
            <span style="font-size:1.5rem;font-weight:800;color:#fff;">Faith</span><span style="font-size:1.5rem;font-weight:800;color:#F5A623;">Trip</span>
          </div>
          <p style="color:rgba(255,255,255,.7);font-size:.82rem;margin:6px 0 0;">Your Trusted IATA-Certified Travel Agency</p>
        </td>
      </tr>

      {{-- Body --}}
      <tr>
        <td style="padding:36px 40px 28px;">
          <p style="font-size:1rem;color:#222;margin:0 0 8px;">Hello, <strong>{{ $name }}</strong></p>
          <p style="font-size:.9rem;color:#555;margin:0 0 28px;line-height:1.6;">
            You requested a password reset for your FaithTrip account. Use the OTP below within <strong>15 minutes</strong>.
          </p>

          {{-- OTP Box --}}
          <div style="background:#f4f6ff;border:2px dashed #0D1B5E;border-radius:12px;padding:22px;text-align:center;margin-bottom:28px;">
            <p style="font-size:.78rem;color:#888;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin:0 0 10px;">Your OTP Code</p>
            <span style="font-size:2.6rem;font-weight:900;letter-spacing:14px;color:#0D1B5E;">{{ $otp }}</span>
            <p style="font-size:.75rem;color:#aaa;margin:10px 0 0;">Valid for 15 minutes</p>
          </div>

          <p style="font-size:.82rem;color:#888;margin:0;line-height:1.6;">
            If you did not request this, please ignore this email. Your password will remain unchanged.
          </p>
        </td>
      </tr>

      {{-- Footer --}}
      <tr>
        <td style="background:#f8f9fa;padding:16px 40px;border-top:1px solid #eee;text-align:center;">
          <p style="font-size:.75rem;color:#aaa;margin:0;">
            &copy; {{ date('Y') }} FaithTrip &nbsp;|&nbsp; Abedin Tower (Level-8), 35 Kemal Ataturk Ave, Banani, Dhaka
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
