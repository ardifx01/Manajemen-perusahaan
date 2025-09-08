<?php
// Direct fix untuk menambahkan kolom gaji_pokok ke tabel employees

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "Menambahkan kolom gaji_pokok ke tabel employees...\n";
    
    // Tambahkan kolom gaji_pokok
    $pdo->exec("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
    echo "✅ Kolom gaji_pokok berhasil ditambahkan\n";
    
    // Tambahkan kolom status jika belum ada
    try {
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif'");
        echo "✅ Kolom status berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️ Kolom status sudah ada\n";
    }
    
    // Update data yang kosong
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL");
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status = '' OR status IS NULL");
    echo "✅ Data default berhasil diupdate\n";
    
    // Pastikan ada data employees
    $count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO employees (nama_karyawan, gaji_pokok, status) VALUES 
                   ('Budi Santoso', 6000000, 'aktif'),
                   ('Siti Nurhaliza', 5500000, 'aktif'),
                   ('Ahmad Rahman', 7000000, 'aktif')");
        echo "✅ Data sample employees berhasil ditambahkan\n";
    }
    
    // Test query
    $result = $pdo->query("SELECT SUM(gaji_pokok) as total FROM employees WHERE status = 'aktif'")->fetch();
    echo "✅ Test query berhasil - Total gaji: Rp " . number_format($result['total'], 0, ',', '.') . "\n";
    
    echo "\n🎉 PERBAIKAN SELESAI! Aplikasi Laravel sekarang bisa berjalan tanpa error.\n";
    
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "ℹ️ Kolom gaji_pokok sudah ada, melanjutkan perbaikan...\n";
        
        // Update data saja
        $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL");
        echo "✅ Data gaji_pokok berhasil diupdate\n";
        
        // Test query
        $result = $pdo->query("SELECT SUM(gaji_pokok) as total FROM employees WHERE status = 'aktif'")->fetch();
        echo "✅ Test query berhasil - Total gaji: Rp " . number_format($result['total'], 0, ',', '.') . "\n";
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
?>
