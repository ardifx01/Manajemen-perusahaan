<?php
// ADD BARANG_MASUKS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel barang_masuks
$pdo->exec("
CREATE TABLE IF NOT EXISTS barang_masuks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_barang VARCHAR(255) NOT NULL,
    qty INTEGER NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    tanggal DATE NOT NULL,
    supplier VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk barang_masuks September 2025
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$pdo->exec("INSERT INTO barang_masuks (nama_barang, qty, harga, total, tanggal, supplier, created_at, updated_at) VALUES 
    ('Kertas A4', 100, 50000, 5000000, '$today', 'PT Supplier ABC', '$now', '$now'),
    ('Tinta Printer', 20, 75000, 1500000, '$today', 'PT Supplier XYZ', '$now', '$now'),
    ('Alat Tulis', 50, 25000, 1250000, '$today', 'Toko Stationery', '$now', '$now')");

echo "barang_masuks table added successfully\n";
?>
