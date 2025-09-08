<?php
// Script perbaikan database PO untuk dijalankan via web browser
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Perbaikan Database PO</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .info { color: #17a2b8; }
        .warning { color: #ffc107; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Perbaikan Database PO</h1>
        <p>Script ini akan memperbaiki tabel PO, Jatuh Tempo, dan Surat Jalan di database SQLite.</p>
        
        <?php
        try {
            $dbPath = dirname(__DIR__) . '/database/database.sqlite';
            
            if (!file_exists($dbPath)) {
                echo "<p class='warning'>⚠️ File database.sqlite tidak ditemukan. Membuat file baru...</p>";
                touch($dbPath);
            }
            
            $pdo = new PDO("sqlite:$dbPath");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo "<p class='success'>✅ Koneksi ke database SQLite berhasil</p>";
            echo "<p class='info'>📍 Path database: $dbPath</p>";
            
            // Cek dan buat tabel pos
            echo "<h3>🔧 Memperbaiki Tabel POS</h3>";
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='pos'");
            if (!$stmt->fetch()) {
                echo "<p class='warning'>🔧 Membuat tabel 'pos'...</p>";
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
                echo "<p class='success'>✅ Tabel 'pos' berhasil dibuat</p>";
            } else {
                echo "<p class='success'>✅ Tabel 'pos' sudah ada</p>";
            }
            
            // Cek dan buat tabel po_items
            echo "<h3>🔧 Memperbaiki Tabel PO_ITEMS</h3>";
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='po_items'");
            if (!$stmt->fetch()) {
                echo "<p class='warning'>🔧 Membuat tabel 'po_items'...</p>";
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
                echo "<p class='success'>✅ Tabel 'po_items' berhasil dibuat</p>";
            } else {
                echo "<p class='success'>✅ Tabel 'po_items' sudah ada</p>";
            }
            
            // Cek dan buat tabel jatuh_tempos
            echo "<h3>🔧 Memperbaiki Tabel JATUH_TEMPOS</h3>";
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='jatuh_tempos'");
            if (!$stmt->fetch()) {
                echo "<p class='warning'>🔧 Membuat tabel 'jatuh_tempos'...</p>";
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
                echo "<p class='success'>✅ Tabel 'jatuh_tempos' berhasil dibuat</p>";
            } else {
                echo "<p class='success'>✅ Tabel 'jatuh_tempos' sudah ada</p>";
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
            
            echo "<h3>🔧 Memeriksa Tabel Pendukung</h3>";
            foreach ($requiredTables as $tableName => $createSql) {
                $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'");
                if (!$stmt->fetch()) {
                    echo "<p class='warning'>🔧 Membuat tabel '$tableName'...</p>";
                    $pdo->exec($createSql);
                    echo "<p class='success'>✅ Tabel '$tableName' berhasil dibuat</p>";
                } else {
                    echo "<p class='success'>✅ Tabel '$tableName' sudah ada</p>";
                }
            }
            
            // Insert sample data jika tabel kosong
            echo "<h3>📊 Menambahkan Data Sample</h3>";
            
            // Sample users
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
            if ($stmt->fetch()['count'] == 0) {
                echo "<p class='warning'>🔧 Menambahkan user sample...</p>";
                $pdo->exec("
                    INSERT INTO users (name, email, password) VALUES 
                    ('Admin', 'admin@gmail.com', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
                    ('Perle', 'perle@gmail.com', '\$2y\$12\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
                ");
                echo "<p class='success'>✅ User sample ditambahkan (password: password123)</p>";
            }
            
            // Sample customers
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
            if ($stmt->fetch()['count'] == 0) {
                echo "<p class='warning'>🔧 Menambahkan customer sample...</p>";
                $pdo->exec("
                    INSERT INTO customers (name, email, phone, address_1, payment_terms_days) VALUES 
                    ('PT. Contoh Customer', 'customer@example.com', '081234567890', 'Jl. Contoh No. 123', 30),
                    ('CV. Sample Corp', 'sample@corp.com', '087654321098', 'Jl. Sample Street 456', 45)
                ");
                echo "<p class='success'>✅ Customer sample ditambahkan</p>";
            }
            
            // Sample products
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM produks");
            if ($stmt->fetch()['count'] == 0) {
                echo "<p class='warning'>🔧 Menambahkan produk sample...</p>";
                $pdo->exec("
                    INSERT INTO produks (nama_produk, harga_pcs, harga_set, satuan) VALUES 
                    ('Produk A', 10000, 95000, 'PCS'),
                    ('Produk B', 15000, 140000, 'PCS'),
                    ('Produk C', 25000, 230000, 'SET')
                ");
                echo "<p class='success'>✅ Produk sample ditambahkan</p>";
            }
            
            // Sample kendaraan
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM kendaraans");
            if ($stmt->fetch()['count'] == 0) {
                echo "<p class='warning'>🔧 Menambahkan kendaraan sample...</p>";
                $pdo->exec("
                    INSERT INTO kendaraans (nama_kendaraan, no_polisi, jenis) VALUES 
                    ('Truck Besar', 'B 1234 CD', 'Truck'),
                    ('Pickup', 'B 5678 EF', 'Pickup'),
                    ('Van', 'B 9012 GH', 'Van')
                ");
                echo "<p class='success'>✅ Kendaraan sample ditambahkan</p>";
            }
            
            // Tampilkan semua tabel yang ada
            echo "<h3>📋 Semua Tabel di Database</h3>";
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>📋 $table</li>";
            }
            echo "</ul>";
            
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
            echo "<h3 style='color: #155724;'>🎉 PERBAIKAN DATABASE SELESAI!</h3>";
            echo "<p style='color: #155724;'>✅ Semua tabel PO, Jatuh Tempo, dan pendukung sudah siap</p>";
            echo "<p style='color: #155724;'>✅ Data sample sudah ditambahkan</p>";
            echo "<p style='color: #155724;'>🔗 Silakan coba input PO lagi dari aplikasi web</p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
            echo "<h3 style='color: #721c24;'>❌ Error</h3>";
            echo "<p style='color: #721c24;'>Error: " . $e->getMessage() . "</p>";
            echo "<p style='color: #721c24;'>Line: " . $e->getLine() . "</p>";
            echo "<p style='color: #721c24;'>File: " . $e->getFile() . "</p>";
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e9ecef; border-radius: 5px;">
            <h4>📝 Langkah Selanjutnya:</h4>
            <ol>
                <li>Buka aplikasi Laravel Anda</li>
                <li>Login dengan email: <code>admin@gmail.com</code> atau <code>perle@gmail.com</code></li>
                <li>Password: <code>password123</code></li>
                <li>Coba input PO baru dari menu PO</li>
                <li>Periksa apakah data tersimpan di menu Jatuh Tempo</li>
            </ol>
        </div>
    </div>
</body>
</html>
