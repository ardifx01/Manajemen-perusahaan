<?php
// Fix untuk menambahkan kolom customer yang hilang di tabel pos

echo "<h2>🔧 Fix Tabel POS - Kolom Customer</h2>";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "<p>✅ Koneksi database berhasil</p>";

    // Cek struktur tabel pos
    $columns = $pdo->query("SHOW COLUMNS FROM pos")->fetchAll();
    $hasCustomer = false;
    $hasNoInvoice = false;
    $hasPengirim = false;
    $hasAlamat1 = false;
    $hasAlamat2 = false;
    
    echo "<h3>📋 Struktur Tabel POS Saat Ini:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
        
        if ($col['Field'] === 'customer') $hasCustomer = true;
        if ($col['Field'] === 'no_invoice') $hasNoInvoice = true;
        if ($col['Field'] === 'pengirim') $hasPengirim = true;
        if ($col['Field'] === 'alamat_1') $hasAlamat1 = true;
        if ($col['Field'] === 'alamat_2') $hasAlamat2 = true;
    }
    echo "</table>";

    echo "<h3>🔍 Status Kolom yang Dibutuhkan:</h3>";
    echo "<ul>";
    echo "<li>customer: " . ($hasCustomer ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "<li>no_invoice: " . ($hasNoInvoice ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "<li>pengirim: " . ($hasPengirim ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "<li>alamat_1: " . ($hasAlamat1 ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "<li>alamat_2: " . ($hasAlamat2 ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "</ul>";

    // Tambahkan kolom yang hilang
    if (!$hasCustomer) {
        $pdo->exec("ALTER TABLE pos ADD COLUMN customer VARCHAR(255) DEFAULT 'PT. Default Customer'");
        echo "<p style='color: green;'>✅ Kolom customer berhasil ditambahkan</p>";
    }

    if (!$hasNoInvoice) {
        $pdo->exec("ALTER TABLE pos ADD COLUMN no_invoice VARCHAR(255) DEFAULT NULL");
        echo "<p style='color: green;'>✅ Kolom no_invoice berhasil ditambahkan</p>";
    }

    if (!$hasPengirim) {
        $pdo->exec("ALTER TABLE pos ADD COLUMN pengirim VARCHAR(255) DEFAULT NULL");
        echo "<p style='color: green;'>✅ Kolom pengirim berhasil ditambahkan</p>";
    }

    if (!$hasAlamat1) {
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_1 TEXT DEFAULT NULL");
        echo "<p style='color: green;'>✅ Kolom alamat_1 berhasil ditambahkan</p>";
    }

    if (!$hasAlamat2) {
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_2 TEXT DEFAULT NULL");
        echo "<p style='color: green;'>✅ Kolom alamat_2 berhasil ditambahkan</p>";
    }

    // Update data kosong dengan nilai default
    $pdo->exec("UPDATE pos SET customer = 'PT. Default Customer' WHERE customer IS NULL OR customer = ''");
    $pdo->exec("UPDATE pos SET alamat_1 = 'Alamat Default' WHERE alamat_1 IS NULL OR alamat_1 = ''");
    echo "<p style='color: green;'>✅ Data default berhasil diupdate</p>";

    // Cek jumlah data pos
    $count = $pdo->query("SELECT COUNT(*) FROM pos")->fetchColumn();
    echo "<p>📊 Jumlah data PO: $count</p>";

    if ($count == 0) {
        // Tambahkan data sample PO
        $pdo->exec("INSERT INTO pos (tanggal_po, no_po, no_surat_jalan, customer, alamat_1, total) VALUES 
                   ('2025-09-01', 'PO-001', 'SJ-001', 'PT. ABC Company', 'Jl. Sudirman No. 1', 1000000),
                   ('2025-09-02', 'PO-002', 'SJ-002', 'PT. XYZ Corp', 'Jl. Thamrin No. 2', 1500000),
                   ('2025-09-03', 'PO-003', 'SJ-003', 'CV. Maju Jaya', 'Jl. Gatot Subroto No. 3', 2000000)");
        echo "<p style='color: green;'>✅ Data sample PO berhasil ditambahkan</p>";
    }

    // Test query yang error sebelumnya
    echo "<h3>🧪 Test Query:</h3>";
    try {
        $result = $pdo->query("SELECT pos.customer, COUNT(DISTINCT po_items.po_id) as orders, SUM(po_items.total) as subtotal 
                              FROM po_items 
                              INNER JOIN pos ON po_items.po_id = pos.id 
                              WHERE pos.tanggal_po BETWEEN '2025-09-01 00:00:00' AND '2025-09-30 23:59:59' 
                              GROUP BY pos.customer 
                              ORDER BY subtotal DESC")->fetchAll();
        
        echo "<p style='color: green;'>✅ Query berhasil dijalankan!</p>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Customer</th><th>Orders</th><th>Subtotal</th></tr>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>{$row['customer']}</td>";
            echo "<td>{$row['orders']}</td>";
            echo "<td>Rp " . number_format($row['subtotal'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ Query test: " . $e->getMessage() . "</p>";
        echo "<p>Mungkin tabel po_items belum ada data atau strukturnya berbeda.</p>";
    }

    // Tampilkan data pos terbaru
    echo "<h3>📋 Data POS Terbaru:</h3>";
    $posData = $pdo->query("SELECT * FROM pos ORDER BY id DESC LIMIT 5")->fetchAll();
    if (!empty($posData)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Tanggal PO</th><th>No PO</th><th>Customer</th><th>Total</th></tr>";
        foreach ($posData as $pos) {
            echo "<tr>";
            echo "<td>{$pos['id']}</td>";
            echo "<td>{$pos['tanggal_po']}</td>";
            echo "<td>{$pos['no_po']}</td>";
            echo "<td>{$pos['customer']}</td>";
            echo "<td>Rp " . number_format($pos['total'] ?? 0, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada data POS.</p>";
    }

    echo "<h3 style='color: green;'>🎉 PERBAIKAN TABEL POS SELESAI!</h3>";
    echo "<p>Kolom customer dan kolom lainnya sudah ditambahkan ke tabel pos.</p>";
    echo "<p><strong>Langkah selanjutnya:</strong> Refresh aplikasi Laravel Anda.</p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
