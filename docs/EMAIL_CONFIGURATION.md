# Konfigurasi Email untuk Fitur OTP

## Masalah: Kode OTP Tidak Muncul di Email

### Penyebab Utama
1. **Mail Driver 'log'**: Aplikasi menggunakan mail driver 'log' yang hanya menyimpan email ke file log, tidak mengirim email sungguhan.
2. **Konfigurasi .env**: Setting email di file .env menggunakan driver log untuk development.

### Solusi

#### 1. Untuk Testing/Development (Menggunakan Mailtrap atau MailHog)

Ubah konfigurasi di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@manajemen-perusahaan.com"
MAIL_FROM_NAME="Manajemen Perusahaan"
```

#### 2. Untuk Production (Menggunakan Gmail SMTP)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="Manajemen Perusahaan"
```

#### 3. Alternatif: Menggunakan Driver 'array' untuk Testing

Jika ingin testing tanpa email sungguhan:

```env
MAIL_MAILER=array
```

Kemudian di controller, tambahkan debug untuk melihat email:

```php
// Di OtpController.php, method sendOtp()
if (config('mail.default') === 'array') {
    // Untuk testing, tampilkan OTP di response
    return redirect()->route('otp.verify.form')
        ->with('status', 'Kode OTP: ' . $otpRecord->otp_code . ' (Mode Testing)');
}
```

### Cara Melihat Email di Log (Jika Menggunakan Driver Log)

Jika tetap menggunakan driver 'log', email akan tersimpan di:
```
storage/logs/laravel.log
```

Cari baris yang mengandung "Message-ID" untuk melihat isi email.

### Rekomendasi
1. **Development**: Gunakan Mailtrap.io (gratis)
2. **Production**: Gunakan Gmail SMTP atau layanan email profesional
3. **Testing**: Gunakan driver 'array' dengan debug output

### Setup Mailtrap (Recommended untuk Development)
1. Daftar di https://mailtrap.io
2. Buat inbox baru
3. Copy kredensial SMTP
4. Update file .env dengan kredensial tersebut
5. Test kirim email OTP
