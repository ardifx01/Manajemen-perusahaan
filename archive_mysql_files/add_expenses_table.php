<?php
// ADD EXPENSES TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel expenses
$pdo->exec("
CREATE TABLE IF NOT EXISTS expenses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kategori VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk expenses September 2025
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$pdo->exec("INSERT INTO expenses (kategori, deskripsi, amount, tanggal, created_at, updated_at) VALUES 
    ('Operasional', 'Biaya listrik kantor', 500000, '$today', '$now', '$now'),
    ('Transport', 'Bensin kendaraan operasional', 200000, '$today', '$now', '$now'),
    ('Maintenance', 'Service komputer', 150000, '$today', '$now', '$now')");

echo "expenses table added successfully\n";
?>
