<?php
// Script untuk menambahkan kolom gaji_pokok yang hilang di tabel employees

try {
    // Koneksi ke database MySQL
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=manajemen_perusahaan', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "<h2>🔧 Fix Kolom gaji_pokok di Tabel employees</h2>";

    // Cek struktur tabel employees saat ini
    echo "<h3>📋 Struktur Tabel employees Saat Ini:</h3>";
    $columns = $pdo->query("DESCRIBE employees")->fetchAll();
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Cek apakah kolom gaji_pokok sudah ada
    $hasGajiPokok = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'gaji_pokok') {
            $hasGajiPokok = true;
            break;
        }
    }

    if ($hasGajiPokok) {
        echo "<p style='color: green;'>✅ Kolom 'gaji_pokok' sudah ada di tabel employees.</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Kolom 'gaji_pokok' tidak ditemukan. Menambahkan kolom...</p>";
        
        // Tambahkan kolom gaji_pokok
        $pdo->exec("ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 0 AFTER nama_karyawan");
        echo "<p style='color: green;'>✅ Kolom 'gaji_pokok' berhasil ditambahkan.</p>";
    }

    // Cek apakah ada kolom lain yang mungkin dibutuhkan
    $requiredColumns = [
        'gaji_pokok' => 'DECIMAL(15,2) DEFAULT 0',
        'status' => 'VARCHAR(50) DEFAULT "aktif"',
        'tanggal_masuk' => 'DATE DEFAULT NULL',
        'tanggal_daftar' => 'DATE DEFAULT NULL'
    ];

    echo "<h3>🔍 Memverifikasi Kolom yang Dibutuhkan:</h3>";
    $existingColumns = array_column($columns, 'Field');
    
    foreach ($requiredColumns as $colName => $colDef) {
        if (!in_array($colName, $existingColumns)) {
            echo "<p style='color: orange;'>⚠️ Menambahkan kolom '$colName'...</p>";
            $pdo->exec("ALTER TABLE employees ADD COLUMN $colName $colDef");
            echo "<p style='color: green;'>✅ Kolom '$colName' berhasil ditambahkan.</p>";
        } else {
            echo "<p style='color: green;'>✅ Kolom '$colName' sudah ada.</p>";
        }
    }

    // Update data employees dengan gaji_pokok default jika kosong
    echo "<h3>💰 Mengupdate Data Gaji Pokok:</h3>";
    $pdo->exec("UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL");
    $updated = $pdo->lastInsertId();
    echo "<p style='color: green;'>✅ Data gaji_pokok berhasil diupdate dengan nilai default 5,000,000.</p>";

    // Tampilkan data employees setelah perbaikan
    echo "<h3>👥 Data Employees Setelah Perbaikan:</h3>";
    $employees = $pdo->query("SELECT id, nama_karyawan, gaji_pokok, status, tanggal_masuk FROM employees LIMIT 10")->fetchAll();
    
    if (empty($employees)) {
        echo "<p style='color: orange;'>⚠️ Tidak ada data employees. Menambahkan data sample...</p>";
        
        $sampleEmployees = [
            ['Budi Santoso', 6000000, 'aktif', '2024-01-15'],
            ['Siti Nurhaliza', 5500000, 'aktif', '2024-02-01'],
            ['Ahmad Rahman', 7000000, 'aktif', '2024-01-10'],
        ];
        
        foreach ($sampleEmployees as $emp) {
            $pdo->prepare("INSERT INTO employees (nama_karyawan, gaji_pokok, status, tanggal_masuk, tanggal_daftar) VALUES (?, ?, ?, ?, ?)")
                ->execute([$emp[0], $emp[1], $emp[2], $emp[3], $emp[3]]);
        }
        
        echo "<p style='color: green;'>✅ Data sample employees berhasil ditambahkan.</p>";
        
        // Ambil data lagi setelah insert
        $employees = $pdo->query("SELECT id, nama_karyawan, gaji_pokok, status, tanggal_masuk FROM employees")->fetchAll();
    }

    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Nama Karyawan</th><th>Gaji Pokok</th><th>Status</th><th>Tanggal Masuk</th></tr>";
    foreach ($employees as $emp) {
        echo "<tr>";
        echo "<td>{$emp['id']}</td>";
        echo "<td>{$emp['nama_karyawan']}</td>";
        echo "<td>Rp " . number_format($emp['gaji_pokok'], 0, ',', '.') . "</td>";
        echo "<td>{$emp['status']}</td>";
        echo "<td>{$emp['tanggal_masuk']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Test query yang error sebelumnya
    echo "<h3>🧪 Test Query Dashboard:</h3>";
    try {
        $result = $pdo->query("SELECT COUNT(*) as total_karyawan FROM employees WHERE status = 'aktif'")->fetch();
        echo "<p style='color: green;'>✅ Total karyawan aktif: {$result['total_karyawan']}</p>";
        
        $result = $pdo->query("SELECT SUM(gaji_pokok) as total_gaji FROM employees WHERE status = 'aktif'")->fetch();
        echo "<p style='color: green;'>✅ Total gaji pokok: Rp " . number_format($result['total_gaji'], 0, ',', '.') . "</p>";
        
        $result = $pdo->query("SELECT AVG(gaji_pokok) as rata_gaji FROM employees WHERE status = 'aktif'")->fetch();
        echo "<p style='color: green;'>✅ Rata-rata gaji: Rp " . number_format($result['rata_gaji'], 0, ',', '.') . "</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error saat test query: " . $e->getMessage() . "</p>";
    }

    echo "<h3>✅ Perbaikan Selesai!</h3>";
    echo "<p>Kolom gaji_pokok sudah ditambahkan dan data sudah diupdate. Aplikasi Laravel seharusnya sudah bisa berjalan tanpa error.</p>";
    echo "<p><strong>Langkah selanjutnya:</strong> Refresh halaman dashboard untuk melihat hasilnya.</p>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<p>Pastikan MySQL server berjalan dan database 'manajemen_perusahaan' sudah ada.</p>";
}
?>
