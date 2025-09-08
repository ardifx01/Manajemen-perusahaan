<?php
/**
 * Script untuk update file .env ke konfigurasi MySQL
 */

echo "<h2>Update Konfigurasi Laravel ke MySQL</h2>";
echo "<hr>";

$envFile = __DIR__ . '/.env';
$envMysqlFile = __DIR__ . '/.env.mysql';

try {
    // Backup .env yang lama
    if (file_exists($envFile)) {
        copy($envFile, $envFile . '.sqlite.backup');
        echo "✅ Backup file .env lama ke .env.sqlite.backup<br>";
    }
    
    // Copy konfigurasi MySQL
    if (file_exists($envMysqlFile)) {
        copy($envMysqlFile, $envFile);
        echo "✅ File .env berhasil diupdate dengan konfigurasi MySQL<br>";
    } else {
        echo "❌ File .env.mysql tidak ditemukan<br>";
        exit;
    }
    
    // Baca APP_KEY dari backup jika ada
    if (file_exists($envFile . '.sqlite.backup')) {
        $backupContent = file_get_contents($envFile . '.sqlite.backup');
        if (preg_match('/APP_KEY=(.+)/', $backupContent, $matches)) {
            $appKey = trim($matches[1]);
            
            // Update APP_KEY di file .env yang baru
            $envContent = file_get_contents($envFile);
            $envContent = preg_replace('/APP_KEY=.+/', "APP_KEY=$appKey", $envContent);
            file_put_contents($envFile, $envContent);
            echo "✅ APP_KEY berhasil dipindahkan dari konfigurasi lama<br>";
        }
    }
    
    echo "<h3>Konfigurasi Database MySQL Aktif:</h3>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ddd;'>";
    echo "DB_CONNECTION=mysql\n";
    echo "DB_HOST=127.0.0.1\n";
    echo "DB_PORT=3306\n";
    echo "DB_DATABASE=manajemen_perusahaan\n";
    echo "DB_USERNAME=root\n";
    echo "DB_PASSWORD=\n";
    echo "</pre>";
    
    echo "<h3>Langkah Selanjutnya:</h3>";
    echo "<ol>";
    echo "<li>Jalankan: <code>php artisan config:cache</code></li>";
    echo "<li>Jalankan: <code>php artisan migrate:status</code></li>";
    echo "<li>Test aplikasi: <code>php artisan serve</code></li>";
    echo "</ol>";
    
    echo "<hr>";
    echo "<h3>✅ Konfigurasi Laravel berhasil diubah ke MySQL!</h3>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error Update Konfigurasi</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
