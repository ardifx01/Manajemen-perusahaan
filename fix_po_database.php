<?php
// Script perbaikan database untuk tabel PO, Jatuh Tempo, dan Surat Jalan
try {
    $dbPath = __DIR__ . '/database/database.sqlite';
    
    if (!file_exists($dbPath)) {
        echo "❌ File database.sqlite tidak ditemukan. Membuat file baru...\n";
        touch($dbPath);
    }
    
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Koneksi ke database SQLite berhasil\n";
    echo "📍 Path database: $dbPath\n\n";
    
    // Cek dan buat tabel pos
    echo "=== MEMPERBAIKI TABEL POS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='pos'");
    if (!$stmt->fetch()) {
        echo "🔧 Membuat tabel 'pos'...\n";
        $pdo->exec("
            CREATE TABLE pos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tanggal_po DATE NOT NULL,
                customer_id INTEGER,
                customer VARCHAR(255),
                pengirim VARCHAR(255),
                no_surat_jalan VARCHAR(255),
                no_po VARCHAR(255),
                no_invoice VARCHAR(255),
                no_polisi VARCHAR(255),
                kendaraan VARCHAR(255),
                qty INTEGER DEFAULT 0,
                qty_jenis VARCHAR(50) DEFAULT 'PCS',
                produk_id INTEGER,
                harga DECIMAL(15,2) DEFAULT 0,
                total DECIMAL(15,2) DEFAULT 0,
                alamat_1 TEXT,
                alamat_2 TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        echo "✅ Tabel 'pos' berhasil dibuat\n";
    } else {
        echo "✅ Tabel 'pos' sudah ada\n";
    }
    
    // Cek dan buat tabel po_items
    echo "\n=== MEMPERBAIKI TABEL PO_ITEMS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='po_items'");
    if (!$stmt->fetch()) {
        echo "🔧 Membuat tabel 'po_items'...\n";
        $pdo->exec("
            CREATE TABLE po_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                po_id INTEGER NOT NULL,
                produk_id INTEGER NOT NULL,
                qty INTEGER NOT NULL DEFAULT 0,
                qty_jenis VARCHAR(50) DEFAULT 'PCS',
                harga DECIMAL(15,2) DEFAULT 0,
                total DECIMAL(15,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (po_id) REFERENCES pos(id) ON DELETE CASCADE
            )
        ");
        echo "✅ Tabel 'po_items' berhasil dibuat\n";
    } else {
        echo "✅ Tabel 'po_items' sudah ada\n";
    }
    
    // Cek dan buat tabel jatuh_tempos
    echo "\n=== MEMPERBAIKI TABEL JATUH_TEMPOS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='jatuh_tempos'");
    if (!$stmt->fetch()) {
        echo "🔧 Membuat tabel 'jatuh_tempos'...\n";
        $pdo->exec("
            CREATE TABLE jatuh_tempos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                no_invoice VARCHAR(255) UNIQUE NOT NULL,
                no_po VARCHAR(255),
                customer VARCHAR(255),
                tanggal_invoice DATE,
                tanggal_jatuh_tempo DATE,
                jumlah_tagihan DECIMAL(15,2) DEFAULT 0,
                jumlah_terbayar DECIMAL(15,2) DEFAULT 0,
                sisa_tagihan DECIMAL(15,2) DEFAULT 0,
                status_pembayaran VARCHAR(50) DEFAULT 'Belum Bayar',
                status_approval VARCHAR(50) DEFAULT 'Pending',
                denda DECIMAL(15,2) DEFAULT 0,
                catatan TEXT,
                reminder_sent BOOLEAN DEFAULT 0,
                last_reminder_date TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        echo "✅ Tabel 'jatuh_tempos' berhasil dibuat\n";
    } else {
        echo "✅ Tabel 'jatuh_tempos' sudah ada\n";
    }
    
    // Cek tabel pendukung lainnya
    $requiredTables = [
        'users' => "
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at TIMESTAMP,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'customers' => "
            CREATE TABLE customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255),
                phone VARCHAR(50),
                address_1 TEXT,
                address_2 TEXT,
                payment_terms_days INTEGER DEFAULT 30,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'produks' => "
            CREATE TABLE produks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_produk VARCHAR(255) NOT NULL,
                harga_pcs DECIMAL(15,2) DEFAULT 0,
                harga_set DECIMAL(15,2) DEFAULT 0,
                satuan VARCHAR(50) DEFAULT 'PCS',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'kendaraans' => "
            CREATE TABLE kendaraans (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama_kendaraan VARCHAR(255) NOT NULL,
                no_polisi VARCHAR(50),
                jenis VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'pengirims' => "
            CREATE TABLE pengirims (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama VARCHAR(255) NOT NULL,
                alamat TEXT,
                telepon VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        "
    ];
    
    echo "\n=== MEMERIKSA TABEL PENDUKUNG ===\n";
    foreach ($requiredTables as $tableName => $createSql) {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'");
        if (!$stmt->fetch()) {
            echo "🔧 Membuat tabel '$tableName'...\n";
            $pdo->exec($createSql);
            echo "✅ Tabel '$tableName' berhasil dibuat\n";
        } else {
            echo "✅ Tabel '$tableName' sudah ada\n";
        }
    }
    
    // Insert sample data jika tabel kosong
    echo "\n=== MENAMBAHKAN DATA SAMPLE ===\n";
    
    // Sample users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    if ($stmt->fetch()['count'] == 0) {
        echo "🔧 Menambahkan user sample...\n";
        $pdo->exec("
            INSERT INTO users (name, email, password) VALUES 
            ('Admin', 'admin@gmail.com', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
            ('Perle', 'perle@gmail.com', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
        ");
        echo "✅ User sample ditambahkan (password: password123)\n";
    }
    
    // Sample customers
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
    if ($stmt->fetch()['count'] == 0) {
        echo "🔧 Menambahkan customer sample...\n";
        $pdo->exec("
            INSERT INTO customers (name, email, phone, address_1, payment_terms_days) VALUES 
            ('PT. Contoh Customer', 'customer@example.com', '081234567890', 'Jl. Contoh No. 123', 30),
            ('CV. Sample Corp', 'sample@corp.com', '087654321098', 'Jl. Sample Street 456', 45)
        ");
        echo "✅ Customer sample ditambahkan\n";
    }
    
    // Sample products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM produks");
    if ($stmt->fetch()['count'] == 0) {
        echo "🔧 Menambahkan produk sample...\n";
        $pdo->exec("
            INSERT INTO produks (nama_produk, harga_pcs, harga_set, satuan) VALUES 
            ('Produk A', 10000, 95000, 'PCS'),
            ('Produk B', 15000, 140000, 'PCS'),
            ('Produk C', 25000, 230000, 'SET')
        ");
        echo "✅ Produk sample ditambahkan\n";
    }
    
    // Sample kendaraan
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM kendaraans");
    if ($stmt->fetch()['count'] == 0) {
        echo "🔧 Menambahkan kendaraan sample...\n";
        $pdo->exec("
            INSERT INTO kendaraans (nama_kendaraan, no_polisi, jenis) VALUES 
            ('Truck Besar', 'B 1234 CD', 'Truck'),
            ('Pickup', 'B 5678 EF', 'Pickup'),
            ('Van', 'B 9012 GH', 'Van')
        ");
        echo "✅ Kendaraan sample ditambahkan\n";
    }
    
    echo "\n🎉 PERBAIKAN DATABASE SELESAI!\n";
    echo "✅ Semua tabel PO, Jatuh Tempo, dan pendukung sudah siap\n";
    echo "✅ Data sample sudah ditambahkan\n";
    echo "🔗 Silakan coba input PO lagi dari aplikasi web\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
}
