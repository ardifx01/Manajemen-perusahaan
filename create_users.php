<?php
/**
 * Script untuk membuat users di database MySQL
 */

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hapus users lama jika ada
    $pdo->exec("DELETE FROM users");
    
    // Hash password
    $password1 = password_hash('password123', PASSWORD_DEFAULT);
    $password2 = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Insert users baru
    $sql = "INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute(['Perle User', 'perle@gmail.com', $password1]);
    $stmt->execute(['Admin User', 'admin@gmail.com', $password2]);
    
    echo "Users berhasil dibuat:\n";
    echo "- perle@gmail.com / password123\n";
    echo "- admin@gmail.com / admin123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
