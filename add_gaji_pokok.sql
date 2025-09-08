-- SQL script untuk menambahkan kolom gaji_pokok ke tabel employees

USE manajemen_perusahaan;

-- Tambahkan kolom gaji_pokok jika belum ada
ALTER TABLE employees ADD COLUMN gaji_pokok DECIMAL(15,2) DEFAULT 5000000;

-- Tambahkan kolom status jika belum ada  
ALTER TABLE employees ADD COLUMN status VARCHAR(50) DEFAULT 'aktif';

-- Update data yang kosong
UPDATE employees SET gaji_pokok = 5000000 WHERE gaji_pokok = 0 OR gaji_pokok IS NULL;
UPDATE employees SET status = 'aktif' WHERE status = '' OR status IS NULL;

-- Tambahkan data sample jika tabel kosong
INSERT INTO employees (nama_karyawan, gaji_pokok, status) 
SELECT * FROM (
    SELECT 'Budi Santoso' as nama_karyawan, 6000000 as gaji_pokok, 'aktif' as status
    UNION ALL
    SELECT 'Siti Nurhaliza', 5500000, 'aktif'
    UNION ALL  
    SELECT 'Ahmad Rahman', 7000000, 'aktif'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM employees LIMIT 1);

-- Test query
SELECT COUNT(*) as total_karyawan, SUM(gaji_pokok) as total_gaji FROM employees WHERE status = 'aktif';
