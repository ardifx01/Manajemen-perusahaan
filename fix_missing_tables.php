<?php
/**
 * Script untuk membuat ulang tabel yang hilang dari migrasi SQLite
 */

echo "<h2>Fix Missing Tables di MySQL</h2>";
echo "<hr>";

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Membuat tabel yang hilang...</h3>";
    
    // Tabel employees
    $pdo->exec("DROP TABLE IF EXISTS `employees`");
    $pdo->exec("
    CREATE TABLE `employees` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) UNIQUE,
        `position` VARCHAR(255),
        `salary` DECIMAL(10,2),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel employees dibuat<br>";
    
    // Tabel kendaraans
    $pdo->exec("DROP TABLE IF EXISTS `kendaraans`");
    $pdo->exec("
    CREATE TABLE `kendaraans` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `nama` VARCHAR(255) NOT NULL,
        `plat_nomor` VARCHAR(255),
        `jenis` VARCHAR(255),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel kendaraans dibuat<br>";
    
    // Tabel customers
    $pdo->exec("DROP TABLE IF EXISTS `customers`");
    $pdo->exec("
    CREATE TABLE `customers` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255),
        `phone` VARCHAR(255),
        `address` TEXT,
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel customers dibuat<br>";
    
    // Tabel produks
    $pdo->exec("DROP TABLE IF EXISTS `produks`");
    $pdo->exec("
    CREATE TABLE `produks` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `description` TEXT,
        `price` DECIMAL(10,2),
        `stock` INT DEFAULT 0,
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel produks dibuat<br>";
    
    // Tabel pos
    $pdo->exec("DROP TABLE IF EXISTS `pos`");
    $pdo->exec("
    CREATE TABLE `pos` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `po_number` VARCHAR(255) NOT NULL,
        `customer_id` BIGINT UNSIGNED,
        `total_amount` DECIMAL(10,2),
        `status` VARCHAR(255) DEFAULT 'pending',
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel pos dibuat<br>";
    
    // Tabel po_items
    $pdo->exec("DROP TABLE IF EXISTS `po_items`");
    $pdo->exec("
    CREATE TABLE `po_items` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `po_id` BIGINT UNSIGNED,
        `product_name` VARCHAR(255),
        `quantity` INT,
        `price` DECIMAL(10,2),
        `total` DECIMAL(10,2),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel po_items dibuat<br>";
    
    // Tabel expenses
    $pdo->exec("DROP TABLE IF EXISTS `expenses`");
    $pdo->exec("
    CREATE TABLE `expenses` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `description` VARCHAR(255) NOT NULL,
        `amount` DECIMAL(10,2),
        `date` DATE,
        `category` VARCHAR(255),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel expenses dibuat<br>";
    
    // Tabel salaries
    $pdo->exec("DROP TABLE IF EXISTS `salaries`");
    $pdo->exec("
    CREATE TABLE `salaries` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `employee_id` BIGINT UNSIGNED,
        `amount` DECIMAL(10,2),
        `month` VARCHAR(255),
        `year` INT,
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel salaries dibuat<br>";
    
    // Tabel barang_masuks
    $pdo->exec("DROP TABLE IF EXISTS `barang_masuks`");
    $pdo->exec("
    CREATE TABLE `barang_masuks` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `product_name` VARCHAR(255),
        `quantity` INT,
        `date` DATE,
        `supplier` VARCHAR(255),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel barang_masuks dibuat<br>";
    
    // Tabel barang_keluars
    $pdo->exec("DROP TABLE IF EXISTS `barang_keluars`");
    $pdo->exec("
    CREATE TABLE `barang_keluars` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `product_name` VARCHAR(255),
        `quantity` INT,
        `date` DATE,
        `destination` VARCHAR(255),
        `created_at` TIMESTAMP NULL,
        `updated_at` TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabel barang_keluars dibuat<br>";
    
    // Insert sample data
    echo "<h3>Menambahkan sample data...</h3>";
    
    // Sample employees
    $pdo->exec("
    INSERT INTO employees (name, email, position, salary, created_at, updated_at) VALUES
    ('John Doe', 'john@company.com', 'Manager', 5000000, NOW(), NOW()),
    ('Jane Smith', 'jane@company.com', 'Staff', 3000000, NOW(), NOW()),
    ('Bob Wilson', 'bob@company.com', 'Driver', 2500000, NOW(), NOW())
    ");
    echo "✅ Sample employees ditambahkan<br>";
    
    // Sample customers
    $pdo->exec("
    INSERT INTO customers (name, email, phone, address, created_at, updated_at) VALUES
    ('PT ABC', 'abc@company.com', '081234567890', 'Jakarta', NOW(), NOW())
    ");
    echo "✅ Sample customers ditambahkan<br>";
    
    // Sample products
    $pdo->exec("
    INSERT INTO produks (name, description, price, stock, created_at, updated_at) VALUES
    ('Product A', 'Sample product A', 100000, 50, NOW(), NOW())
    ");
    echo "✅ Sample products ditambahkan<br>";
    
    // Sample PO
    $pdo->exec("
    INSERT INTO pos (po_number, customer_id, total_amount, status, created_at, updated_at) VALUES
    ('PO-001', 1, 500000, 'completed', NOW(), NOW()),
    ('PO-002', 1, 300000, 'pending', NOW(), NOW())
    ");
    echo "✅ Sample PO ditambahkan<br>";
    
    // Sample PO Items
    $pdo->exec("
    INSERT INTO po_items (po_id, product_name, quantity, price, total, created_at, updated_at) VALUES
    (1, 'Product A', 5, 100000, 500000, NOW(), NOW()),
    (2, 'Product A', 3, 100000, 300000, NOW(), NOW())
    ");
    echo "✅ Sample PO items ditambahkan<br>";
    
    echo "<hr>";
    echo "<h3>✅ Semua tabel berhasil dibuat!</h3>";
    echo "<p>Database MySQL sekarang memiliki semua tabel yang diperlukan dengan sample data.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
