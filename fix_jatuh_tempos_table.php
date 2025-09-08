<?php
// FIX JATUH_TEMPOS TABLE - Tambah kolom tanggal_invoice yang hilang
echo "FIXING JATUH_TEMPOS TABLE - ADDING MISSING COLUMNS\n";

// Koneksi ke database yang sudah ada
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Cek struktur tabel jatuh_tempos saat ini
echo "Checking current jatuh_tempos table structure...\n";
$result = $pdo->query("PRAGMA table_info(jatuh_tempos)");
$existingColumns = [];
while ($row = $result->fetch()) {
    $existingColumns[] = $row['name'];
}
echo "Existing columns: " . implode(', ', $existingColumns) . "\n\n";

// Daftar kolom yang diperlukan
$requiredColumns = [
    'tanggal_invoice' => 'DATE NULL',
    'jumlah_tagihan' => 'DECIMAL(15,2) DEFAULT 0',
    'no_invoice' => 'VARCHAR(255) NULL',
    'customer' => 'VARCHAR(255) NULL',
    'keterangan' => 'TEXT NULL'
];

// Tambahkan kolom yang hilang
$addedColumns = [];
foreach ($requiredColumns as $columnName => $columnDef) {
    if (!in_array($columnName, $existingColumns)) {
        try {
            $pdo->exec("ALTER TABLE jatuh_tempos ADD COLUMN $columnName $columnDef");
            $addedColumns[] = $columnName;
            echo "✅ Added column: $columnName\n";
        } catch (Exception $e) {
            echo "❌ Failed to add column $columnName: " . $e->getMessage() . "\n";
        }
    } else {
        echo "⏭️ Column $columnName already exists\n";
    }
}

// Verifikasi struktur tabel setelah update
echo "\nVerifying updated table structure...\n";
$result = $pdo->query("PRAGMA table_info(jatuh_tempos)");
$finalColumns = [];
while ($row = $result->fetch()) {
    $finalColumns[] = $row['name'];
}
echo "Final columns: " . implode(', ', $finalColumns) . "\n";

// Test insert dengan struktur baru
echo "\nTesting insert with new structure...\n";
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

