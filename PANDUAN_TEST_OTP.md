# Panduan Testing Fitur OTP Forgot Password

## Status Saat Ini
✅ Migration otp_codes table sudah dibuat
✅ Controller OTP sudah dibuat dengan debug mode
✅ Email template sudah diperbaiki
✅ Routes OTP sudah ditambahkan
✅ Server Laravel berjalan di http://127.0.0.1:8000

## Langkah Testing

### 1. Akses Halaman Forgot Password
- Buka browser dan kunjungi: `http://127.0.0.1:8000/forgot-password`
- Atau klik tombol "Forgot Password" dari halaman login

### 2. Test dengan Mode Debug
Saat ini aplikasi dikonfigurasi dengan mode testing yang akan menampilkan OTP di browser.

**Input email yang sudah terdaftar di sistem:**
- Masukkan email user yang ada di database
- Klik "Kirim Kode OTP"

**Yang akan terjadi:**
- Sistem akan generate OTP 6 digit
- Karena menggunakan mode testing, OTP akan ditampilkan di pesan success
- Format pesan: "Kode OTP telah dikirim ke email Anda. [MODE TESTING - OTP: 123456]"

### 3. Verifikasi OTP
- Setelah melihat OTP di pesan, masukkan kode tersebut
- Sistem akan memverifikasi OTP
- Jika valid, akan redirect ke halaman reset password

### 4. Reset Password
- Masukkan password baru (minimal 8 karakter)
- Konfirmasi password
- Klik "Reset Password"

## Untuk Email Sungguhan

### Opsi 1: Menggunakan Mailtrap (Recommended untuk Testing)
1. Daftar gratis di https://mailtrap.io
2. Buat inbox baru
3. Copy kredensial SMTP
4. Edit file `config_email_otp.php`:
   ```php
   $emailConfig = [
       'MAIL_MAILER' => 'smtp',
       'MAIL_HOST' => 'sandbox.smtp.mailtrap.io',
       'MAIL_PORT' => '2525',
       'MAIL_USERNAME' => 'your_username_here',
       'MAIL_PASSWORD' => 'your_password_here',
       'MAIL_ENCRYPTION' => 'tls',
       'MAIL_FROM_ADDRESS' => 'noreply@manajemen-perusahaan.com',
       'MAIL_FROM_NAME' => 'Manajemen Perusahaan'
   ];
   ```
5. Jalankan: `php config_email_otp.php`

### Opsi 2: Menggunakan Gmail SMTP
1. Enable 2-Factor Authentication di Gmail
2. Generate App Password (bukan password biasa)
3. Edit file `config_email_otp.php`:
   ```php
   $emailConfig = [
       'MAIL_MAILER' => 'smtp',
       'MAIL_HOST' => 'smtp.gmail.com',
       'MAIL_PORT' => '587',
       'MAIL_USERNAME' => 'your_email@gmail.com',
       'MAIL_PASSWORD' => 'your_app_password',
       'MAIL_ENCRYPTION' => 'tls',
       'MAIL_FROM_ADDRESS' => 'your_email@gmail.com',
       'MAIL_FROM_NAME' => 'Manajemen Perusahaan'
   ];
   ```
4. Jalankan: `php config_email_otp.php`

## Troubleshooting

### Jika OTP tidak muncul di pesan:
1. Cek apakah email yang dimasukkan terdaftar di database users
2. Cek log error di `storage/logs/laravel.log`
3. Pastikan migration sudah berjalan: `php artisan migrate`

### Jika error "Class not found":
1. Jalankan: `composer dump-autoload`
2. Clear cache: `php artisan config:clear`

### Jika email tidak terkirim (mode SMTP):
1. Cek kredensial email di file .env
2. Pastikan firewall tidak memblokir port SMTP
3. Test koneksi SMTP dengan: `php artisan tinker` lalu `Mail::raw('test', function($m) { $m->to('test@example.com'); });`

## File Penting
- Controller: `app/Http/Controllers/Auth/OtpController.php`
- Model: `app/Models/OtpCode.php`
- Email Template: `resources/views/emails/otp.blade.php`
- Routes: `routes/auth.php`
- Migration: `database/migrations/*_create_otp_codes_table.php`
