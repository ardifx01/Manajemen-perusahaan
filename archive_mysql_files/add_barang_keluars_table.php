<?php
// ADD BARANG_KELUARS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel barang_keluars
$pdo->exec("
CREATE TABLE IF NOT EXISTS barang_keluars (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_barang VARCHAR(255) NOT NULL,
    qty INTEGER NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    tanggal DATE NOT NULL,
    customer VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk barang_keluars September 2025
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$pdo->exec("INSERT INTO barang_keluars (nama_barang, qty, harga, total, tanggal, customer, created_at, updated_at) VALUES 
    ('Kertas A4', 50, 50000, 2500000, '$today', 'PT Customer ABC', '$now', '$now'),
    ('Tinta Printer', 10, 75000, 750000, '$today', 'PT Customer XYZ', '$now', '$now'),
    ('Alat Tulis', 25, 25000, 625000, '$today', 'Kantor Pemerintah', '$now', '$now')");

echo "barang_keluars table added successfully\n";
?>
