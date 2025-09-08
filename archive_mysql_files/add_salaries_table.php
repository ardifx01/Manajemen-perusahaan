<?php
// ADD SALARIES TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel salaries
$pdo->exec("
CREATE TABLE IF NOT EXISTS salaries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_id INTEGER NOT NULL,
    bulan INTEGER NOT NULL,
    tahun INTEGER NOT NULL,
    gaji_pokok DECIMAL(15,2) NOT NULL,
    tunjangan DECIMAL(15,2) DEFAULT 0,
    potongan DECIMAL(15,2) DEFAULT 0,
    total_gaji DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk salaries bulan September 2025
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO salaries (employee_id, bulan, tahun, gaji_pokok, tunjangan, potongan, total_gaji, created_at, updated_at) VALUES 
    (1, 9, 2025, 8000000, 1000000, 0, 9000000, '$now', '$now'),
    (2, 9, 2025, 6000000, 500000, 0, 6500000, '$now', '$now')");

echo "salaries table added successfully\n";
?>
