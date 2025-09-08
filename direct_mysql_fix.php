<?php
// Direct MySQL fix untuk kolom gaji_pokok

$host = '127.0.0.1';
$dbname = 'manajemen_perusahaan';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cek apakah kolom gaji_pokok sudah ada
    $stmt = $pdo->query("SHOW COLUMNS FROM employees LIKE 'gaji_pokok'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        // Tambahkan kolom gaji_pokok
        $pdo->exec("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
        echo "Kolom gaji_pokok berhasil ditambahkan.\n";
    } else {
        echo "Kolom gaji_pokok sudah ada.\n";
    }
    
    // Cek apakah kolom status sudah ada
    $stmt = $pdo->query("SHOW COLUMNS FROM employees LIKE 'status'");
    $statusExists = $stmt->rowCount() > 0;
    
    if (!$statusExists) {
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif'");
        echo "Kolom status berhasil ditambahkan.\n";
    }
    
    // Update data kosong
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok IS NULL OR gaji_pokok = 0");
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status IS NULL OR status = ''");
    
    // Cek jumlah data employees
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Tambahkan data sample
        $pdo->exec("INSERT INTO employees (nama_karyawan, gaji_pokok, status) VALUES 
                   ('Budi Santoso', 6000000, 'aktif'),
                   ('Siti Nurhaliza', 5500000, 'aktif'),
                   ('Ahmad Rahman', 7000000, 'aktif')");
        echo "Data sample employees berhasil ditambahkan.\n";
    }
    
    // Test query yang error sebelumnya
    $stmt = $pdo->query("SELECT SUM(gaji_pokok) as total FROM employees WHERE status = 'aktif'");
    $result = $stmt->fetch();
    echo "Test berhasil! Total gaji: Rp " . number_format($result['total'], 0, ',', '.') . "\n";
    
    echo "PERBAIKAN SELESAI!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
