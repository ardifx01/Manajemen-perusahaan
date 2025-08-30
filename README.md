<div align="center">

# 🏢 Manajemen Perusahaan

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

### ✨ Aplikasi Manajemen Operasional Perusahaan Modern

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-🚧%20Work%20in%20Progress-yellow?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/Version-1.0.0-blue?style=flat-square" alt="Version">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

</div>

## 📋 Daftar Isi

- [🎯 Fitur Utama](#-fitur-utama)
- [🖼️ Preview](#️-preview)
- [🛠️ Tech Stack](#️-tech-stack)
- [⚡ Quick Start](#-quick-start)
- [🎨 UI/UX Highlights](#-uiux-highlights)
- [📚 API Routes](#-api-routes)
- [🔧 Troubleshooting](#-troubleshooting)
- [🗺️ Roadmap](#️-roadmap)
- [🤝 Contributing](#-contributing)

## 🎯 Fitur Utama

<table>
<tr>
<td width="50%">

### 📦 **Purchase Order Management**
- ✅ Daftar PO dengan pencarian canggih
- ✅ Form tambah/edit PO yang intuitif
- ✅ Tracking status PO real-time

### 📄 **Surat Jalan Digital**
- ✅ Generate surat jalan otomatis
- ✅ Template yang dapat dikustomisasi
- ✅ Export ke PDF

### 💰 **Financial Management**
- ✅ Laporan pendapatan bulanan
- ✅ Tracking pengeluaran
- ✅ Dashboard keuangan interaktif

</td>
<td width="50%">

### 👥 **Master Data Management**
- ✅ Database pelanggan terintegrasi
- ✅ Manajemen karyawan
- ✅ Katalog produk & inventory
- ✅ Data kendaraan & pengirim

### 🔐 **Security & Authentication**
- ✅ Login system yang aman
- ✅ Role-based access control
- ✅ Session management

### 📊 **Reporting & Analytics**
- ✅ Dashboard dengan visualisasi data
- ✅ Export laporan (Excel/PDF)
- ✅ Real-time analytics

</td>
</tr>
</table>

## 🖼️ Preview

<div align="center">
  <img src="docs/screenshot/dashboard.jpg" alt="Dashboard Preview" width="800" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
  
  *Dashboard dengan UI modern dan responsif*
</div>

## 🛠️ Tech Stack

<div align="center">

| **Backend** | **Frontend** | **Database** | **Tools** |
|-------------|--------------|--------------|-----------|
| ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white) | ![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat&logo=laravel&logoColor=white) | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) | ![Composer](https://img.shields.io/badge/Composer-885630?style=flat&logo=composer&logoColor=white) |
| ![PHP](https://img.shields.io/badge/PHP%208.2+-777BB4?style=flat&logo=php&logoColor=white) | ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white) | ![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=flat&logo=mariadb&logoColor=white) | ![Node.js](https://img.shields.io/badge/Node.js-339933?style=flat&logo=node.js&logoColor=white) |
| | ![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC34A?style=flat&logo=alpine.js&logoColor=white) | | ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat&logo=vite&logoColor=white) |

</div>

## ⚡ Quick Start

### 📋 Prerequisites

```bash
✅ PHP 8.2+
✅ Composer 2+
✅ Node.js 18+ & NPM
✅ MySQL/MariaDB
```

### 🚀 Installation

```bash
# 1️⃣ Clone repository
git clone https://github.com/DillanINF/Manajemen-perusahaan.git
cd Manajemen-perusahaan

# 2️⃣ Install dependencies
composer install
npm install

# 3️⃣ Environment setup
cp .env.example .env
php artisan key:generate

# 4️⃣ Database setup
php artisan migrate
php artisan db:seed  # Optional

# 5️⃣ Build assets
npm run dev  # Development
# npm run build  # Production

# 6️⃣ Start server
php artisan serve
```

### ⚙️ Environment Configuration

```ini
APP_NAME="Manajemen Perusahaan"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cam_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 🎨 UI/UX Highlights

<div align="center">

### ✨ **Modern Interface Design**

</div>

| Feature | Description |
|---------|-------------|
| 🎯 **Smart Sidebar** | Sticky header/footer dengan smooth scrolling |
| 🎭 **Smooth Animations** | Alpine.js transitions untuk pengalaman yang halus |
| 📱 **Responsive Design** | Mobile-first approach dengan hamburger menu |
| 🎨 **Consistent Icons** | Icon system yang seragam dan scalable |
| ⚡ **Quick Access** | One-click access ke pengaturan dari sidebar footer |

## 📚 API Routes

<details>
<summary><b>🔗 Click to expand routes</b></summary>

### 🏠 **Core Routes**
```php
GET  /dashboard              # Main dashboard
GET  /settings              # User settings
```

### 📦 **Purchase Order**
```php
GET  /po                    # PO listing
POST /po                    # Create PO
GET  /po/{id}/edit          # Edit PO form
PUT  /po/{id}               # Update PO
```

### 📄 **Surat Jalan**
```php
GET  /suratjalan            # Surat jalan listing
POST /suratjalan            # Generate surat jalan
```

### 💰 **Financial Reports**
```php
GET  /finance/income        # Income report (?inc_month=8&inc_year=2025)
GET  /finance/income/detail # Income details (JSON)
GET  /finance/expense       # Expense report (?month=8&year=2025)
GET  /finance/expense/detail# Expense details (JSON)
```

</details>

## 🔧 Troubleshooting

<details>
<summary><b>🛠️ Common Issues & Solutions</b></summary>

### 🚨 **Sidebar Issues**
```bash
Problem: Sidebar tidak sticky
Solution: Pastikan <aside id="sidebar"> memiliki overflow-hidden
```

### 🎯 **Icon Problems**
```bash
Problem: Icon panah mengecil
Solution: Gunakan classes: min-w-[1rem] min-h-[1rem] shrink-0 flex-none
```

### ⚙️ **Settings Access**
```bash
Problem: Klik profil tidak membuka pengaturan
Solution: Pastikan footer profil dibungkus <a href="{{ route('settings') }}">
```

### 🎭 **Animation Issues**
```bash
Problem: Animasi pengaturan tidak muncul
Solution: Cek kondisional request()->routeIs('settings')
```

### 📦 **Build Problems**
```bash
Problem: Vite build gagal
Solution: rm -rf .vite node_modules && npm install
```

</details>

## 🗺️ Roadmap

<div align="center">

### 🎯 **Coming Soon**

</div>

```mermaid
gantt
    title Development Roadmap
    dateFormat  YYYY-MM-DD
    section Phase 1
    Reports Export (Excel/PDF)    :2025-01-01, 30d
    section Phase 2
    Role & Permission System      :2025-02-01, 45d
    section Phase 3
    Real-time Notifications       :2025-03-15, 30d
    section Phase 4
    Automated Testing Suite       :2025-04-15, 60d
```

- [ ] 📊 **Advanced Reporting** - Export Excel/PDF dengan template kustom
- [ ] 👥 **Role Management** - Sistem permission yang granular
- [ ] 🔔 **Real-time Notifications** - WebSocket integration
- [ ] 🧪 **Testing Suite** - Comprehensive testing dengan Pest/PHPUnit
- [ ] 📱 **Mobile App** - React Native companion app
- [ ] 🤖 **API Integration** - RESTful API untuk third-party integration

## 🤝 Contributing

<div align="center">

### 💝 **We Welcome Contributors!**

</div>

```bash
# 1️⃣ Fork the repository
# 2️⃣ Create feature branch
git checkout -b feature/amazing-feature

# 3️⃣ Commit changes (follow conventional commits)
git commit -m "feat(ui): add amazing feature"

# 4️⃣ Push to branch
git push origin feature/amazing-feature

# 5️⃣ Open Pull Request
```

### 📝 **Commit Convention**
- `feat(scope):` ✨ New features
- `fix(scope):` 🐛 Bug fixes  
- `docs(scope):` 📚 Documentation
- `style(scope):` 💄 Code style
- `refactor(scope):` ♻️ Code refactoring
- `chore(scope):` 🔧 Maintenance

---

<div align="center">

### 📄 **License**

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

### 💖 **Made with Love**

Built with ❤️ by [DillanINF](https://github.com/DillanINF)

<p align="center">
  <img src="https://img.shields.io/badge/⭐-Star%20this%20repo-yellow?style=for-the-badge" alt="Star this repo">
</p>

</div>
