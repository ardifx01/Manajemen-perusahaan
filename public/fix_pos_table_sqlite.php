<?php
// Quick fix for SQLite: add missing columns and tables required by Invoice features
// Usage: open http://127.0.0.1:8000/fix_pos_table_sqlite.php in your browser while dev server is running

declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');

echo '<h2>SQLite Quick Fix: POS & Settings</h2>';

try {
    $dbPath = __DIR__ . '/../database/database.sqlite';
    if (!file_exists($dbPath)) {
        throw new RuntimeException('database.sqlite tidak ditemukan di: ' . $dbPath);
    }

    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo '<p>✔ Koneksi SQLite OK</p>';
    echo '<p><small>DB: ' . htmlspecialchars($dbPath) . '</small></p>';

    // Helper: cek kolom ada atau tidak
    $columnExists = function (PDO $pdo, string $table, string $column): bool {
        $stmt = $pdo->prepare("PRAGMA table_info($table)");
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            if (isset($col['name']) && strtolower($col['name']) === strtolower($column)) {
                return true;
            }
        }
        return false;
    };

    // 1) Pastikan tabel pos ada
    $hasPos = (bool)$pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='pos'")->fetchColumn();
    if (!$hasPos) {
        echo '<p>➕ Membuat tabel pos...</p>';
        $pdo->exec(<<<SQL
            CREATE TABLE pos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tanggal_po DATE NOT NULL,
                customer_id INTEGER,
                customer VARCHAR(255),
                pengirim VARCHAR(255),
                no_surat_jalan VARCHAR(255),
                no_po VARCHAR(255),
                no_invoice VARCHAR(255),
                no_polisi VARCHAR(255),
                kendaraan VARCHAR(255),
                qty INTEGER DEFAULT 0,
                qty_jenis VARCHAR(50) DEFAULT 'PCS',
                produk_id INTEGER,
                harga DECIMAL(15,2) DEFAULT 0,
                total DECIMAL(15,2) DEFAULT 0,
                alamat_1 TEXT,
                alamat_2 TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        SQL);
        echo '<p>✔ Tabel pos dibuat</p>';
    } else {
        echo '<p>✔ Tabel pos sudah ada</p>';
    }

    // 2) Tambahkan kolom po_number jika belum ada
    if (!$columnExists($pdo, 'pos', 'po_number')) {
        echo '<p>➕ Menambahkan kolom po_number ke tabel pos...</p>';
        $pdo->exec("ALTER TABLE pos ADD COLUMN po_number INTEGER");
        echo '<p>✔ Kolom po_number berhasil ditambahkan</p>';
    } else {
        echo '<p>✔ Kolom po_number sudah ada</p>';
    }

    // 3) Pastikan tabel settings ada
    $hasSettings = (bool)$pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetchColumn();
    if (!$hasSettings) {
        echo '<p>➕ Membuat tabel settings...</p>';
        $pdo->exec(<<<SQL
            CREATE TABLE settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                key VARCHAR(191) UNIQUE NOT NULL,
                value TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        SQL);
        echo '<p>✔ Tabel settings dibuat</p>';
    } else {
        echo '<p>✔ Tabel settings sudah ada</p>';
    }

    // 4) Seed next_invoice_number jika belum ada
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key = 'next_invoice_number' LIMIT 1");
    $stmt->execute();
    $val = $stmt->fetchColumn();
    if ($val === false) {
        echo '<p>➕ Menambahkan setting next_invoice_number = 1000</p>';
        $ins = $pdo->prepare("INSERT INTO settings(key, value) VALUES('next_invoice_number', '1000')");
        $ins->execute();
        echo '<p>✔ next_invoice_number diinisialisasi</p>';
    } else {
        echo '<p>✔ next_invoice_number sudah ada (nilai saat ini: ' . htmlspecialchars((string)$val) . ')</p>';
    }

    echo '<hr><h3>🎉 Perbaikan selesai</h3>';
    echo '<p>Anda sudah bisa kembali ke halaman Data Invoice dan coba tombol Tambah / Atur Nomor lagi.</p>';
    echo '<p><a href="/" style="display:inline-block;padding:10px 14px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;">Kembali ke Aplikasi</a></p>';

} catch (Throwable $e) {
    http_response_code(500);
    echo '<p style="color:red;">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
