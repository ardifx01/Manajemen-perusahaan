<?php

require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "=== CHECKING PO DATA IN DATABASE ===" . PHP_EOL;
    
    // Check total PO records
    $totalPOs = App\Models\PO::count();
    echo "Total PO records in database: " . $totalPOs . PHP_EOL;
    
    if ($totalPOs > 0) {
        echo PHP_EOL . "Recent PO data:" . PHP_EOL;
        $recent = App\Models\PO::orderBy('created_at', 'desc')->take(5)->get(['id', 'no_po', 'customer', 'tanggal_po', 'total', 'created_at']);
        foreach ($recent as $po) {
            echo sprintf("ID: %s | NO_PO: %s | Customer: %s | Date: %s | Total: %s | Created: %s", 
                $po->id, $po->no_po, $po->customer, $po->tanggal_po, $po->total, $po->created_at) . PHP_EOL;
        }
    } else {
        echo "❌ NO PO DATA FOUND IN DATABASE!" . PHP_EOL;
    }
    
    // Check PO Items
    $totalItems = App\Models\POItem::count();
    echo PHP_EOL . "Total PO Items records: " . $totalItems . PHP_EOL;
    
    if ($totalItems > 0) {
        echo PHP_EOL . "Recent PO Items:" . PHP_EOL;
        $items = App\Models\POItem::with('produk')->orderBy('created_at', 'desc')->take(3)->get();
        foreach ($items as $item) {
            $produkName = $item->produk ? $item->produk->nama_produk : 'N/A';
            echo sprintf("PO_ID: %s | Produk: %s | Qty: %s %s | Total: %s", 
                $item->po_id, $produkName, $item->qty, $item->qty_jenis, $item->total) . PHP_EOL;
        }
    }
    
    // Check if tables exist
    echo PHP_EOL . "=== CHECKING TABLE STRUCTURE ===" . PHP_EOL;
    
    $tables = ['pos', 'po_items', 'produks', 'customers', 'kendaraans'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "✅ Table '$table' exists with $count records" . PHP_EOL;
        } catch (Exception $e) {
            echo "❌ Table '$table' error: " . $e->getMessage() . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}
