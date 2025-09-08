# Fitur Forgot Password dengan OTP

## Deskripsi
Fitur ini memungkinkan pengguna untuk mereset password menggunakan kode OTP (One-Time Password) yang dikirim melalui email.

## Alur Kerja
1. User mengakses halaman "Forgot Password"
2. User memasukkan email yang terdaftar
3. Sistem mengirim kode OTP 6 digit ke email user
4. User memasukkan kode OTP untuk verifikasi
5. Setelah OTP terverifikasi, user dapat memasukkan password baru
6. Password berhasil direset

## File yang Dibuat/Dimodifikasi

### 1. Migration
- `database/migrations/2025_09_08_013012_create_otp_codes_table.php`
  - Tabel untuk menyimpan kode OTP
  - Fields: id, email, otp_code, expires_at, is_used, timestamps

### 2. Model
- `app/Models/OtpCode.php`
  - Model untuk mengelola OTP codes
  - Method: generateOtp(), verifyOtp(), isExpired()

### 3. Controller
- `app/Http/Controllers/Auth/OtpController.php`
  - Handle semua logic OTP
  - Methods: sendOtp(), verifyOtp(), showOtpForm(), showResetForm(), resetPassword()

### 4. Mail Template
- `app/Mail/OtpMail.php` - Mail class untuk mengirim OTP
- `resources/views/emails/otp.blade.php` - Template email OTP yang responsive

### 5. Views
- `resources/views/auth/forgot-password.blade.php` - Form input email (dimodifikasi)
- `resources/views/auth/verify-otp.blade.php` - Form input OTP dengan countdown timer
- `resources/views/auth/reset-password-otp.blade.php` - Form reset password baru

### 6. Routes
- `routes/auth.php` - Ditambahkan routes untuk OTP functionality

## Routes yang Ditambahkan
```php
Route::post('send-otp', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('verify-otp', [OtpController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('reset-password-form', [OtpController::class, 'showResetForm'])->name('password.reset.form');
Route::post('reset-password-otp', [OtpController::class, 'resetPassword'])->name('password.reset.otp');
```

## Fitur Keamanan
- OTP berlaku selama 10 menit
- OTP hanya dapat digunakan sekali
- Session management untuk mencegah akses langsung ke halaman reset
- Validasi email harus terdaftar di sistem
- Password baru minimal 8 karakter dengan konfirmasi

## Fitur UI/UX
- Countdown timer untuk menunjukkan sisa waktu OTP
- Input OTP dengan format yang user-friendly (6 digit)
- Validasi real-time untuk password confirmation
- Pesan error dan success yang informatif
- Design responsive dengan gradient background

## Cara Penggunaan
1. Jalankan migration: `php artisan migrate`
2. Konfigurasi email di file `.env`
3. Akses `/forgot-password` untuk memulai proses reset
4. Ikuti alur yang telah disediakan

## Konfigurasi Email
Untuk testing, gunakan mail driver 'log' di `.env`:
```
MAIL_MAILER=log
```

Untuk production, konfigurasi SMTP sesuai provider email yang digunakan.

## Catatan Teknis
- Menggunakan session untuk menyimpan state OTP verification
- Email template menggunakan inline CSS untuk kompatibilitas
- JavaScript untuk countdown timer dan validasi input
- Menggunakan Carbon untuk handling waktu expire OTP
