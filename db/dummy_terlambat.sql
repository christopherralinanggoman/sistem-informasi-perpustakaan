USE perpustakaan;

INSERT INTO anggota (id_anggota, nama, email, password, alamat, no_telp) VALUES
(1, 'Ahmad Santoso', 'ahmad@example.com', 'hashed_password1', 'Jl. Merdeka No. 1', '081234567890'),
(2, 'Budi Hartono', 'budi@example.com', 'hashed_password2', 'Jl. Mawar No. 2', '081298765432'),
(3, 'Citra Lestari', 'citra@example.com', 'hashed_password3', 'Jl. Melati No. 3', '081356789012');

INSERT INTO buku (id_buku, judul, genre, stok) VALUES
(1, 'Belajar PHP', 'Programming', 10),
(2, 'Database MySQL', 'Database', 8),
(3, 'Data Science Basics', 'Technology', 5);

INSERT INTO transaksi (id_transaksi, id_buku, id_anggota, tanggal_pinjam, due_date, status) VALUES
(1, 1, 1, '2024-03-06', '2024-03-09', 'terlambat'), -- Terlambat 1 hari
(2, 2, 2, '2024-03-06', '2024-03-08', 'terlambat'), -- Terlambat 2 hari
(3, 3, 3, '2024-03-06', '2024-03-07', 'terlambat'); -- Terlambat 3 hari
