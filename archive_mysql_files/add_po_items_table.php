<?php
// ADD PO_ITEMS TABLE - Tambah tabel yang missing
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah tabel po_items
$pdo->exec("
CREATE TABLE IF NOT EXISTS po_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    po_id INTEGER NOT NULL,
    produk VARCHAR(255) NOT NULL,
    qty INTEGER NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// Insert sample data untuk po_items
$now = date('Y-m-d H:i:s');
$pdo->exec("INSERT INTO po_items (po_id, produk, qty, harga, total, created_at, updated_at) VALUES 
    (1, 'Produk A', 10, 100000, 1000000, '$now', '$now'),
    (1, 'Produk B', 5, 200000, 1000000, '$now', '$now')");

echo "po_items table added successfully\n";
?>
