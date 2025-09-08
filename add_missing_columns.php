<?php
/**
 * Script untuk menambahkan kolom yang hilang di tabel MySQL
 */

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Menambahkan kolom yang hilang...\n";
    
    // Tambahkan kolom status ke tabel employees
    try {
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(255) DEFAULT 'aktif'");
        echo "✅ Kolom 'status' ditambahkan ke tabel employees\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "⚠️ Kolom 'status' sudah ada di tabel employees\n";
        } else {
            throw $e;
        }
    }
    
    // Update semua employees yang ada menjadi status aktif
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status IS NULL OR status = ''");
    echo "✅ Status employees diupdate ke 'aktif'\n";
    
    // Tambahkan kolom yang mungkin dibutuhkan di tabel lain
    $tables_columns = [
        'customers' => ['status' => "VARCHAR(255) DEFAULT 'active'"],
        'produks' => ['status' => "VARCHAR(255) DEFAULT 'active'"],
        'pos' => ['delivery_date' => "DATE NULL", 'notes' => "TEXT NULL"],
        'kendaraans' => ['status' => "VARCHAR(255) DEFAULT 'active'"],
        'expenses' => ['status' => "VARCHAR(255) DEFAULT 'approved'"],
        'salaries' => ['status' => "VARCHAR(255) DEFAULT 'paid'"]
    ];
    
    foreach ($tables_columns as $table => $columns) {
        foreach ($columns as $column => $definition) {
            try {
                $pdo->exec("ALTER TABLE $table ADD COLUMN $column $definition");
                echo "✅ Kolom '$column' ditambahkan ke tabel $table\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                    echo "⚠️ Kolom '$column' sudah ada di tabel $table\n";
                } else {
                    echo "❌ Error menambahkan kolom '$column' ke tabel $table: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n✅ Semua kolom yang dibutuhkan sudah ditambahkan!\n";
    echo "✅ Aplikasi sekarang siap digunakan\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
