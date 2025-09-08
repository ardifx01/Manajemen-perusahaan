<?php
/**
 * Script web untuk memperbaiki database - menambahkan kolom yang hilang
 */

echo "<h2>Fix Database - Tambah Kolom yang Hilang</h2>";
echo "<hr>";

try {
    // Koneksi ke MySQL
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan;charset=utf8mb4",
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Menambahkan kolom yang hilang...</h3>";
    
    // Tambahkan kolom status ke employees
    try {
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(255) DEFAULT 'aktif'");
        echo "✅ Kolom 'status' ditambahkan ke tabel employees<br>";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "⚠️ Kolom 'status' sudah ada di tabel employees<br>";
        } else {
            echo "❌ Error: " . $e->getMessage() . "<br>";
        }
    }
    
    // Update status employees
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status IS NULL OR status = ''");
    echo "✅ Status employees diupdate ke 'aktif'<br>";
    
    // Tambahkan kolom lain yang mungkin dibutuhkan
    $alterations = [
        "ALTER TABLE customers ADD COLUMN status VARCHAR(255) DEFAULT 'active'" => "customers.status",
        "ALTER TABLE produks ADD COLUMN status VARCHAR(255) DEFAULT 'active'" => "produks.status", 
        "ALTER TABLE pos ADD COLUMN delivery_date DATE NULL" => "pos.delivery_date",
        "ALTER TABLE pos ADD COLUMN notes TEXT NULL" => "pos.notes",
        "ALTER TABLE kendaraans ADD COLUMN status VARCHAR(255) DEFAULT 'active'" => "kendaraans.status",
        "ALTER TABLE expenses ADD COLUMN status VARCHAR(255) DEFAULT 'approved'" => "expenses.status",
        "ALTER TABLE salaries ADD COLUMN status VARCHAR(255) DEFAULT 'paid'" => "salaries.status"
    ];
    
    foreach ($alterations as $sql => $description) {
        try {
            $pdo->exec($sql);
            echo "✅ Kolom '$description' ditambahkan<br>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "⚠️ Kolom '$description' sudah ada<br>";
            } else {
                echo "❌ Error menambahkan '$description': " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "<h3>Verifikasi struktur tabel employees:</h3>";
    $columns = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Default']}</td></tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>✅ Database berhasil diperbaiki!</h3>";
    echo "<p>Semua kolom yang dibutuhkan sudah ditambahkan. Aplikasi siap digunakan.</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error</h3>";
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
