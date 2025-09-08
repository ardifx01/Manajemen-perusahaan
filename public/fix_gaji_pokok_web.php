<?php
// Web script untuk menambahkan kolom gaji_pokok ke tabel employees

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "<h2>🔧 Perbaikan Kolom gaji_pokok</h2>";

    // Cek struktur tabel employees
    $columns = $pdo->query("SHOW COLUMNS FROM employees")->fetchAll();
    $hasGajiPokok = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'gaji_pokok') {
            $hasGajiPokok = true;
            break;
        }
    }

    if (!$hasGajiPokok) {
        echo "<p>⚠️ Kolom gaji_pokok tidak ditemukan. Menambahkan...</p>";
        $pdo->exec("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
        echo "<p style='color: green;'>✅ Kolom gaji_pokok berhasil ditambahkan!</p>";
    } else {
        echo "<p style='color: green;'>✅ Kolom gaji_pokok sudah ada.</p>";
    }

    // Tambahkan kolom status jika belum ada
    $hasStatus = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'status') {
            $hasStatus = true;
            break;
        }
    }

    if (!$hasStatus) {
        echo "<p>⚠️ Kolom status tidak ditemukan. Menambahkan...</p>";
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif'");
        echo "<p style='color: green;'>✅ Kolom status berhasil ditambahkan!</p>";
    }

    // Update data yang kosong
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL");
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status = '' OR status IS NULL");

    // Cek apakah ada data employees
    $count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    if ($count == 0) {
        echo "<p>⚠️ Tabel employees kosong. Menambahkan data sample...</p>";
        $pdo->exec("INSERT INTO employees (nama_karyawan, gaji_pokok, status) VALUES 
                   ('Budi Santoso', 6000000, 'aktif'),
                   ('Siti Nurhaliza', 5500000, 'aktif'),
                   ('Ahmad Rahman', 7000000, 'aktif')");
        echo "<p style='color: green;'>✅ Data sample employees berhasil ditambahkan!</p>";
    }

    // Test query yang error sebelumnya
    echo "<h3>🧪 Test Query:</h3>";
    $result = $pdo->query("SELECT COUNT(*) as total FROM employees WHERE status = 'aktif'")->fetch();
    echo "<p>Total karyawan aktif: {$result['total']}</p>";

    $result = $pdo->query("SELECT SUM(gaji_pokok) as total_gaji FROM employees WHERE status = 'aktif'")->fetch();
    echo "<p>Total gaji pokok: Rp " . number_format($result['total_gaji'], 0, ',', '.') . "</p>";

    echo "<h3 style='color: green;'>✅ Perbaikan Selesai!</h3>";
    echo "<p>Sekarang coba refresh aplikasi Laravel Anda.</p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
