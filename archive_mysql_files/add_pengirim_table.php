<?php
// ADD PENGIRIM TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel pengirim
$pdo->exec("
CREATE TABLE IF NOT EXISTS pengirim (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama VARCHAR(255) NOT NULL,
    alamat TEXT NULL,
    no_telepon VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk pengirim
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO pengirim (nama, alamat, no_telepon, email, status, created_at, updated_at) VALUES 
    ('Ekspedisi Cepat', 'Jl. Raya Jakarta No. 100', '021-1111111', 'info@ekspedisicepat.com', 'aktif', '$now', '$now'),
    ('Logistik Prima', 'Jl. Sudirman No. 200', '021-2222222', 'contact@logistikprima.com', 'aktif', '$now', '$now'),
    ('Pengiriman Mandiri', 'Jl. Thamrin No. 300', '021-3333333', 'admin@pengirimanmandiri.com', 'aktif', '$now', '$now')");

echo "pengirim table added successfully\n";
?>
