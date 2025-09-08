<?php
// ADD KENDARAANS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel kendaraans
$pdo->exec("
CREATE TABLE IF NOT EXISTS kendaraans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_kendaraan VARCHAR(255) NOT NULL,
    nopol VARCHAR(20) NOT NULL,
    jenis VARCHAR(100) NOT NULL,
    status VARCHAR(50) DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk kendaraans
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO kendaraans (nama_kendaraan, nopol, jenis, status, created_at, updated_at) VALUES 
    ('Truck Fuso', 'B 1234 ABC', 'Truck', 'aktif', '$now', '$now'),
    ('Pickup L300', 'B 5678 DEF', 'Pickup', 'aktif', '$now', '$now'),
    ('Motor Vario', 'B 9012 GHI', 'Motor', 'aktif', '$now', '$now')");

echo "kendaraans table added successfully\n";
?>
