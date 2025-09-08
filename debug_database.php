<?php

echo "=== DEBUGGING DATABASE CONNECTION ===" . PHP_EOL;

try {
    // Direct SQLite connection
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful" . PHP_EOL;
    
    // Check if pos table exists
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database: " . implode(', ', $tables) . PHP_EOL;
    
    if (in_array('pos', $tables)) {
        $count = $db->query("SELECT COUNT(*) FROM pos")->fetchColumn();
        echo "✅ pos table exists with $count records" . PHP_EOL;
        
        if ($count > 0) {
            echo PHP_EOL . "Recent PO data:" . PHP_EOL;
            $stmt = $db->query("SELECT id, no_po, customer, tanggal_po, total, created_at FROM pos ORDER BY created_at DESC LIMIT 5");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo sprintf("ID: %s | NO_PO: %s | Customer: %s | Date: %s | Total: %s | Created: %s", 
                    $row['id'], $row['no_po'], $row['customer'], $row['tanggal_po'], $row['total'], $row['created_at']) . PHP_EOL;
            }
        }
    } else {
        echo "❌ pos table does not exist!" . PHP_EOL;
    }
    
    if (in_array('po_items', $tables)) {
        $itemCount = $db->query("SELECT COUNT(*) FROM po_items")->fetchColumn();
        echo "✅ po_items table exists with $itemCount records" . PHP_EOL;
    } else {
        echo "❌ po_items table does not exist!" . PHP_EOL;
    }
    
    // Check other required tables
    $requiredTables = ['produks', 'customers', 'kendaraans'];
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            $count = $db->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "✅ $table table exists with $count records" . PHP_EOL;
        } else {
            echo "❌ $table table does not exist!" . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . PHP_EOL;
}
