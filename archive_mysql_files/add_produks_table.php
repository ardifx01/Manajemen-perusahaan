<?php
// ADD PRODUKS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel produks
$pdo->exec("
CREATE TABLE IF NOT EXISTS produks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_produk VARCHAR(255) NOT NULL,
    kode_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    satuan VARCHAR(50) NOT NULL,
    deskripsi TEXT NULL,
    status VARCHAR(50) DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk produks
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO produks (nama_produk, kode_produk, harga, satuan, deskripsi, status, created_at, updated_at) VALUES 
    ('Produk A', 'PRD001', 100000, 'pcs', 'Produk unggulan perusahaan', 'aktif', '$now', '$now'),
    ('Produk B', 'PRD002', 200000, 'pcs', 'Produk premium dengan kualitas tinggi', 'aktif', '$now', '$now'),
    ('Produk C', 'PRD003', 150000, 'pcs', 'Produk standar untuk kebutuhan umum', 'aktif', '$now', '$now')");

echo "produks table added successfully\n";
?>
