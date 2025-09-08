<?php
// Web-based database fix untuk kolom gaji_pokok

echo "<h2>🔧 Database Fix - Kolom gaji_pokok</h2>";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "<p>✅ Koneksi database berhasil</p>";

    // Cek struktur tabel employees
    $columns = $pdo->query("SHOW COLUMNS FROM employees")->fetchAll();
    $hasGajiPokok = false;
    $hasStatus = false;
    
    foreach ($columns as $col) {
        if ($col['Field'] === 'gaji_pokok') $hasGajiPokok = true;
        if ($col['Field'] === 'status') $hasStatus = true;
    }

    echo "<h3>📋 Status Kolom:</h3>";
    echo "<ul>";
    echo "<li>gaji_pokok: " . ($hasGajiPokok ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "<li>status: " . ($hasStatus ? "✅ Ada" : "❌ Tidak ada") . "</li>";
    echo "</ul>";

    // Tambahkan kolom yang hilang
    if (!$hasGajiPokok) {
        $pdo->exec("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000");
        echo "<p style='color: green;'>✅ Kolom gaji_pokok berhasil ditambahkan</p>";
    }

    if (!$hasStatus) {
        $pdo->exec("ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif'");
        echo "<p style='color: green;'>✅ Kolom status berhasil ditambahkan</p>";
    }

    // Update data kosong
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok IS NULL OR gaji_pokok = 0");
    $pdo->exec("UPDATE employees SET status = 'aktif' WHERE status IS NULL OR status = ''");
    echo "<p style='color: green;'>✅ Data default berhasil diupdate</p>";

    // Cek jumlah data employees
    $count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    echo "<p>📊 Jumlah data employees: $count</p>";

    if ($count == 0) {
        $pdo->exec("INSERT INTO employees (nama_karyawan, gaji_pokok, status) VALUES 
                   ('Budi Santoso', 6000000, 'aktif'),
                   ('Siti Nurhaliza', 5500000, 'aktif'),
                   ('Ahmad Rahman', 7000000, 'aktif')");
        echo "<p style='color: green;'>✅ Data sample employees berhasil ditambahkan</p>";
    }

    // Test query yang error sebelumnya
    echo "<h3>🧪 Test Query:</h3>";
    $result = $pdo->query("SELECT COUNT(*) as total_karyawan, SUM(gaji_pokok) as total_gaji FROM employees WHERE status = 'aktif'")->fetch();
    echo "<p>Total karyawan aktif: {$result['total_karyawan']}</p>";
    echo "<p>Total gaji pokok: Rp " . number_format($result['total_gaji'], 0, ',', '.') . "</p>";

    // Tampilkan data employees
    echo "<h3>👥 Data Employees:</h3>";
    $employees = $pdo->query("SELECT * FROM employees LIMIT 10")->fetchAll();
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Gaji Pokok</th><th>Status</th></tr>";
    foreach ($employees as $emp) {
        echo "<tr>";
        echo "<td>{$emp['id']}</td>";
        echo "<td>{$emp['nama_karyawan']}</td>";
        echo "<td>Rp " . number_format($emp['gaji_pokok'], 0, ',', '.') . "</td>";
        echo "<td>{$emp['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3 style='color: green;'>🎉 PERBAIKAN SELESAI!</h3>";
    echo "<p>Aplikasi Laravel sekarang bisa berjalan tanpa error kolom gaji_pokok.</p>";
    echo "<p><strong>Langkah selanjutnya:</strong> Refresh halaman dashboard Laravel Anda.</p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<p>❗ MySQL server tidak berjalan. Pastikan XAMPP/WAMP sudah distart.</p>";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "<p>❗ Database 'manajemen_perusahaan' tidak ditemukan. Buat database terlebih dahulu.</p>";
    }
}
?>
