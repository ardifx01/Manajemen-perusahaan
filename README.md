<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
    </p>

## Manajemen Perusahaan

Aplikasi manajemen operasional perusahaan berbasis Laravel. Proyek ini masih aktif dikembangkan (WIP) dan akan terus diperbarui.

### Fitur Utama (progress)
- **Purchase Order (PO)**: daftar PO, tambah/ubah, pencarian.
- **Surat Jalan**: generate dan kelola surat jalan.
- **Invoice & Pembayaran**: pembuatan invoice, relasi ke PO dan pelanggan.
- **Master Data**: pelanggan, karyawan, produk, kendaraan, pengirim.
- **Autentikasi**: login, proteksi halaman.
- **Laporan Keuangan – Pendapatan**: ringkasan pendapatan per bulan (bruto, PPN, net), detail per customer, dan modal rincian transaksi.
- **Laporan Keuangan – Pengeluaran**: rekap gaji karyawan dan pengeluaran lain per bulan, termasuk modal rincian.

### Tech Stack
- Backend: Laravel (PHP)
- Frontend: Blade, TailwindCSS, Vite, Alpine.js
- Database: MySQL/MariaDB
- Tools: Composer, Node.js/NPM

## Pratinjau

![Tampilan Dashboard](docs/screenshot/dashboard.jpg)

### Cara Mengakses Cepat
- Dashboard utama: `GET /dashboard`
- Laporan Pendapatan: route `finance.income` (contoh: `/finance/income?inc_month=8&inc_year=2025`)
- Detail Pendapatan (JSON): route `finance.income.detail`
- Laporan Pengeluaran: route `finance.expense` (contoh: `/finance/expense?month=8&year=2025`)
- Detail Pengeluaran (JSON): route `finance.expense.detail`
- Surat Jalan: `GET /suratjalan`
- Purchase Order: `GET /po`

---

## Perubahan UI Terbaru (Highlight)
- **Sidebar terstruktur & rapi**: menu “Data PO” kini berada di dalam tree “Purchase Order” bersama “Input PO”.
- **Header/Footer Sidebar sticky**: hanya area menu yang scroll. Implementasi: `overflow-hidden` pada `<aside id="sidebar">`, `overflow-y-auto` pada `<nav>`.
- **Footer profil → Pengaturan**: klik area profil di footer sidebar langsung membuka halaman Pengaturan. Item “Pengaturan” pada menu dihapus agar tidak duplikat.
- **Animasi masuk halaman Pengaturan**: konten melakukan fade-in + slide-up + scale halus saat route `settings` aktif (Alpine.js transition).
- **Ikon panah konsisten**: semua panah toggle tree pakai `w-4 h-4 min-w-[1rem] min-h-[1rem] shrink-0 flex-none` sehingga tidak menyusut dan tetap align.

Lokasi utama perubahan: `resources/views/layouts/app.blade.php` (bagian `<aside>`/sidebar dan `<main>`/konten).

## Arsitektur & UX
- **Layout**: `resources/views/layouts/app.blade.php` menjadi layout utama.
- **Sidebar**: dibagi 3 bagian (header, area menu, footer). Sticky header/footer untuk akses cepat.
- **Navigasi PO**: tree “Purchase Order” otomatis terbuka saat berada di route `po.*` maupun `suratjalan.*`.
- **Responsif**: tombol hamburger dan overlay untuk mobile, floating handle untuk show/hide sidebar di desktop.

## Persiapan & Instalasi

### Prasyarat
- PHP 8.2+
- Composer 2+
- Node.js 18+ dan NPM
- MySQL/MariaDB

### Langkah Cepat
```bash
# 1) Install dependencies PHP & JS
composer install
npm install

# 2) Salin environment & generate key
cp .env.example .env  # atau duplikasi manual di Windows
php artisan key:generate

# 3) Konfigurasi database di .env, lalu migrate (opsional: seed)
php artisan migrate
# php artisan db:seed

# 4) Build asset frontend
npm run dev   # untuk pengembangan
# npm run build  # untuk produksi

# 5) Jalankan server
php artisan serve
```

### Variabel Lingkungan (contoh)
Pastikan `.env` terisi minimal:

```ini
APP_NAME="Manajemen Perusahaan"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cam_db
DB_USERNAME=root
DB_PASSWORD=
```

## Skrip NPM yang Berguna
- `npm run dev` — Vite dev server (hot reload)
- `npm run build` — build produksi
- `npm run preview` — preview hasil build

## Routing & Navigasi Penting
- `route('po.index')` — Input/daftar PO
- `route('suratjalan.index')` — Data PO/Surat Jalan
- `route('finance.income')` — Laporan Pendapatan (param: `inc_month`, `inc_year`)
- `route('finance.expense')` — Laporan Pengeluaran (param: `month`, `year`)
- `route('settings')` — Pengaturan (akses cepat via klik profil footer sidebar)

## Konvensi Commit
Mengikuti gaya commit konvensional, contoh:
- `feat(ui): ...` — fitur UI
- `fix(api): ...` — perbaikan backend/API
- `chore(deps): ...` — pembaruan dependency

## Troubleshooting
- **Sidebar tidak sticky**: pastikan `<aside id="sidebar">` memiliki `overflow-hidden` dan `<nav>` memiliki `overflow-y-auto`.
- **Ikon panah mengecil**: pastikan kelas SVG panah menyertakan `min-w-[1rem] min-h-[1rem] shrink-0 flex-none`.
- **Klik profil tidak membuka Pengaturan**: pastikan footer profil dibungkus `<a href="{{ route('settings') }}">` dan tidak terhalang elemen lain.
- **Animasi Pengaturan tidak muncul**: cek kondisional Blade `request()->routeIs('settings')` pada blok `<main>`.
- **Build Vite gagal**: hapus cache `.vite` dan `node_modules`, lalu `npm install` ulang.

## Roadmap Singkat
- [ ] Laporan PO & Invoice (export Excel/PDF)
- [ ] Role & Permission
- [ ] Notifikasi
- [ ] Pengujian otomatis (Pest/PHPUnit)

## Catatan Pengembangan
- [WIP] Menambahkan field `no_invoice` pada modul PO
- [UI] Struktur ulang sidebar, akses cepat Pengaturan, dan animasi halaman Pengaturan (selesai)

## Kontribusi
Pull Request sangat dipersilakan. Buka issue untuk diskusi bug/fitur. Ikuti gaya commit konvensional jika memungkinkan.

## Lisensi
Proyek ini dirilis di bawah lisensi MIT.
