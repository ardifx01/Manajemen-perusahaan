<?php
// Quick fix untuk menambahkan kolom gaji_pokok ke tabel employees

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Tambahkan kolom gaji_pokok jika belum ada
    $pdo->exec("ALTER TABLE employees ADD COLUMN IF NOT EXISTS gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
    
    // Tambahkan kolom lain yang mungkin dibutuhkan
    $pdo->exec("ALTER TABLE employees ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'aktif'");
    $pdo->exec("ALTER TABLE employees ADD COLUMN IF NOT EXISTS tanggal_masuk DATE DEFAULT NULL");
    
    // Update data yang kosong
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL");
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status = '' OR status IS NULL");
    
    // Pastikan ada data employees
    $count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO employees (nama_karyawan, gaji_pokok, status, tanggal_masuk) VALUES 
                   ('Budi Santoso', 6000000, 'aktif', '2024-01-15'),
                   ('Siti Nurhaliza', 5500000, 'aktif', '2024-02-01'),
                   ('Ahmad Rahman', 7000000, 'aktif', '2024-01-10')");
    }
    
    echo "SUCCESS: Kolom gaji_pokok berhasil ditambahkan dan data diupdate!";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
