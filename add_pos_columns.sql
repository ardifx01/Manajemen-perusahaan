-- SQL script untuk menambahkan kolom customer dan kolom lainnya ke tabel pos

USE manajemen_perusahaan;

-- Tambahkan kolom customer jika belum ada
ALTER TABLE pos ADD COLUMN customer VARCHAR(255) DEFAULT 'PT. Default Customer';

-- Tambahkan kolom lain yang dibutuhkan
ALTER TABLE pos ADD COLUMN no_invoice VARCHAR(255) DEFAULT NULL;
ALTER TABLE pos ADD COLUMN pengirim VARCHAR(255) DEFAULT NULL;
ALTER TABLE pos ADD COLUMN alamat_1 TEXT DEFAULT NULL;
ALTER TABLE pos ADD COLUMN alamat_2 TEXT DEFAULT NULL;

-- Update data yang kosong
UPDATE pos SET customer = 'PT. Default Customer' WHERE customer IS NULL OR customer = '';
UPDATE pos SET alamat_1 = 'Alamat Default' WHERE alamat_1 IS NULL OR alamat_1 = '';

-- Tambahkan data sample jika tabel kosong
INSERT INTO pos (tanggal_po, no_po, no_surat_jalan, customer, alamat_1, total) 
SELECT * FROM (
    SELECT '2025-09-01' as tanggal_po, 'PO-001' as no_po, 'SJ-001' as no_surat_jalan, 'PT. ABC Company' as customer, 'Jl. Sudirman No. 1' as alamat_1, 1000000 as total
    UNION ALL
    SELECT '2025-09-02', 'PO-002', 'SJ-002', 'PT. XYZ Corp', 'Jl. Thamrin No. 2', 1500000
    UNION ALL  
    SELECT '2025-09-03', 'PO-003', 'SJ-003', 'CV. Maju Jaya', 'Jl. Gatot Subroto No. 3', 2000000
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM pos LIMIT 1);

-- Test query
SELECT pos.customer, COUNT(DISTINCT po_items.po_id) as orders, SUM(po_items.total) as subtotal 
FROM po_items 
INNER JOIN pos ON po_items.po_id = pos.id 
WHERE pos.tanggal_po BETWEEN '2025-09-01 00:00:00' AND '2025-09-30 23:59:59' 
GROUP BY pos.customer 
ORDER BY subtotal DESC;
