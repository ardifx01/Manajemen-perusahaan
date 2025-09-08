<?php
/**
 * Script untuk memperbaiki struktur tabel sessions
 */

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Memperbaiki tabel sessions...\n";
    
    // Drop tabel sessions yang lama
    $pdo->exec("DROP TABLE IF EXISTS `sessions`");
    
    // Buat tabel sessions dengan struktur yang benar
    $pdo->exec("
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    echo "✅ Tabel sessions berhasil diperbaiki dengan struktur yang benar\n";
    echo "✅ Kolom id sekarang VARCHAR(255)\n";
    echo "✅ Aplikasi siap digunakan\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
