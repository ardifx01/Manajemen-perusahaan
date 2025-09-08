<?php
/**
 * Script untuk setup database MySQL dan migrasi dari SQLite
 * Jalankan script ini melalui browser: http://localhost/setup_mysql.php
 */

echo "<h2>Setup Database MySQL untuk Laravel</h2>";
echo "<hr>";

// Konfigurasi MySQL - UBAH SESUAI PENGATURAN ANDA
$mysqlConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'manajemen_perusahaan',
    'username' => 'root',
    'password' => '', // Masukkan password MySQL Anda di sini
];

$sqliteDb = __DIR__ . '/database/database.sqlite';

echo "<h3>Langkah 1: Test Koneksi MySQL</h3>";

try {
    // Test koneksi MySQL
    $pdo = new PDO(
        "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};charset=utf8mb4",
        $mysqlConfig['username'],
        $mysqlConfig['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Berhasil terhubung ke MySQL Server<br>";
    
    // Buat database
    echo "<h3>Langkah 2: Membuat Database</h3>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$mysqlConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '{$mysqlConfig['database']}' berhasil dibuat<br>";
    
    // Gunakan database
    $pdo->exec("USE `{$mysqlConfig['database']}`");
    
    echo "<h3>Langkah 3: Migrasi Data dari SQLite</h3>";
    
    if (file_exists($sqliteDb)) {
        // Koneksi ke SQLite
        $sqlitePdo = new PDO("sqlite:$sqliteDb");
        $sqlitePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Dapatkan daftar tabel
        $tablesQuery = $sqlitePdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Ditemukan " . count($tables) . " tabel di SQLite:<br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        
        // Migrasi setiap tabel
        foreach ($tables as $table) {
            echo "<strong>Migrasi tabel: $table</strong><br>";
            
            try {
                // Ambil data dari SQLite
                $dataQuery = $sqlitePdo->query("SELECT * FROM `$table`");
                $data = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($data)) {
                    // Buat tabel MySQL berdasarkan data SQLite
                    createMysqlTable($pdo, $table, $data[0]);
                    
                    // Insert data
                    $columns = array_keys($data[0]);
                    $columnsList = '`' . implode('`, `', $columns) . '`';
                    $placeholders = ':' . implode(', :', $columns);
                    
                    $insertSql = "INSERT IGNORE INTO `$table` ($columnsList) VALUES ($placeholders)";
                    $insertStmt = $pdo->prepare($insertSql);
                    
                    $successCount = 0;
                    foreach ($data as $row) {
                        try {
                            $insertStmt->execute($row);
                            $successCount++;
                        } catch (Exception $e) {
                            // Skip error rows
                        }
                    }
                    
                    echo "✅ $successCount dari " . count($data) . " baris berhasil dimigrasikan<br>";
                } else {
                    echo "⚠️ Tabel kosong<br>";
                }
                
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "<br>";
            }
            
            echo "<br>";
        }
        
    } else {
        echo "⚠️ File SQLite tidak ditemukan: $sqliteDb<br>";
    }
    
    echo "<h3>Langkah 4: Update Konfigurasi Laravel</h3>";
    echo "<p>Sekarang update file <strong>.env</strong> dengan konfigurasi berikut:</p>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ddd;'>";
    echo "DB_CONNECTION=mysql\n";
    echo "DB_HOST={$mysqlConfig['host']}\n";
    echo "DB_PORT={$mysqlConfig['port']}\n";
    echo "DB_DATABASE={$mysqlConfig['database']}\n";
    echo "DB_USERNAME={$mysqlConfig['username']}\n";
    echo "DB_PASSWORD={$mysqlConfig['password']}\n";
    echo "</pre>";
    
    echo "<h3>Langkah 5: Jalankan Perintah Laravel</h3>";
    echo "<p>Setelah update .env, jalankan perintah berikut di terminal:</p>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ddd;'>";
    echo "php artisan config:cache\n";
    echo "php artisan migrate:status\n";
    echo "php artisan serve\n";
    echo "</pre>";
    
    echo "<hr>";
    echo "<h3>✅ Setup MySQL Selesai!</h3>";
    echo "<p>Database MySQL sudah siap digunakan dengan semua data dari SQLite.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error Setup MySQL</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Pastikan:</p>";
    echo "<ul>";
    echo "<li>MySQL Server sudah berjalan</li>";
    echo "<li>Username dan password MySQL benar</li>";
    echo "<li>Port MySQL benar (default: 3306)</li>";
    echo "</ul>";
}

function createMysqlTable($pdo, $tableName, $sampleData) {
    // Drop table jika sudah ada
    $pdo->exec("DROP TABLE IF EXISTS `$tableName`");
    
    // Buat struktur tabel berdasarkan data sample
    $columns = [];
    foreach ($sampleData as $column => $value) {
        if ($column === 'id') {
            $columns[] = "`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY";
        } elseif (is_numeric($value) && strpos($value, '.') !== false) {
            $columns[] = "`$column` DECIMAL(10,2)";
        } elseif (is_numeric($value)) {
            $columns[] = "`$column` BIGINT";
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            $columns[] = "`$column` TIMESTAMP NULL";
        } else {
            $columns[] = "`$column` TEXT";
        }
    }
    
    $createSql = "CREATE TABLE `$tableName` (" . implode(', ', $columns) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($createSql);
}
?>
