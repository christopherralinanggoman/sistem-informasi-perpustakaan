USE perpustakaan;

-- Nonaktifkan sementara foreign key constraint untuk menghindari error
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus tabel buku jika sudah ada
DROP TABLE IF EXISTS buku;

-- Aktifkan kembali foreign key constraint
SET FOREIGN_KEY_CHECKS = 1;

-- Buat ulang tabel buku dengan stok default 200 dan kolom genre
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(255) NOT NULL,
    penerbit VARCHAR(255) NOT NULL,
    tahun_terbit INT NOT NULL,
    isbn VARCHAR(50) NOT NULL UNIQUE,
    gambar VARCHAR(255),
    stok INT NOT NULL DEFAULT 200,
    genre VARCHAR(50)
);

-- Tambahkan data buku (stok = 200), dengan genre bervariasi
INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, gambar, stok, genre) VALUES
('Buku Sejarah Indonesia', 'Penulis Sejarah', 'Penerbit Nusantara', 2020, '978-602-1234-567', 'sejarah_indonesia.jpg', 200, 'Sejarah'),
('Buku Sejarah Indonesia Edisi 2', 'Penulis Sejarah', 'Penerbit Nusantara', 2021, '978-602-1234-568', 'sejarah_indonesia2.jpg', 200, 'Sejarah'),
('Buku Pemrograman Web', 'Author Web', 'Tech Media', 2021, '978-602-7654-321', 'Buku Pemrograman Web.png', 200, 'Teknologi'),
('Belajar Data Science', 'Siti Analytics', 'Tekno Future', 2022, '978-602-4444-123', 'belajar_data_science.jpg', 200, 'Teknologi'),
('Pemrograman Python Lanjut', 'Zaki Coders', 'Tekno Expert', 2023, '978-602-6666-777', 'advanced_python.jpg', 200, 'Teknologi'),
('Petualangan Laut Sunyi', 'Rina Samudera', 'Pustaka Biru', 2018, '978-602-1111-222', 'petualangan_laut_sunyi.jpg', 200, 'Fiksi'),
('Psikologi Remaja', 'Dr. Budi Hati', 'Kesehatan Jiwa Press', 2020, '978-602-9999-333', 'psikologi_remaja.jpeg', 200, 'Psikologi'),
('Panduan Fotografi Dasar', 'Dewi Kamera', 'Seni Visual Press', 2017, '978-602-7777-456', 'buku_dasar_fotografi.jpg', 200, 'Teknologi'),
('Ekonomi Mikro Terapan', 'Prof. Andi Ekonomi', 'Penerbit Ekono', 2016, '978-602-2222-444', 'ekonomi_mikro_terapan.jpeg', 200, 'Ekonomi'),
('Kisah Misteri Urban', 'Nana Malam', 'Horror House', 2021, '978-602-8888-999', 'misteri_urban.jpg', 200, 'Fiksi'),
('Resep Makanan Nusantara', 'Chef Dapur', 'Kuliner Mantap', 2019, '978-602-5555-789', 'resep_makanan_nusantara.jpg', 200, 'Kuliner');