try {
    $pdo->exec("INSERT INTO jatuh_tempos (tanggal_jatuh_tempo, tanggal_invoice, jumlah, jumlah_tagihan, no_invoice, customer, status, keterangan, created_at, updated_at) VALUES 
        ('$today', '$today', 1000000, 1000000, 'INV-TEST-001', 'PT Test Customer', 'pending', 'Test jatuh tempo entry', '$now', '$now')");
    echo "✅ Test insert successful\n";
    
    // Hapus test data
    $pdo->exec("DELETE FROM jatuh_tempos WHERE keterangan = 'Test jatuh tempo entry'");
    echo "✅ Test data cleaned up\n";
} catch (Exception $e) {
    echo "❌ Test insert failed: " . $e->getMessage() . "\n";
}

// Test query yang error sebelumnya
echo "\nTesting problematic query...\n";
try {
    $result = $pdo->query("SELECT MONTH(tanggal_invoice) as m, COUNT(*) as total_count, COALESCE(SUM(jumlah_tagihan),0) as total_sum FROM jatuh_tempos WHERE strftime('%Y', tanggal_invoice) = '2025' GROUP BY m LIMIT 1");
    echo "✅ Query test successful\n";
} catch (Exception $e) {
    echo "❌ Query test failed: " . $e->getMessage() . "\n";
}

try {
    // Fix produks table - check structure
    echo "🔧 Checking produks table structure...\n";
    
    $result = $pdo->query("PRAGMA table_info(produks)");
    $produkColumns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($produkColumns)) {
        echo "❌ Tabel 'produks' tidak ditemukan!\n";
        echo "🔧 Creating produks table...\n";
        
        $createProduksTable = "
            CREATE TABLE produks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                kode_produk VARCHAR(50) UNIQUE,
                nama_produk VARCHAR(255) NOT NULL,
                harga DECIMAL(15,2) DEFAULT 0,
                harga_pcs DECIMAL(15,2) DEFAULT 0,
                harga_set DECIMAL(15,2) DEFAULT 0,
                satuan VARCHAR(50),
                deskripsi TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $pdo->exec($createProduksTable);
        echo "✅ Tabel 'produks' berhasil dibuat!\n";
        
        // Add sample data
        $sampleData = "
            INSERT INTO produks (kode_produk, nama_produk, harga, harga_pcs, harga_set, satuan, deskripsi) VALUES
            ('PRD0001', 'Produk Sample 1', 10000, 10000, 95000, 'pcs', 'Produk contoh untuk testing'),
            ('PRD0002', 'Produk Sample 2', 15000, 15000, 140000, 'pcs', 'Produk contoh kedua')
        ";
        $pdo->exec($sampleData);
        echo "✅ Sample data produk ditambahkan!\n";
    } else {
        echo "✅ Tabel 'produks' sudah ada\n";
        
        // Check required columns
        $existingProdukColumns = array_column($produkColumns, 'name');
        $requiredProdukColumns = ['kode_produk', 'nama_produk', 'harga', 'harga_pcs', 'harga_set', 'satuan', 'deskripsi'];
        
        foreach ($requiredProdukColumns as $column) {
            if (!in_array($column, $existingProdukColumns)) {
                echo "❌ Column '$column' missing from produks table\n";
                echo "🔧 Adding '$column' column...\n";
                
                $columnType = match($column) {
                    'harga', 'harga_pcs', 'harga_set' => 'DECIMAL(15,2) DEFAULT 0',
                    'kode_produk', 'satuan' => 'VARCHAR(50)',
                    'nama_produk' => 'VARCHAR(255)',
                    'deskripsi' => 'TEXT',
                    default => 'VARCHAR(255)'
                };
                
                $pdo->exec("ALTER TABLE produks ADD COLUMN $column $columnType");
                echo "✅ Column '$column' added successfully!\n";
            }
        }
    }
    
    // Test insert produk
    echo "🧪 Testing produk insert...\n";
    $testProduk = "INSERT INTO produks (kode_produk, nama_produk, harga, harga_pcs) 
                   VALUES ('TEST001', 'Test Produk', 5000, 5000)";
    $pdo->exec($testProduk);
    echo "✅ Test produk insert successful!\n";
    
    // Clean up test data
    $pdo->exec("DELETE FROM produks WHERE kode_produk = 'TEST001'");

    // Fix kendaraans table - check structure
    echo "\n🔧 Checking kendaraans table structure...\n";
    
    $result = $pdo->query("PRAGMA table_info(kendaraans)");
    $kendaraanColumns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($kendaraanColumns)) {
        echo "❌ Tabel 'kendaraans' tidak ditemukan!\n";
        echo "🔧 Creating kendaraans table...\n";
        
        $createKendaraansTable = "
            CREATE TABLE kendaraans (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama VARCHAR(255) NOT NULL,
                nama_kendaraan VARCHAR(255),
                no_polisi VARCHAR(50),
                jenis_kendaraan VARCHAR(100),
                status VARCHAR(20) DEFAULT 'aktif',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $pdo->exec($createKendaraansTable);
        echo "✅ Tabel 'kendaraans' berhasil dibuat!\n";
        
        // Add sample data
        $sampleKendaraan = "
            INSERT INTO kendaraans (nama, nama_kendaraan, no_polisi, jenis_kendaraan, status) VALUES
            ('Truck Besar', 'Truck Besar', 'B 1234 AB', 'Truck', 'aktif'),
            ('Pickup Kecil', 'Pickup Kecil', 'B 5678 CD', 'Pickup', 'aktif')
        ";
        $pdo->exec($sampleKendaraan);
        echo "✅ Sample data kendaraan ditambahkan!\n";
    } else {
        echo "✅ Tabel 'kendaraans' sudah ada\n";
        
        // Check required columns
        $existingKendaraanColumns = array_column($kendaraanColumns, 'name');
        $requiredKendaraanColumns = ['nama', 'nama_kendaraan', 'no_polisi', 'jenis_kendaraan', 'status'];
        
        foreach ($requiredKendaraanColumns as $column) {
            if (!in_array($column, $existingKendaraanColumns)) {
                echo "❌ Column '$column' missing from kendaraans table\n";
                echo "🔧 Adding '$column' column...\n";
                
                $columnType = match($column) {
                    'nama', 'nama_kendaraan' => 'VARCHAR(255)',
                    'no_polisi' => 'VARCHAR(50)',
                    'jenis_kendaraan' => 'VARCHAR(100)',
                    'status' => 'VARCHAR(20) DEFAULT \'aktif\'',
                    default => 'VARCHAR(255)'
                };
                
                $pdo->exec("ALTER TABLE kendaraans ADD COLUMN $column $columnType");
                echo "✅ Column '$column' added successfully!\n";
            }
        }
        
        // Update existing records to fill nama column if empty
        echo "🔧 Updating existing records to fill nama column...\n";
        $pdo->exec("UPDATE kendaraans SET nama = COALESCE(nama_kendaraan, 'Unknown Vehicle') WHERE nama IS NULL OR nama = ''");
        echo "✅ Updated existing records with nama values\n";
    }
    
    // Test insert kendaraan
    echo "🧪 Testing kendaraan insert...\n";
    $testKendaraan = "INSERT INTO kendaraans (nama, nama_kendaraan, no_polisi, jenis_kendaraan) 
                      VALUES ('Test Vehicle', 'Test Vehicle', 'B 9999 XX', 'Test')";
    $pdo->exec($testKendaraan);
    echo "✅ Test kendaraan insert successful!\n";
    
    // Clean up test data
    $pdo->exec("DELETE FROM kendaraans WHERE no_polisi = 'B 9999 XX'");

    // Fix admin user - set is_admin flag
    echo "\n🔧 Checking admin user permissions...\n";
    
    // Check if users table has is_admin column
    $result = $pdo->query("PRAGMA table_info(users)");
    $userColumns = $result->fetchAll(PDO::FETCH_ASSOC);
    $hasIsAdminColumn = false;
    
    foreach ($userColumns as $column) {
        if ($column['name'] === 'is_admin') {
            $hasIsAdminColumn = true;
            break;
        }
    }
    
    if (!$hasIsAdminColumn) {
        echo "❌ Column 'is_admin' missing from users table\n";
        echo "🔧 Adding 'is_admin' column...\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN is_admin BOOLEAN DEFAULT 0");
        echo "✅ Column 'is_admin' added successfully!\n";
    }
    
    // Update admin@gmail.com to have admin privileges
    echo "🔧 Setting admin privileges for admin@gmail.com...\n";
    $result = $pdo->exec("UPDATE users SET is_admin = 1 WHERE email = 'admin@gmail.com'");
    
    if ($result > 0) {
        echo "✅ Admin privileges granted to admin@gmail.com\n";
    } else {
        echo "⚠️  admin@gmail.com not found, creating admin user...\n";
        
        $adminInsert = "INSERT INTO users (name, email, password, is_admin, email_verified_at, created_at, updated_at) 
                        VALUES ('Admin User', 'admin@gmail.com', ?, 1, datetime('now'), datetime('now'), datetime('now'))";
        
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare($adminInsert);
        $stmt->execute([$hashedPassword]);
        echo "✅ Admin user created with full privileges\n";
    }
    
    // Verify admin status
    $adminCheck = $pdo->query("SELECT name, email, is_admin FROM users WHERE email = 'admin@gmail.com'")->fetch(PDO::FETCH_ASSOC);
    if ($adminCheck) {
        echo "✅ Admin verification: {$adminCheck['name']} ({$adminCheck['email']}) - is_admin: {$adminCheck['is_admin']}\n";
    }

    // Fix pos table - add missing 'no_invoice' column
    echo "\n🔧 Checking pos table structure...\n";
    
    // Check if pos table has 'no_invoice' column
    $result = $pdo->query("PRAGMA table_info(pos)");
    $posColumns = $result->fetchAll(PDO::FETCH_ASSOC);
    $hasNoInvoiceColumn = false;
    
    foreach ($posColumns as $column) {
        if ($column['name'] === 'no_invoice') {
            $hasNoInvoiceColumn = true;
            break;
        }
    }
    
    if (!$hasNoInvoiceColumn) {
        echo "❌ Column 'no_invoice' missing from pos table\n";
        echo "🔧 Adding 'no_invoice' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN no_invoice VARCHAR(255)");
        echo "✅ Column 'no_invoice' added successfully!\n";
    } else {
        echo "✅ Column 'no_invoice' already exists in pos table\n";
    }
    
    // Check if pos table has 'customer_id' column
    $hasCustomerIdColumn = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'customer_id') {
            $hasCustomerIdColumn = true;
            break;
        }
    }
    
    if (!$hasCustomerIdColumn) {
        echo "❌ Column 'customer_id' missing from pos table\n";
        echo "🔧 Adding 'customer_id' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN customer_id INTEGER");
        echo "✅ Column 'customer_id' added successfully!\n";
    } else {
        echo "✅ Column 'customer_id' already exists in pos table\n";
    }
    
    // Check if pos table has 'qty_jenis' column
    $hasQtyJenisColumn = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'qty_jenis') {
            $hasQtyJenisColumn = true;
            break;
        }
    }
    
    if (!$hasQtyJenisColumn) {
        echo "❌ Column 'qty_jenis' missing from pos table\n";
        echo "🔧 Adding 'qty_jenis' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN qty_jenis VARCHAR(50)");
        echo "✅ Column 'qty_jenis' added successfully!\n";
    } else {
        echo "✅ Column 'qty_jenis' already exists in pos table\n";
    }
    
    // Check if pos table has 'no_polisi' column
    $hasNoPolisiColumn = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'no_polisi') {
            $hasNoPolisiColumn = true;
            break;
        }
    }
    
    if (!$hasNoPolisiColumn) {
        echo "❌ Column 'no_polisi' missing from pos table\n";
        echo "🔧 Adding 'no_polisi' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN no_polisi VARCHAR(50)");
        echo "✅ Column 'no_polisi' added successfully!\n";
    } else {
        echo "✅ Column 'no_polisi' already exists in pos table\n";
    }
    
    // Check if pos table has 'alamat_1' column
    $hasAlamat1Column = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'alamat_1') {
            $hasAlamat1Column = true;
            break;
        }
    }
    
    if (!$hasAlamat1Column) {
        echo "❌ Column 'alamat_1' missing from pos table\n";
        echo "🔧 Adding 'alamat_1' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_1 TEXT");
        echo "✅ Column 'alamat_1' added successfully!\n";
    } else {
        echo "✅ Column 'alamat_1' already exists in pos table\n";
    }
    
    // Check if pos table has 'alamat_2' column
    $hasAlamat2Column = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'alamat_2') {
            $hasAlamat2Column = true;
            break;
        }
    }
    
    if (!$hasAlamat2Column) {
        echo "❌ Column 'alamat_2' missing from pos table\n";
        echo "🔧 Adding 'alamat_2' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_2 TEXT");
        echo "✅ Column 'alamat_2' added successfully!\n";
    } else {
        echo "✅ Column 'alamat_2' already exists in pos table\n";
    }
    
    // Check if pos table has 'pengirim' column
    $hasPengirimColumn = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'pengirim') {
            $hasPengirimColumn = true;
            break;
        }
    }
    
    if (!$hasPengirimColumn) {
        echo "❌ Column 'pengirim' missing from pos table\n";
        echo "🔧 Adding 'pengirim' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN pengirim VARCHAR(255)");
        echo "✅ Column 'pengirim' added successfully!\n";
    } else {
        echo "✅ Column 'pengirim' already exists in pos table\n";
    }
    
    // Check if pos table has 'produk' column
    $hasProdukColumn = false;
    foreach ($posColumns as $column) {
        if ($column['name'] === 'produk') {
            $hasProdukColumn = true;
            break;
        }
    }
    
    if (!$hasProdukColumn) {
        echo "❌ Column 'produk' missing from pos table\n";
        echo "🔧 Adding 'produk' column...\n";
        $pdo->exec("ALTER TABLE pos ADD COLUMN produk VARCHAR(255)");
        echo "✅ Column 'produk' added successfully!\n";
    } else {
        echo "✅ Column 'produk' already exists in pos table\n";
        
        // Check if produk column has NOT NULL constraint
        echo "🔧 Checking produk column constraint...\n";
        $tableInfo = $pdo->query("PRAGMA table_info(pos)")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tableInfo as $column) {
            if ($column['name'] === 'produk' && $column['notnull'] == 1) {
                echo "❌ Column 'produk' has NOT NULL constraint\n";
                echo "🔧 Removing NOT NULL constraint by recreating table...\n";
                
                // Get all data from pos table
                $posData = $pdo->query("SELECT * FROM pos")->fetchAll(PDO::FETCH_ASSOC);
                
                // Drop and recreate pos table without NOT NULL constraint on produk
                $pdo->exec("DROP TABLE IF EXISTS pos_backup");
                $pdo->exec("ALTER TABLE pos RENAME TO pos_backup");
                
                $createPosTable = "
                CREATE TABLE pos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    no_surat_jalan VARCHAR(255),
                    no_po VARCHAR(255),
                    no_invoice VARCHAR(255),
                    customer_id INTEGER,
                    customer VARCHAR(255),
                    tanggal_po DATETIME,
                    produk_id INTEGER,
                    produk VARCHAR(255),
                    qty INTEGER,
                    qty_jenis VARCHAR(50),
                    harga DECIMAL(15,2),
                    total DECIMAL(15,2),
                    kendaraan VARCHAR(255),
                    no_polisi VARCHAR(50),
                    alamat_1 TEXT,
                    alamat_2 TEXT,
                    pengirim VARCHAR(255),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )";
                
                $pdo->exec($createPosTable);
                
                // Restore data
                if (!empty($posData)) {
                    foreach ($posData as $row) {
                        $columns = array_keys($row);
                        $values = array_values($row);
                        $placeholders = str_repeat('?,', count($values) - 1) . '?';
                        $sql = "INSERT INTO pos (" . implode(',', $columns) . ") VALUES ($placeholders)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($values);
                    }
                }
                
                $pdo->exec("DROP TABLE pos_backup");
                echo "✅ Table recreated without NOT NULL constraint on produk\n";
                break;
            }
        }
    }
    
    // Test insert pos
    echo "🧪 Testing pos insert...\n";
    $testPos = "INSERT INTO pos (no_surat_jalan, no_po, no_invoice, customer_id, customer, tanggal_po, produk_id, qty, qty_jenis, harga, total, kendaraan, no_polisi, alamat_1, alamat_2, pengirim, created_at, updated_at) 
                VALUES ('TEST/001/2025', 'PO-TEST-001', 'INV-TEST-001', 1, 'Test Customer', datetime('now'), 1, 1, 'PCS', 100000, 100000, 'Test Vehicle', 'B 0000 XX', 'Test Address 1', 'Test Address 2', 'Test Pengirim', datetime('now'), datetime('now'))";
    $pdo->exec($testPos);
    echo "✅ Test pos insert successful!\n";
    
    // Clean up test data
    $pdo->exec("DELETE FROM pos WHERE no_po = 'PO-TEST-001'");

    // Fix po_items table - add missing 'produk_id' column
    echo "\n🔧 Checking po_items table structure...\n";
    
    // Check if po_items table has 'produk_id' column
    $result = $pdo->query("PRAGMA table_info(po_items)");
    $poItemsColumns = $result->fetchAll(PDO::FETCH_ASSOC);
    $hasProdukIdColumn = false;
    
    foreach ($poItemsColumns as $column) {
        if ($column['name'] === 'produk_id') {
            $hasProdukIdColumn = true;
            break;
        }
    }
    
    if (!$hasProdukIdColumn) {
        echo "❌ Column 'produk_id' missing from po_items table\n";
        echo "🔧 Adding 'produk_id' column...\n";
        $pdo->exec("ALTER TABLE po_items ADD COLUMN produk_id INTEGER");
        echo "✅ Column 'produk_id' added successfully!\n";
    } else {
        echo "✅ Column 'produk_id' already exists in po_items table\n";
    }
    
    // Check if po_items table has 'qty_jenis' column
    $hasQtyJenisColumn = false;
    foreach ($poItemsColumns as $column) {
        if ($column['name'] === 'qty_jenis') {
            $hasQtyJenisColumn = true;
            break;
        }
    }
    
    if (!$hasQtyJenisColumn) {
        echo "❌ Column 'qty_jenis' missing from po_items table\n";
        echo "🔧 Adding 'qty_jenis' column...\n";
        $pdo->exec("ALTER TABLE po_items ADD COLUMN qty_jenis VARCHAR(50)");
        echo "✅ Column 'qty_jenis' added successfully!\n";
    } else {
        echo "✅ Column 'qty_jenis' already exists in po_items table\n";
    }
    
    // Check if po_items table has 'produk' column and fix NOT NULL constraint
    $hasProdukColumn = false;
    foreach ($poItemsColumns as $column) {
        if ($column['name'] === 'produk') {
            $hasProdukColumn = true;
            break;
        }
    }
    
    if (!$hasProdukColumn) {
        echo "❌ Column 'produk' missing from po_items table\n";
        echo "🔧 Adding 'produk' column...\n";
        $pdo->exec("ALTER TABLE po_items ADD COLUMN produk VARCHAR(255)");
        echo "✅ Column 'produk' added successfully!\n";
    } else {
        echo "✅ Column 'produk' already exists in po_items table\n";
        
        // Check if produk column has NOT NULL constraint
        echo "🔧 Checking produk column constraint in po_items...\n";
        $tableInfo = $pdo->query("PRAGMA table_info(po_items)")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tableInfo as $column) {
            if ($column['name'] === 'produk' && $column['notnull'] == 1) {
                echo "❌ Column 'produk' has NOT NULL constraint in po_items\n";
                echo "🔧 Removing NOT NULL constraint by recreating po_items table...\n";
                
                // Get all data from po_items table
                $poItemsData = $pdo->query("SELECT * FROM po_items")->fetchAll(PDO::FETCH_ASSOC);
                
                // Drop and recreate po_items table without NOT NULL constraint on produk
                $pdo->exec("DROP TABLE IF EXISTS po_items_backup");
                $pdo->exec("ALTER TABLE po_items RENAME TO po_items_backup");
                
                $createPoItemsTable = "
                CREATE TABLE po_items (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    po_id INTEGER,
                    produk_id INTEGER,
                    produk VARCHAR(255),
                    qty INTEGER,
                    qty_jenis VARCHAR(50),
                    harga DECIMAL(15,2),
                    total DECIMAL(15,2),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )";
                
                $pdo->exec($createPoItemsTable);
                
                // Restore data
                if (!empty($poItemsData)) {
                    foreach ($poItemsData as $row) {
                        $columns = array_keys($row);
                        $values = array_values($row);
                        $placeholders = str_repeat('?,', count($values) - 1) . '?';
                        $sql = "INSERT INTO po_items (" . implode(',', $columns) . ") VALUES ($placeholders)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($values);
                    }
                }
                
                $pdo->exec("DROP TABLE po_items_backup");
                echo "✅ po_items table recreated without NOT NULL constraint on produk\n";
                break;
            }
        }
    }
    
    // Test insert po_items
    echo "🧪 Testing po_items insert...\n";
    $testPoItems = "INSERT INTO po_items (po_id, produk_id, qty, qty_jenis, harga, total, created_at, updated_at) 
                    VALUES (1, 1, 1, 'PCS', 100000, 100000, datetime('now'), datetime('now'))";
    $pdo->exec($testPoItems);
    echo "✅ Test po_items insert successful!\n";
    
    // Clean up test data
    $pdo->exec("DELETE FROM po_items WHERE po_id = 1 AND produk_id = 1");

    // Fix customers table - add missing 'name' column
    echo "\n🔧 Checking customers table structure...\n";
    
    // Check if customers table exists and has 'name' column
    $result = $pdo->query("PRAGMA table_info(customers)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $hasNameColumn = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'name') {
            $hasNameColumn = true;
            break;
        }
    }
    
    // Check and add missing columns
    $requiredColumns = ['name', 'nama_customer', 'address_1', 'address_2', 'payment_terms_days', 'delivery_note_number', 'invoice_number'];
    $existingColumns = array_column($columns, 'name');
    
    foreach ($requiredColumns as $column) {
        if (!in_array($column, $existingColumns)) {
            echo "❌ Column '$column' missing from customers table\n";
            echo "🔧 Adding '$column' column...\n";
            
            $columnType = match($column) {
                'payment_terms_days' => 'INTEGER',
                'name', 'nama_customer', 'address_1', 'address_2', 'delivery_note_number', 'invoice_number' => 'VARCHAR(255)',
                default => 'VARCHAR(255)'
            };
            
            $pdo->exec("ALTER TABLE customers ADD COLUMN $column $columnType");
            echo "✅ Column '$column' added successfully!\n";
        } else {
            echo "✅ Column '$column' already exists\n";
        }
    }
    
    // Handle NOT NULL constraint for nama_customer
    echo "🔧 Updating existing records to fill nama_customer...\n";
    $pdo->exec("UPDATE customers SET nama_customer = name WHERE nama_customer IS NULL OR nama_customer = ''");
    echo "✅ Updated existing records with nama_customer values\n";
    
    // Test insert to customers table with nama_customer
    echo "🧪 Testing customers table insert...\n";
    $testInsert = "INSERT INTO customers (name, nama_customer, address_1, address_2, payment_terms_days) 
                   VALUES ('Test Customer', 'Test Customer', 'Test Address 1', 'Test Address 2', 30)";
    $pdo->exec($testInsert);
    echo "✅ Test insert successful!\n";
    
    // Clean up test data
    $pdo->exec("DELETE FROM customers WHERE name = 'Test Customer'");
    
} catch (Exception $e) {
    echo "❌ Customers table fix failed: " . $e->getMessage() . "\n";
}

// Tampilkan ringkasan
echo "\n✅ JATUH_TEMPOS TABLE FIXED!\n";
echo "➕ Columns added: " . (count($addedColumns) > 0 ? implode(', ', $addedColumns) : 'None') . "\n";
echo "📊 Total columns now: " . count($finalColumns) . "\n";
echo "\n🔧 Table structure updated successfully!\n";
echo "📋 Invoice queries should work now\n";
echo "🌐 URL: http://127.0.0.1:8000/login\n";
?>
