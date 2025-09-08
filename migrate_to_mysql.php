<?php
/**
 * Script untuk migrasi data dari SQLite ke MySQL
 * Jalankan script ini setelah mengubah konfigurasi database ke MySQL
 */

// Konfigurasi database
$sqliteDb = __DIR__ . '/database/database.sqlite';
$mysqlConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'manajemen_perusahaan', // Ganti dengan nama database MySQL Anda
    'username' => 'root',
    'password' => '', // Ganti dengan password MySQL Anda
];

echo "=== MIGRASI DATABASE SQLITE KE MYSQL ===\n\n";

try {
    // Koneksi ke SQLite
    echo "1. Menghubungkan ke SQLite...\n";
    $sqlitePdo = new PDO("sqlite:$sqliteDb");
    $sqlitePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Berhasil terhubung ke SQLite\n\n";

    // Koneksi ke MySQL
    echo "2. Menghubungkan ke MySQL...\n";
    $mysqlPdo = new PDO(
        "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};charset=utf8mb4",
        $mysqlConfig['username'],
        $mysqlConfig['password']
    );
    $mysqlPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Berhasil terhubung ke MySQL\n\n";

    // Buat database jika belum ada
    echo "3. Membuat database MySQL...\n";
    $mysqlPdo->exec("CREATE DATABASE IF NOT EXISTS `{$mysqlConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $mysqlPdo->exec("USE `{$mysqlConfig['database']}`");
    echo "   ✓ Database '{$mysqlConfig['database']}' siap digunakan\n\n";

    // Dapatkan daftar tabel dari SQLite
    echo "4. Mengambil daftar tabel dari SQLite...\n";
    $tablesQuery = $sqlitePdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
    
    echo "   Ditemukan " . count($tables) . " tabel:\n";
    foreach ($tables as $table) {
        echo "   - $table\n";
    }
    echo "\n";

    // Migrasi setiap tabel
    echo "5. Memulai migrasi data...\n";
    foreach ($tables as $table) {
        echo "   Migrasi tabel: $table\n";
        
        // Ambil struktur tabel dari SQLite
        $createTableQuery = $sqlitePdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'");
        $createTableSql = $createTableQuery->fetchColumn();
        
        if ($createTableSql) {
            // Konversi SQL SQLite ke MySQL
            $mysqlCreateSql = convertSqliteToMysql($createTableSql, $table);
            
            // Drop table jika sudah ada
            $mysqlPdo->exec("DROP TABLE IF EXISTS `$table`");
            
            // Buat tabel di MySQL
            $mysqlPdo->exec($mysqlCreateSql);
            
            // Copy data
            $dataQuery = $sqlitePdo->query("SELECT * FROM `$table`");
            $data = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($data)) {
                // Ambil nama kolom
                $columns = array_keys($data[0]);
                $columnsList = '`' . implode('`, `', $columns) . '`';
                $placeholders = ':' . implode(', :', $columns);
                
                $insertSql = "INSERT INTO `$table` ($columnsList) VALUES ($placeholders)";
                $insertStmt = $mysqlPdo->prepare($insertSql);
                
                foreach ($data as $row) {
                    $insertStmt->execute($row);
                }
                
                echo "     ✓ " . count($data) . " baris data berhasil dimigrasikan\n";
            } else {
                echo "     ✓ Tabel kosong, hanya struktur yang dibuat\n";
            }
        }
    }

    echo "\n=== MIGRASI SELESAI ===\n";
    echo "✓ Semua data berhasil dimigrasikan ke MySQL\n";
    echo "✓ Database: {$mysqlConfig['database']}\n";
    echo "✓ Host: {$mysqlConfig['host']}:{$mysqlConfig['port']}\n\n";
    
    echo "LANGKAH SELANJUTNYA:\n";
    echo "1. Update file .env dengan konfigurasi MySQL\n";
    echo "2. Jalankan: php artisan config:cache\n";
    echo "3. Test aplikasi dengan database MySQL\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

function convertSqliteToMysql($sqliteSql, $tableName) {
    // Konversi dasar SQLite ke MySQL
    $mysqlSql = $sqliteSql;
    
    // Ganti tipe data SQLite ke MySQL
    $mysqlSql = str_replace('INTEGER PRIMARY KEY AUTOINCREMENT', 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY', $mysqlSql);
    $mysqlSql = str_replace('INTEGER PRIMARY KEY', 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY', $mysqlSql);
    $mysqlSql = str_replace('INTEGER', 'BIGINT', $mysqlSql);
    $mysqlSql = str_replace('TEXT', 'TEXT', $mysqlSql);
    $mysqlSql = str_replace('REAL', 'DOUBLE', $mysqlSql);
    $mysqlSql = str_replace('BLOB', 'LONGBLOB', $mysqlSql);
    
    // Tambahkan ENGINE dan CHARSET
    $mysqlSql = rtrim($mysqlSql, ';') . ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    
    return $mysqlSql;
}
?>
