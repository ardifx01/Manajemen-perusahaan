<?php
// ADD PRODUK_ID COLUMN - Tambah kolom yang missing ke tabel pos
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tambah kolom produk_id ke tabel pos
$pdo->exec("ALTER TABLE pos ADD COLUMN produk_id INTEGER NULL");

// Update data existing dengan produk_id default (1, 2, 3)
$pdo->exec("UPDATE pos SET produk_id = 1 WHERE id = 1");
$pdo->exec("UPDATE pos SET produk_id = 2 WHERE id = 2");
$pdo->exec("UPDATE pos SET produk_id = 3 WHERE id >= 3");

echo "produk_id column added to pos table successfully\n";
?>
