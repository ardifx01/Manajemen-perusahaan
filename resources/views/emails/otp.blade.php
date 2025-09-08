<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-code {
            background-color: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p>Sistem Manajemen Perusahaan</p>
        </div>
        
        <div class="content">
            <h2>Kode OTP Anda</h2>
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>
            
            <div class="otp-code">
                <div class="otp-number">{{ $otpCode }}</div>
                <p style="margin: 10px 0 0 0; color: #6c757d;">Masukkan kode ini di halaman verifikasi</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Kode OTP ini berlaku selama <strong>10 menit</strong></li>
                    <li>Jangan bagikan kode ini kepada siapa pun</li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                </ul>
            </div>
            
            <p>Jika Anda mengalami kesulitan, silakan hubungi administrator sistem.</p>
            
            <p>Terima kasih,<br>
            <strong>Tim Manajemen Perusahaan</strong></p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Sistem Manajemen Perusahaan. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
