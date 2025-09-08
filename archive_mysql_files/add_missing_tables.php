<?php
// ADD MISSING TABLES - Tambah tabel yang hilang TANPA menghapus database existing
echo "ADDING MISSING TABLES TO EXISTING DATABASE\n";

// Koneksi ke database yang sudah ada
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Cek tabel yang sudah ada
$existingTables = [];
$result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $result->fetch()) {
    $existingTables[] = $row['name'];
}

echo "Existing tables: " . implode(', ', $existingTables) . "\n\n";

// Daftar tabel yang mungkin hilang
$missingTables = [
    'po_items' => "
    CREATE TABLE IF NOT EXISTS po_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        po_id INTEGER NOT NULL,
        produk VARCHAR(255) NOT NULL,
        qty INTEGER NOT NULL,
        harga DECIMAL(15,2) NOT NULL,
        total DECIMAL(15,2) NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'invoices' => "
    CREATE TABLE IF NOT EXISTS invoices (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        no_invoice VARCHAR(255) NOT NULL,
        customer VARCHAR(255) NOT NULL,
        tanggal_invoice DATE NOT NULL,
        subtotal DECIMAL(15,2) NOT NULL,
        ppn DECIMAL(15,2) NOT NULL,
        total DECIMAL(15,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'surat_jalans' => "
    CREATE TABLE IF NOT EXISTS surat_jalans (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        no_surat_jalan VARCHAR(255) NOT NULL,
        tanggal DATE NOT NULL,
        customer VARCHAR(255) NOT NULL,
        pengirim VARCHAR(255) NULL,
        kendaraan VARCHAR(255) NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'annual_summaries' => "
    CREATE TABLE IF NOT EXISTS annual_summaries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tahun INTEGER NOT NULL,
        total_revenue DECIMAL(15,2) DEFAULT 0,
        total_expense DECIMAL(15,2) DEFAULT 0,
        total_profit DECIMAL(15,2) DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'jatuh_tempos' => "
    CREATE TABLE IF NOT EXISTS jatuh_tempos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_id INTEGER NULL,
        tanggal_jatuh_tempo DATE NOT NULL,
        jumlah DECIMAL(15,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'tanda_terimas' => "
    CREATE TABLE IF NOT EXISTS tanda_terimas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        no_tanda_terima VARCHAR(255) NOT NULL,
        tanggal DATE NOT NULL,
        customer VARCHAR(255) NOT NULL,
        jumlah DECIMAL(15,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'gaji_karyawans' => "
    CREATE TABLE IF NOT EXISTS gaji_karyawans (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        employee_id INTEGER NOT NULL,
        bulan INTEGER NOT NULL,
        tahun INTEGER NOT NULL,
        gaji_pokok DECIMAL(15,2) NOT NULL,
        tunjangan DECIMAL(15,2) DEFAULT 0,
        potongan DECIMAL(15,2) DEFAULT 0,
        total_gaji DECIMAL(15,2) NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'pengirim' => "
    CREATE TABLE IF NOT EXISTS pengirim (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama VARCHAR(255) NOT NULL,
        alamat TEXT NULL,
        no_telepon VARCHAR(20) NULL,
        email VARCHAR(255) NULL,
        status VARCHAR(50) DEFAULT 'aktif',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )",
    
    'kendaraans' => "
    CREATE TABLE IF NOT EXISTS kendaraans (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_kendaraan VARCHAR(255) NOT NULL,
        no_polisi VARCHAR(20) NULL,
        jenis_kendaraan VARCHAR(100) NULL,
        status VARCHAR(20) DEFAULT 'aktif',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )"
];

// Tambahkan hanya tabel yang belum ada
$addedTables = [];
foreach ($missingTables as $tableName => $sql) {
    if (!in_array($tableName, $existingTables)) {
        $pdo->exec($sql);
        $addedTables[] = $tableName;
        echo "✅ $tableName table added\n";
    } else {
        echo "⏭️ $tableName table already exists\n";
    }
}

// Insert sample data hanya untuk tabel yang baru ditambahkan
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

if (in_array('po_items', $addedTables)) {
    // Cek apakah ada data pos untuk referensi
    $posCount = $pdo->query("SELECT COUNT(*) FROM pos")->fetchColumn();
    if ($posCount > 0) {
        $pdo->exec("INSERT INTO po_items (po_id, produk, qty, harga, total, created_at, updated_at) VALUES 
            (1, 'Produk A', 10, 100000, 1000000, '$now', '$now'),
            (1, 'Produk B', 5, 200000, 1000000, '$now', '$now')");
        echo "Sample po_items data inserted\n";
    }
}

if (in_array('pengirim', $addedTables)) {
    $pdo->exec("INSERT INTO pengirim (nama, alamat, no_telepon, email, status, created_at, updated_at) VALUES 
        ('Ekspedisi Cepat', 'Jl. Raya Jakarta No. 100', '021-1111111', 'info@ekspedisicepat.com', 'aktif', '$now', '$now'),
        ('Logistik Prima', 'Jl. Sudirman No. 200', '021-2222222', 'contact@logistikprima.com', 'aktif', '$now', '$now')");
    echo "Sample pengirim data inserted\n";
}

if (in_array('kendaraans', $addedTables)) {
    $pdo->exec("INSERT INTO kendaraans (nama_kendaraan, no_polisi, jenis_kendaraan, status, created_at, updated_at) VALUES 
        ('Truck Fuso', 'B 1234 ABC', 'Truck', 'aktif', '$now', '$now'),
        ('Pickup L300', 'B 5678 DEF', 'Pickup', 'aktif', '$now', '$now')");
    echo "Sample kendaraans data inserted\n";
}

// Verifikasi tabel setelah penambahan
$finalTables = [];
$result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $result->fetch()) {
    $finalTables[] = $row['name'];
}

echo "\n✅ MISSING TABLES ADDED SUCCESSFULLY!\n";
echo "📊 Total tables now: " . count($finalTables) . "\n";
echo "➕ Tables added: " . (count($addedTables) > 0 ? implode(', ', $addedTables) : 'None') . "\n";
echo "\n🔧 Database preserved - no data lost!\n";
echo "🌐 URL: http://127.0.0.1:8000/login\n";
echo "\n📝 Note: Your existing data is safe!\n";
?>
