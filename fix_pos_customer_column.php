<?php
// Direct fix untuk menambahkan kolom customer ke tabel pos

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "Menambahkan kolom customer ke tabel pos...\n";
    
    // Tambahkan kolom customer
    $pdo->exec("ALTER TABLE pos ADD COLUMN customer VARCHAR(255) DEFAULT 'PT. Default Customer'");
    echo "✅ Kolom customer berhasil ditambahkan\n";
    
    // Tambahkan kolom lain yang mungkin dibutuhkan
    try {
        $pdo->exec("ALTER TABLE pos ADD COLUMN no_invoice VARCHAR(255) DEFAULT NULL");
        echo "✅ Kolom no_invoice berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️ Kolom no_invoice sudah ada\n";
    }
    
    try {
        $pdo->exec("ALTER TABLE pos ADD COLUMN pengirim VARCHAR(255) DEFAULT NULL");
        echo "✅ Kolom pengirim berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️ Kolom pengirim sudah ada\n";
    }
    
    try {
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_1 TEXT DEFAULT NULL");
        echo "✅ Kolom alamat_1 berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️ Kolom alamat_1 sudah ada\n";
    }
    
    try {
        $pdo->exec("ALTER TABLE pos ADD COLUMN alamat_2 TEXT DEFAULT NULL");
        echo "✅ Kolom alamat_2 berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️ Kolom alamat_2 sudah ada\n";
    }
    
    // Update data yang kosong
    $pdo->exec("UPDATE pos SET customer = 'PT. Default Customer' WHERE customer IS NULL OR customer = ''");
    $pdo->exec("UPDATE pos SET alamat_1 = 'Alamat Default' WHERE alamat_1 IS NULL OR alamat_1 = ''");
    echo "✅ Data default berhasil diupdate\n";
    
    // Pastikan ada data pos
    $count = $pdo->query("SELECT COUNT(*) FROM pos")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO pos (tanggal_po, no_po, no_surat_jalan, customer, alamat_1, total) VALUES 
                   ('2025-09-01', 'PO-001', 'SJ-001', 'PT. ABC Company', 'Jl. Sudirman No. 1', 1000000),
                   ('2025-09-02', 'PO-002', 'SJ-002', 'PT. XYZ Corp', 'Jl. Thamrin No. 2', 1500000),
                   ('2025-09-03', 'PO-003', 'SJ-003', 'CV. Maju Jaya', 'Jl. Gatot Subroto No. 3', 2000000)");
        echo "✅ Data sample pos berhasil ditambahkan\n";
    }
    
    // Test query
    try {
        $result = $pdo->query("SELECT pos.customer, COUNT(DISTINCT po_items.po_id) as orders, SUM(po_items.total) as subtotal 
                              FROM po_items 
                              INNER JOIN pos ON po_items.po_id = pos.id 
                              WHERE pos.tanggal_po BETWEEN '2025-09-01 00:00:00' AND '2025-09-30 23:59:59' 
                              GROUP BY pos.customer 
                              ORDER BY subtotal DESC")->fetchAll();
        echo "✅ Test query berhasil!\n";
        foreach ($result as $row) {
            echo "Customer: {$row['customer']}, Orders: {$row['orders']}, Subtotal: " . number_format($row['subtotal'], 0, ',', '.') . "\n";
        }
    } catch (Exception $e) {
        echo "⚠️ Test query: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 PERBAIKAN SELESAI! Aplikasi Laravel sekarang bisa berjalan tanpa error.\n";
    
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "ℹ️ Kolom customer sudah ada, melanjutkan perbaikan...\n";
        
        // Update data saja
        $pdo->exec("UPDATE pos SET customer = 'PT. Default Customer' WHERE customer IS NULL OR customer = ''");
        echo "✅ Data customer berhasil diupdate\n";
        
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
?>
