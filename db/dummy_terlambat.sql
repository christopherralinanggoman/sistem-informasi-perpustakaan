USE perpustakaan;

-- Insert akun dummy anggota jika belum ada
INSERT INTO anggota (nama, email, password, alamat, no_telp) VALUES
('Ahmad', 'ahmad@example.com', 'hashed_password_1', 'Jakarta', '08123456789'),
('Budi', 'budi@example.com', 'hashed_password_2', 'Bandung', '08234567890'),
('Citra', 'citra@example.com', 'hashed_password_3', 'Surabaya', '08345678901')
ON DUPLICATE KEY UPDATE 
    email = VALUES(email), 
    password = VALUES(password), 
    alamat = VALUES(alamat), 
    no_telp = VALUES(no_telp);

-- Ambil ID anggota berdasarkan nama untuk menghindari duplikasi
SET @id_ahmad = (SELECT id_anggota FROM anggota WHERE nama = 'Ahmad');
SET @id_budi = (SELECT id_anggota FROM anggota WHERE nama = 'Budi');
SET @id_citra = (SELECT id_anggota FROM anggota WHERE nama = 'Citra');

-- Insert transaksi peminjaman buku dengan keterlambatan jika belum ada
INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, due_date, tanggal_kembali, status)
SELECT * FROM (SELECT 1 AS id_buku, @id_ahmad AS id_anggota, '2024-03-06' AS tanggal_pinjam, '2024-03-09' AS due_date, '2024-03-10' AS tanggal_kembali, 'terlambat' AS status) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM transaksi WHERE id_buku = 1 AND id_anggota = @id_ahmad AND tanggal_pinjam = '2024-03-06'
)
UNION ALL
SELECT * FROM (SELECT 2, @id_budi, '2024-03-06', '2024-03-09', '2024-03-12', 'terlambat') AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM transaksi WHERE id_buku = 2 AND id_anggota = @id_budi AND tanggal_pinjam = '2024-03-06'
)
UNION ALL
SELECT * FROM (SELECT 3, @id_citra, '2024-03-06', '2024-03-09', NULL, 'belum dikembalikan') AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM transaksi WHERE id_buku = 3 AND id_anggota = @id_citra AND tanggal_pinjam = '2024-03-06'
);
