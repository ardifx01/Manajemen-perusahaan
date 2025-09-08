<?php
/**
 * Script untuk check dan fix data users di MySQL
 */

echo "<h2>Check Users di Database MySQL</h2>";
echo "<hr>";

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Data Users di MySQL:</h3>";
    
    // Check apakah tabel users ada
    $tables = $pdo->query("SHOW TABLES LIKE 'users'")->fetchAll();
    if (empty($tables)) {
        echo "❌ Tabel users tidak ditemukan<br>";
        
        // Buat tabel users
        $createUsers = "
        CREATE TABLE `users` (
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) UNIQUE NOT NULL,
            `email_verified_at` TIMESTAMP NULL,
            `password` VARCHAR(255) NOT NULL,
            `remember_token` VARCHAR(100) NULL,
            `created_at` TIMESTAMP NULL,
            `updated_at` TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createUsers);
        echo "✅ Tabel users berhasil dibuat<br>";
    }
    
    // Check data users
    $users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "⚠️ Tidak ada data users<br>";
        echo "<h3>Membuat Users Default:</h3>";
        
        // Hash password
        $password1 = password_hash('password123', PASSWORD_DEFAULT);
        $password2 = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Insert users default
        $insertUsers = "
        INSERT INTO users (name, email, password, created_at, updated_at) VALUES
        ('Perle User', 'perle@gmail.com', ?, NOW(), NOW()),
        ('Admin User', 'admin@gmail.com', ?, NOW(), NOW())
        ";
        
        $stmt = $pdo->prepare($insertUsers);
        $stmt->execute([$password1, $password2]);
        
        echo "✅ User 'perle@gmail.com' dengan password 'password123'<br>";
        echo "✅ User 'admin@gmail.com' dengan password 'admin123'<br>";
        
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Reset Password Users:</h3>";
        
        // Reset password untuk users yang ada
        $password1 = password_hash('password123', PASSWORD_DEFAULT);
        $password2 = password_hash('admin123', PASSWORD_DEFAULT);
        
        $pdo->prepare("UPDATE users SET password = ? WHERE email = 'perle@gmail.com'")->execute([$password1]);
        $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@gmail.com'")->execute([$password2]);
        
        echo "✅ Password reset untuk semua users<br>";
        echo "✅ perle@gmail.com / password123<br>";
        echo "✅ admin@gmail.com / admin123<br>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Users siap digunakan!</h3>";
    echo "<p>Silakan login dengan credentials di atas.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
