<?php
/**
 * Script untuk memilih dan setup database MySQL
 */

echo "<h2>Pilih Database MySQL untuk Laravel</h2>";
echo "<hr>";

// Konfigurasi MySQL
$mysqlConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'username' => 'root',
    'password' => '',
];

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};charset=utf8mb4",
        $mysqlConfig['username'],
        $mysqlConfig['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>✅ Berhasil terhubung ke MySQL Server</h3>";
    
    // Tampilkan database yang sudah ada
    echo "<h3>Database yang sudah ada:</h3>";
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($databases as $db) {
        if (!in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys'])) {
            echo "<li><strong>$db</strong></li>";
        }
    }
    echo "</ul>";
    
    echo "<h3>Pilihan Database untuk Laravel:</h3>";
    
    // Form untuk memilih database
    if (isset($_POST['action'])) {
        $selectedDb = $_POST['database_name'];
        
        if ($_POST['action'] === 'create_new') {
            // Buat database baru
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$selectedDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "<div style='background:#d4edda; padding:10px; border:1px solid #c3e6cb; color:#155724; margin:10px 0;'>";
            echo "✅ Database '$selectedDb' berhasil dibuat!";
            echo "</div>";
        }
        
        // Update konfigurasi .env
        updateEnvFile($selectedDb);
        
        echo "<h3>✅ Konfigurasi Laravel berhasil diupdate!</h3>";
        echo "<p><strong>Database aktif:</strong> $selectedDb</p>";
        
        echo "<h3>Langkah Selanjutnya:</h3>";
        echo "<p><a href='setup_mysql.php?db=$selectedDb' style='background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>🚀 Mulai Migrasi Data</a></p>";
        
    } else {
        // Tampilkan form pilihan
        echo "<form method='POST' style='background:#f8f9fa; padding:20px; border:1px solid #dee2e6;'>";
        
        echo "<h4>Opsi 1: Gunakan Database yang Sudah Ada</h4>";
        if (in_array('cam_db', $databases)) {
            echo "<label><input type='radio' name='action' value='use_existing'> Gunakan database <strong>cam_db</strong> (data akan ditambahkan)</label><br>";
            echo "<input type='hidden' name='database_name' value='cam_db'><br>";
        }
        
        echo "<h4>Opsi 2: Buat Database Baru (Rekomendasi)</h4>";
        echo "<label><input type='radio' name='action' value='create_new' checked> Buat database baru:</label><br>";
        echo "<input type='text' name='database_name' value='manajemen_perusahaan' style='padding:5px; margin:5px 0; width:300px;'><br>";
        echo "<small style='color:#6c757d;'>Nama database baru untuk aplikasi Laravel</small><br><br>";
        
        echo "<button type='submit' style='background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;'>Lanjutkan Setup</button>";
        echo "</form>";
        
        echo "<div style='background:#fff3cd; padding:15px; border:1px solid #ffeaa7; color:#856404; margin:20px 0;'>";
        echo "<h4>💡 Rekomendasi:</h4>";
        echo "<ul>";
        echo "<li><strong>Database baru</strong>: Lebih aman, terorganisir, dan tidak mengganggu data existing</li>";
        echo "<li><strong>Database existing</strong>: Data akan digabung, mungkin ada konflik nama tabel</li>";
        echo "</ul>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background:#f8d7da; padding:10px; border:1px solid #f5c6cb; color:#721c24;'>";
    echo "❌ Error: " . $e->getMessage();
    echo "</div>";
    
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Pastikan MySQL Server sudah berjalan</li>";
    echo "<li>Cek username dan password MySQL</li>";
    echo "<li>Pastikan port 3306 tidak diblokir</li>";
    echo "</ul>";
}

function updateEnvFile($databaseName) {
    $envFile = __DIR__ . '/.env';
    $envContent = '';
    
    // Baca .env yang ada atau buat baru
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
    } else {
        // Template .env baru
        $envContent = file_get_contents(__DIR__ . '/.env.example');
    }
    
    // Update konfigurasi database
    $envContent = preg_replace('/DB_CONNECTION=.+/', 'DB_CONNECTION=mysql', $envContent);
    $envContent = preg_replace('/DB_HOST=.+/', 'DB_HOST=127.0.0.1', $envContent);
    $envContent = preg_replace('/DB_PORT=.+/', 'DB_PORT=3306', $envContent);
    $envContent = preg_replace('/DB_DATABASE=.+/', "DB_DATABASE=$databaseName", $envContent);
    $envContent = preg_replace('/DB_USERNAME=.+/', 'DB_USERNAME=root', $envContent);
    $envContent = preg_replace('/DB_PASSWORD=.+/', 'DB_PASSWORD=', $envContent);
    
    // Hapus komentar pada konfigurasi MySQL
    $envContent = str_replace('# DB_HOST=', 'DB_HOST=', $envContent);
    $envContent = str_replace('# DB_PORT=', 'DB_PORT=', $envContent);
    $envContent = str_replace('# DB_DATABASE=', 'DB_DATABASE=', $envContent);
    $envContent = str_replace('# DB_USERNAME=', 'DB_USERNAME=', $envContent);
    $envContent = str_replace('# DB_PASSWORD=', 'DB_PASSWORD=', $envContent);
    
    file_put_contents($envFile, $envContent);
}
?>
