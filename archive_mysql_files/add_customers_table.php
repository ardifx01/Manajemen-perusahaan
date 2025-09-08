<?php
// ADD CUSTOMERS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel customers
$pdo->exec("
CREATE TABLE IF NOT EXISTS customers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_customer VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    no_telepon VARCHAR(20) NULL,
    alamat TEXT NULL,
    kota VARCHAR(100) NULL,
    status VARCHAR(50) DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk customers
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO customers (nama_customer, email, no_telepon, alamat, kota, status, created_at, updated_at) VALUES 
    ('PT ABC Corporation', 'contact@abc.com', '021-1234567', 'Jl. Sudirman No. 123', 'Jakarta', 'aktif', '$now', '$now'),
    ('PT XYZ Industries', 'info@xyz.com', '021-7654321', 'Jl. Thamrin No. 456', 'Jakarta', 'aktif', '$now', '$now'),
    ('CV Maju Jaya', 'admin@majujaya.com', '021-9876543', 'Jl. Gatot Subroto No. 789', 'Jakarta', 'aktif', '$now', '$now')");

echo "customers table added successfully\n";
?>
