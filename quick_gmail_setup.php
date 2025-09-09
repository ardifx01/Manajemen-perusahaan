<?php

/**
 * Quick Gmail Setup for OTP
 * Update .env dengan App Password yang sudah didapat
 */

echo "📧 QUICK GMAIL SETUP FOR OTP\n";
echo "============================\n\n";

// App Password yang diberikan user
$appPassword = "euaxacukexkffyek"; // 16 characters

// Email Gmail yang sudah diberikan
$gmailEmail = "dilaninf6@gmail.com";
echo "Using Gmail: {$gmailEmail}\n";
echo "\n🔧 Updating .env file...\n";

// Check if .env file exists
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "❌ File .env tidak ditemukan!\n";
    exit(1);
}

// Read current .env content
$envContent = file_get_contents($envFile);

// Update mail configuration
$patterns = [
    '/^MAIL_MAILER=.*$/m' => 'MAIL_MAILER=smtp',
    '/^MAIL_HOST=.*$/m' => 'MAIL_HOST=smtp.gmail.com',
    '/^MAIL_PORT=.*$/m' => 'MAIL_PORT=587',
    '/^MAIL_USERNAME=.*$/m' => "MAIL_USERNAME={$gmailEmail}",
    '/^MAIL_PASSWORD=.*$/m' => "MAIL_PASSWORD={$appPassword}",
    '/^MAIL_ENCRYPTION=.*$/m' => 'MAIL_ENCRYPTION=tls',
    '/^MAIL_FROM_ADDRESS=.*$/m' => "MAIL_FROM_ADDRESS=\"{$gmailEmail}\"",
    '/^MAIL_FROM_NAME=.*$/m' => 'MAIL_FROM_NAME="Manajemen Perusahaan"'
];

foreach ($patterns as $pattern => $replacement) {
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
    } else {
        // Add if not exists
        $envContent .= "\n" . $replacement;
    }
}

// Write updated .env
file_put_contents($envFile, $envContent);

echo "✅ File .env berhasil diupdate!\n\n";

// Clear config cache
echo "🔄 Clearing configuration cache...\n";
if (function_exists('exec')) {
    exec('php artisan config:clear 2>&1', $output, $return_var);
    if ($return_var === 0) {
        echo "✅ Configuration cache cleared\n";
    }
}

echo "\n📧 KONFIGURASI GMAIL SELESAI:\n";
echo "============================\n";
echo "Email: {$gmailEmail}\n";
echo "SMTP Host: smtp.gmail.com\n";
echo "Port: 587\n";
echo "Encryption: TLS\n";
echo "App Password: euax****fyek\n\n";

echo "🧪 CARA TEST OTP:\n";
echo "=================\n";
echo "1. Buka website aplikasi\n";
echo "2. Klik 'Forgot Password'\n";
echo "3. Masukkan email yang terdaftar di sistem\n";
echo "4. Klik 'Kirim Kode OTP'\n";
echo "5. Cek inbox Gmail Anda dalam 1-2 menit\n\n";

echo "✅ Gmail SMTP siap digunakan!\n";
echo "OTP sekarang akan dikirim ke email sungguhan.\n";

?>
