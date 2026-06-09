<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #20bdc4 0%, #179aa0 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
        <h1 style="color: white; margin: 0; font-size: 24px;">📧 Pesan Baru dari Form Kontak</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Anda menerima pesan baru dari form kontak <strong>Autopahala</strong>:</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #20bdc4;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280; width: 120px;">Nama:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $senderName }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Email:</td>
                    <td style="padding: 8px 0; color: #111827;">
                        <a href="mailto:{{ $senderEmail }}" style="color: #20bdc4; text-decoration: none;">{{ $senderEmail }}</a>
                    </td>
                </tr>
            </table>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="font-weight: bold; color: #6b7280; margin-bottom: 10px;">Pesan:</p>
            <p style="color: #111827; white-space: pre-wrap; margin: 0;">{{ $messageContent }}</p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 14px;">
            <p style="margin: 5px 0;">Email ini dikirim otomatis dari form kontak Autopahala</p>
            <p style="margin: 5px 0;">Balas langsung ke email pengirim untuk merespons</p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>© {{ date('Y') }} Autopahala. All rights reserved.</p>
    </div>
</body>
</html>
