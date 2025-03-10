USE perpustakaan;

-- Insert akun dummy anggota
INSERT INTO anggota (nama, email, password, alamat, no_telp) VALUES
('Ahmad', 'ahmad@example.com', 'hashed_password_1', 'Jakarta', '08123456789'),
('Budi', 'budi@example.com', 'hashed_password_2', 'Bandung', '08234567890'),
('Citra', 'citra@example.com', 'hashed_password_3', 'Surabaya', '08345678901');

-- Ambil ID anggota terbaru
SET @id_ahmad = LAST_INSERT_ID();
SET @id_budi = @id_ahmad + 1;
SET @id_citra = @id_budi + 1;

-- Insert transaksi peminjaman buku dengan keterlambatan
INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, due_date, tanggal_kembali, status) VALUES
(1, @id_ahmad, '2024-03-06', '2024-03-09', '2024-03-10', 'terlambat'), -- 1 hari terlambat
(2, @id_budi, '2024-03-06', '2024-03-09', '2024-03-12', 'terlambat'), -- 3 hari terlambat
(3, @id_citra, '2024-03-06', '2024-03-09', NULL, 'belum dikembalikan'); -- Masih belum dikembalikan
