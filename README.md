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
    - **Master Data**: pelanggan, karyawan, produk.
    - **Autentikasi**: login, proteksi halaman.
    
    ### Tech Stack
    - Backend: Laravel (PHP)
    - Frontend: Blade, TailwindCSS, Vite
    - Database: MySQL/MariaDB
    - Tools: Composer, Node.js/NPM
    
    ## Pratinjau
    
    ![Tampilan Dashboard](docs/screenshot/dashboard.jpg)
    
    ---
    
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
    
    ---
    
    ## Skrip NPM yang Berguna
    - `npm run dev` — Vite dev server (hot reload)
    - `npm run build` — build produksi
    - `npm run preview` — preview hasil build
    
    ## Roadmap Singkat
    - [ ] Laporan PO & Invoice (export Excel/PDF)
    - [ ] Role & Permission
    - [ ] Notifikasi
    - [ ] Pengujian otomatis (Pest/PHPUnit)
    
    ## Kontribusi
    Pull Request sangat dipersilakan. Buka issue untuk diskusi bug/fitur. Ikuti gaya commit konvensional jika memungkinkan.
    
    ## Lisensi
    Proyek ini dirilis di bawah lisensi MIT.

- [WIP] Menambahkan field no_invoice pada modul PO
