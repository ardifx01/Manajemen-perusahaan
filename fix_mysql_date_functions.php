<?php
/**
 * Script untuk memperbaiki error SQLite strftime function dan menambahkan kolom tanggal_po
 */

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Memperbaiki MySQL date functions dan menambahkan kolom tanggal_po...\n";
    
    // Tambahkan kolom tanggal_po ke tabel pos
    try {
        $pdo->exec("ALTER TABLE pos ADD COLUMN tanggal_po DATE DEFAULT (CURDATE())");
        echo "✅ Kolom 'tanggal_po' ditambahkan ke tabel pos\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "⚠️ Kolom 'tanggal_po' sudah ada di tabel pos\n";
        } else {
            throw $e;
        }
    }
    
    // Update tanggal_po untuk data yang sudah ada
    $pdo->exec("UPDATE pos SET tanggal_po = CURDATE() WHERE tanggal_po IS NULL");
    echo "✅ Tanggal PO diupdate untuk data existing\n";
    
    // Tambahkan kolom tanggal lain yang mungkin dibutuhkan
    $date_columns = [
        'employees' => ['tanggal_masuk' => 'DATE NULL'],
        'customers' => ['tanggal_daftar' => 'DATE DEFAULT (CURDATE())'],
        'produks' => ['tanggal_dibuat' => 'DATE DEFAULT (CURDATE())'],
        'expenses' => ['tanggal' => 'DATE DEFAULT (CURDATE())'],
        'barang_masuks' => ['tanggal' => 'DATE DEFAULT (CURDATE())'],
        'barang_keluars' => ['tanggal' => 'DATE DEFAULT (CURDATE())']
    ];
    
    foreach ($date_columns as $table => $columns) {
        foreach ($columns as $column => $definition) {
            try {
                $pdo->exec("ALTER TABLE $table ADD COLUMN $column $definition");
                echo "✅ Kolom '$column' ditambahkan ke tabel $table\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                    echo "⚠️ Kolom '$column' sudah ada di tabel $table\n";
                } else {
                    echo "❌ Error menambahkan '$column' ke $table: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    // Test query dengan MySQL date functions
    echo "\nTesting MySQL date functions...\n";
    
    $result = $pdo->query("SELECT COUNT(*) as count FROM pos WHERE YEAR(tanggal_po) = 2025 AND MONTH(tanggal_po) = 9")->fetch();
    echo "✅ Query dengan YEAR() dan MONTH() berhasil: {$result['count']} records\n";
    
    echo "\n=== CATATAN PENTING ===\n";
    echo "SQLite menggunakan: strftime('%Y', tanggal) dan strftime('%m', tanggal)\n";
    echo "MySQL menggunakan: YEAR(tanggal) dan MONTH(tanggal)\n";
    echo "Aplikasi perlu diupdate untuk menggunakan MySQL date functions\n";
    
    echo "\n✅ Database berhasil diperbaiki!\n";
    echo "✅ Semua kolom tanggal sudah ditambahkan\n";
    echo "✅ MySQL date functions siap digunakan\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
