<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your AutoPahala Account</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f8fafc; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 48px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 500px; margin: 0 auto;">

                    {{-- Logo --}}
                    <tr>
                        <td align="center" style="padding-bottom: 28px;">
                            <span style="font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">Auto<span style="color: #4f46e5;">pahala</span></span>
                        </td>
                    </tr>

                    {{-- Main Card --}}
                    <tr>
                        <td style="background: #ffffff; border-radius: 16px; padding: 44px 36px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">

                            {{-- Icon Circle --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="width: 64px; height: 64px; background-color: #eef2ff; border-radius: 50%; text-align: center; vertical-align: middle;">
                                                    <span style="font-size: 28px;">✉️</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Heading --}}
                            <h1 style="margin: 0 0 12px; font-size: 22px; font-weight: 700; color: #0f172a; text-align: center; line-height: 1.3;">
                                Verifikasi Alamat Email Anda
                            </h1>

                            {{-- Greeting --}}
                            <p style="margin: 0 0 24px; font-size: 15px; color: #475569; text-align: center; line-height: 1.6;">
                                Halo <strong>{{ $userName }}</strong>,<br>
                                Terima kasih telah bergabung dengan AutoPahala! Silakan verifikasi email Anda untuk mulai menggunakan platform.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 4px 0 32px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="border-radius: 10px; background-color: #4f46e5;">
                                                    <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 14px 36px; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; letter-spacing: 0.02em;">
                                                        Verifikasi Email Saya
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Expiry --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" style="background: #f8fafc; border-radius: 8px; padding: 12px 20px;">
                                            <tr>
                                                <td style="font-size: 12px; color: #64748b; text-align: center;">
                                                    ⏱️ Link berlaku selama <strong>60 menit</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 0 0 20px;">

                            {{-- Fallback --}}
                            <p style="margin: 0 0 8px; font-size: 12px; color: #94a3b8; text-align: center;">
                                Jika tombol tidak berfungsi, salin URL berikut ke browser:
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #4f46e5; text-align: center; word-break: break-all; line-height: 1.6; background: #f8fafc; padding: 10px 12px; border-radius: 6px;">
                                {{ $url }}
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding-top: 28px;">
                            <p style="margin: 0 0 6px; font-size: 12px; color: #94a3b8;">
                                Jika Anda tidak membuat akun di AutoPahala, abaikan email ini.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1;">
                                &copy; {{ date('Y') }} AutoPahala &middot; Platform Crowdfunding Terpercaya
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
