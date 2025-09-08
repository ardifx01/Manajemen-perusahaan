<?php
// Direct fix menggunakan mysqli untuk kolom customer di tabel pos

$mysqli = new mysqli('127.0.0.1', 'root', '', 'manajemen_perusahaan');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Cek apakah kolom customer ada
$result = $mysqli->query("SHOW COLUMNS FROM pos LIKE 'customer'");
if ($result->num_rows == 0) {
    // Tambahkan kolom customer
    $mysqli->query("ALTER TABLE pos ADD COLUMN customer VARCHAR(255) DEFAULT 'PT. Default Customer'");
    echo "✅ Kolom customer ditambahkan\n";
} else {
    echo "ℹ️ Kolom customer sudah ada\n";
}

// Tambahkan kolom lain yang dibutuhkan
$columns = ['no_invoice', 'pengirim', 'alamat_1', 'alamat_2'];
foreach ($columns as $col) {
    $result = $mysqli->query("SHOW COLUMNS FROM pos LIKE '$col'");
    if ($result->num_rows == 0) {
        if ($col == 'alamat_1' || $col == 'alamat_2') {
            $mysqli->query("ALTER TABLE pos ADD COLUMN $col TEXT DEFAULT NULL");
        } else {
            $mysqli->query("ALTER TABLE pos ADD COLUMN $col VARCHAR(255) DEFAULT NULL");
        }
        echo "✅ Kolom $col ditambahkan\n";
    }
}

// Update data kosong
$mysqli->query("UPDATE pos SET customer = 'PT. Default Customer' WHERE customer IS NULL OR customer = ''");
$mysqli->query("UPDATE pos SET alamat_1 = 'Alamat Default' WHERE alamat_1 IS NULL OR alamat_1 = ''");

// Cek jumlah data
$result = $mysqli->query("SELECT COUNT(*) as count FROM pos");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $mysqli->query("INSERT INTO pos (tanggal_po, no_po, no_surat_jalan, customer, alamat_1, total) VALUES 
                   ('2025-09-01', 'PO-001', 'SJ-001', 'PT. ABC Company', 'Jl. Sudirman No. 1', 1000000),
                   ('2025-09-02', 'PO-002', 'SJ-002', 'PT. XYZ Corp', 'Jl. Thamrin No. 2', 1500000),
                   ('2025-09-03', 'PO-003', 'SJ-003', 'CV. Maju Jaya', 'Jl. Gatot Subroto No. 3', 2000000)");
    echo "✅ Data sample ditambahkan\n";
}

// Test query yang error sebelumnya
try {
    $result = $mysqli->query("SELECT pos.customer, COUNT(DISTINCT po_items.po_id) as orders, SUM(po_items.total) as subtotal 
                             FROM po_items 
                             INNER JOIN pos ON po_items.po_id = pos.id 
                             WHERE pos.tanggal_po BETWEEN '2025-09-01 00:00:00' AND '2025-09-30 23:59:59' 
                             GROUP BY pos.customer 
                             ORDER BY subtotal DESC");
    echo "✅ Test query berhasil!\n";
    while ($row = $result->fetch_assoc()) {
        echo "Customer: {$row['customer']}, Orders: {$row['orders']}, Subtotal: " . number_format($row['subtotal'], 0, ',', '.') . "\n";
    }
} catch (Exception $e) {
    echo "⚠️ Test query: " . $e->getMessage() . "\n";
}

$mysqli->close();
echo "🎉 PERBAIKAN SELESAI!\n";
?>
