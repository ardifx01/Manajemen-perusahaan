<?php
/**
 * Script untuk memperbaiki APP_KEY dan sessions table
 */

echo "<h2>Fix APP_KEY dan Sessions Table</h2>";
echo "<hr>";

$envFile = __DIR__ . '/.env';

try {
    // Step 1: Fix APP_KEY
    echo "<h3>Step 1: Memperbaiki APP_KEY</h3>";
    
    // Generate APP_KEY baru
    $newKey = 'base64:' . base64_encode(random_bytes(32));
    
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        
        // Ganti APP_KEY yang ada atau tambahkan jika tidak ada
        if (strpos($envContent, 'APP_KEY=') !== false) {
            $envContent = preg_replace('/APP_KEY=.*/', "APP_KEY=$newKey", $envContent);
        } else {
            $envContent = "APP_KEY=$newKey\n" . $envContent;
        }
        
        file_put_contents($envFile, $envContent);
        echo "✅ APP_KEY berhasil diupdate: $newKey<br>";
    } else {
        echo "❌ File .env tidak ditemukan<br>";
    }
    
    // Step 2: Fix Sessions Table
    echo "<h3>Step 2: Memperbaiki Sessions Table</h3>";
    
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop dan recreate sessions table dengan struktur yang benar
    $pdo->exec("DROP TABLE IF EXISTS `sessions`");
    
    $createSessionsTable = "
    CREATE TABLE `sessions` (
        `id` VARCHAR(255) NOT NULL,
        `user_id` BIGINT UNSIGNED NULL,
        `ip_address` VARCHAR(45) NULL,
        `user_agent` TEXT NULL,
        `payload` LONGTEXT NOT NULL,
        `last_activity` INT NOT NULL,
        PRIMARY KEY (`id`),
        INDEX `sessions_user_id_index` (`user_id`),
        INDEX `sessions_last_activity_index` (`last_activity`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($createSessionsTable);
    echo "✅ Sessions table berhasil dibuat ulang dengan struktur yang benar<br>";
    
    // Step 3: Clear cache dan sessions
    echo "<h3>Step 3: Clear Cache</h3>";
    echo "✅ Jalankan perintah berikut di terminal:<br>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ddd;'>";
    echo "php artisan config:clear\n";
    echo "php artisan session:table\n";
    echo "php artisan config:cache\n";
    echo "php artisan serve\n";
    echo "</pre>";
    
    echo "<hr>";
    echo "<h3>✅ Perbaikan Selesai!</h3>";
    echo "<p>APP_KEY dan sessions table sudah diperbaiki. Aplikasi siap digunakan.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
