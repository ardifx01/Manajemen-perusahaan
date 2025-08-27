<div align="center">

# Manajemen Perusahaan

Aplikasi manajemen operasional perusahaan berbasis Laravel (WIP). Fokus pada alur PO, Surat Jalan, tagihan, dan master data dengan UI/UX yang rapih, cepat, dan konsisten.

![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777bb4?logo=php)
![Vite](https://img.shields.io/badge/Vite-Frontend-646cff?logo=vite)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3-38b2ac?logo=tailwind-css)

</div>

---

## Sorotan Pembaruan UI/UX Terbaru
- **Heading terpusat dan konsisten**: judul-judul utama seperti `Laporan Pendapatan`/`Pengeluaran` ditata ulang agar rapi dan fokus.
- **Border tabel distandarisasi**: ketebalan/warna garis seragam (mode terang/gelap).
- **Aksi ringkas**: tombol seperti “Tambah Pengeluaran” disederhanakan menjadi ikon + (dengan aksesibilitas `aria-label`).
- **Subjudul tak perlu dihilangkan**: tampilan lebih bersih tanpa “(Tabel Gabungan)”.
- **Sidebar tree untuk Manajemen Pengguna**: sekarang dapat di-expand/collapse seperti `Karyawan` dan `Input PO`.
- **Ikon sidebar diperbesar**: keterbacaan lebih baik pada `Manajemen Pengguna`.

> Catatan: Mode gelap/terang dapat diganti dari header (toggle theme).

## Fitur Saat Ini
- **Purchase Order (PO)**: input, daftar, dan pencarian PO.
- **Surat Jalan / Data PO**: kelola data PO dan ekspor Surat Jalan (Excel).
- **Master Data**: Customer, Produk, Kendaraan, Pengirim.
- **Karyawan**: data karyawan dan modul gaji (submenu di sidebar).
- **Manajemen Pengguna**: tambah user, daftar user (khusus admin), dan Pengaturan.
- **Jatuh Tempo**: monitoring tagihan jatuh tempo.
- **Autentikasi**: login dan proteksi halaman.

## Tech Stack
- Backend: Laravel (PHP)
- Frontend: Blade, TailwindCSS, Vite
- Database: MySQL/MariaDB
- Tools: Composer, Node.js/NPM

---

## Instalasi Cepat
```bash
# 1) Install dependencies PHP & JS
composer install
npm install

# 2) Salin environment & generate key
cp .env.example .env  # di Windows bisa salin manual
php artisan key:generate

# 3) Konfigurasi database di .env, lalu migrate (opsional: seed)
php artisan migrate
# php artisan db:seed

# 4) Jalankan asset frontend
npm run dev   # pengembangan
# npm run build  # produksi

# 5) Jalankan server
php artisan serve
```

### Contoh .env minimal
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

## Skrip NPM
- `npm run dev` — Vite dev server (hot reload)
- `npm run build` — build produksi
- `npm run preview` — preview hasil build

## Roadmap Singkat
- [ ] Laporan PO & Invoice (ekspor Excel/PDF)
- [ ] Role & Permission
- [ ] Notifikasi
- [ ] Pengujian otomatis (Pest/PHPUnit)

## Kontribusi
Pull Request dipersilakan. Buka issue untuk diskusi bug/fitur. Gunakan gaya commit konvensional bila memungkinkan.

## Lisensi
MIT License
