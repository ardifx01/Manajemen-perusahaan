<?php
// Script untuk memeriksa tabel PO di database SQLite
try {
    $dbPath = __DIR__ . '/database/database.sqlite';
    
    if (!file_exists($dbPath)) {
        echo "❌ File database.sqlite tidak ditemukan di: $dbPath\n";
        exit(1);
    }
    
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Koneksi ke database SQLite berhasil\n";
    echo "📍 Path database: $dbPath\n\n";
    
    // Cek tabel pos
    echo "=== MEMERIKSA TABEL POS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='pos'");
    $posTable = $stmt->fetch();
    
    if ($posTable) {
        echo "✅ Tabel 'pos' ditemukan\n";
        
        // Cek struktur tabel pos
        $stmt = $pdo->query("PRAGMA table_info(pos)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "📋 Kolom tabel pos:\n";
        foreach ($columns as $col) {
            echo "   - {$col['name']} ({$col['type']})\n";
        }
        
        // Cek jumlah data
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM pos");
        $count = $stmt->fetch()['count'];
        echo "📊 Jumlah data PO: $count\n";
        
        if ($count > 0) {
            echo "📄 Sample data PO terbaru:\n";
            $stmt = $pdo->query("SELECT id, no_po, customer, tanggal_po, total, created_at FROM pos ORDER BY created_at DESC LIMIT 3");
            $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($samples as $sample) {
                echo "   ID: {$sample['id']}, No PO: {$sample['no_po']}, Customer: {$sample['customer']}, Total: {$sample['total']}\n";
            }
        }
    } else {
        echo "❌ Tabel 'pos' TIDAK ditemukan!\n";
    }
    
    echo "\n=== MEMERIKSA TABEL PO_ITEMS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='po_items'");
    $poItemsTable = $stmt->fetch();
    
    if ($poItemsTable) {
        echo "✅ Tabel 'po_items' ditemukan\n";
        
        // Cek struktur tabel po_items
        $stmt = $pdo->query("PRAGMA table_info(po_items)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "📋 Kolom tabel po_items:\n";
        foreach ($columns as $col) {
            echo "   - {$col['name']} ({$col['type']})\n";
        }
        
        // Cek jumlah data
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM po_items");
        $count = $stmt->fetch()['count'];
        echo "📊 Jumlah data PO Items: $count\n";
        
        if ($count > 0) {
            echo "📄 Sample data PO Items terbaru:\n";
            $stmt = $pdo->query("SELECT pi.id, pi.po_id, pi.produk_id, pi.qty, pi.total, p.nama_produk 
                                FROM po_items pi 
                                LEFT JOIN produks p ON pi.produk_id = p.id 
                                ORDER BY pi.created_at DESC LIMIT 3");
            $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($samples as $sample) {
                echo "   ID: {$sample['id']}, PO ID: {$sample['po_id']}, Produk: {$sample['nama_produk']}, Qty: {$sample['qty']}\n";
            }
        }
    } else {
        echo "❌ Tabel 'po_items' TIDAK ditemukan!\n";
    }
    
    echo "\n=== MEMERIKSA TABEL JATUH_TEMPOS ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='jatuh_tempos'");
    $jtTable = $stmt->fetch();
    
    if ($jtTable) {
        echo "✅ Tabel 'jatuh_tempos' ditemukan\n";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM jatuh_tempos");
        $count = $stmt->fetch()['count'];
        echo "📊 Jumlah data Jatuh Tempo: $count\n";
    } else {
        echo "❌ Tabel 'jatuh_tempos' TIDAK ditemukan!\n";
    }
    
    echo "\n=== SEMUA TABEL DI DATABASE ===\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "📋 $table\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
