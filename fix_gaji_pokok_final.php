<?php
// Final fix untuk kolom gaji_pokok - langsung eksekusi

$mysqli = new mysqli('127.0.0.1', 'root', '', 'manajemen_perusahaan');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Cek apakah kolom gaji_pokok ada
$result = $mysqli->query("SHOW COLUMNS FROM employees LIKE 'gaji_pokok'");
if ($result->num_rows == 0) {
    // Tambahkan kolom gaji_pokok
    $mysqli->query("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
    echo "✅ Kolom gaji_pokok ditambahkan\n";
} else {
    echo "ℹ️ Kolom gaji_pokok sudah ada\n";
}

// Cek apakah kolom status ada
$result = $mysqli->query("SHOW COLUMNS FROM employees LIKE 'status'");
if ($result->num_rows == 0) {
    $mysqli->query("ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif'");
    echo "✅ Kolom status ditambahkan\n";
}

// Update data kosong
$mysqli->query("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok IS NULL OR gaji_pokok = 0");
$mysqli->query("UPDATE employees SET status = 'aktif' WHERE status IS NULL OR status = ''");

// Cek jumlah data
$result = $mysqli->query("SELECT COUNT(*) as count FROM employees");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $mysqli->query("INSERT INTO employees (nama_karyawan, gaji_pokok, status) VALUES 
                   ('Budi Santoso', 6000000, 'aktif'),
                   ('Siti Nurhaliza', 5500000, 'aktif'),
                   ('Ahmad Rahman', 7000000, 'aktif')");
    echo "✅ Data sample ditambahkan\n";
}

// Test query
$result = $mysqli->query("SELECT SUM(gaji_pokok) as total FROM employees WHERE status = 'aktif'");
$row = $result->fetch_assoc();
echo "✅ Test berhasil - Total gaji: Rp " . number_format($row['total'], 0, ',', '.') . "\n";

$mysqli->close();
echo "🎉 PERBAIKAN SELESAI!\n";
?>
