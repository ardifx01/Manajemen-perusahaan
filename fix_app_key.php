<?php
/**
 * Script untuk memperbaiki APP_KEY di file .env
 */

echo "<h2>Fix APP_KEY Laravel</h2>";
echo "<hr>";

$envFile = __DIR__ . '/.env';

try {
    // Generate APP_KEY baru
    $key = 'base64:' . base64_encode(random_bytes(32));
    
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        
        // Update APP_KEY
        if (preg_match('/APP_KEY=/', $envContent)) {
            $envContent = preg_replace('/APP_KEY=.*/', "APP_KEY=$key", $envContent);
        } else {
            $envContent = "APP_KEY=$key\n" . $envContent;
        }
        
        file_put_contents($envFile, $envContent);
        echo "✅ APP_KEY berhasil diupdate di file .env<br>";
        echo "✅ Key baru: $key<br>";
        
    } else {
        echo "❌ File .env tidak ditemukan<br>";
    }
    
    echo "<h3>Langkah Selanjutnya:</h3>";
    echo "<p>Jalankan perintah berikut:</p>";
    echo "<pre style='background:#f5f5f5; padding:10px; border:1px solid #ddd;'>";
    echo "php artisan config:cache\n";
    echo "php artisan serve\n";
    echo "</pre>";
    
    echo "<hr>";
    echo "<h3>✅ APP_KEY berhasil diperbaiki!</h3>";
    echo "<p>Sekarang aplikasi Laravel siap digunakan.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error Fix APP_KEY</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
