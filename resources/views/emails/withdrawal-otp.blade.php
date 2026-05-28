<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 40px; border: 1px solid #e2e8f0;">
        <h2 style="margin: 0 0 8px; font-size: 20px; color: #1e293b;">Kode OTP Penarikan Dana</h2>
        <p style="margin: 0 0 24px; font-size: 14px; color: #64748b;">Halo {{ $userName }},</p>

        <p style="font-size: 14px; color: #475569; margin-bottom: 24px;">
            Anda mengajukan penarikan dana sebesar <strong>{{ $amount }}</strong>. Gunakan kode OTP berikut untuk mengkonfirmasi:
        </p>

        <div style="background: #f1f5f9; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 24px;">
            <span style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #1e293b;">{{ $otp->otp_code }}</span>
        </div>

        <p style="font-size: 13px; color: #94a3b8; margin-bottom: 8px;">
            Kode ini berlaku selama <strong>10 menit</strong>.
        </p>
        <p style="font-size: 13px; color: #94a3b8;">
            Jika Anda tidak mengajukan penarikan ini, abaikan email ini dan segera ubah password akun Anda.
        </p>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
        <p style="font-size: 12px; color: #94a3b8; margin: 0;">Autopahala - Platform Crowdfunding</p>
    </div>
</body>
</html>
