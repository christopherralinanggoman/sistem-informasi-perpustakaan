-- Buat database perpustakaan jika belum ada
CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

-- Tabel admin untuk login
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin user dengan password 'admin' (MD5)
INSERT INTO admin (username, password) VALUES
('admin', MD5('admin'));

-- Tabel buku
CREATE TABLE IF NOT EXISTS buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(255) NOT NULL,
    penerbit VARCHAR(255) NOT NULL,
    tahun_terbit YEAR,
    isbn VARCHAR(20),
    gambar VARCHAR(255)
);

-- Tabel anggota
CREATE TABLE IF NOT EXISTS anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    alamat TEXT,
    no_telp VARCHAR(20)
);

-- Tabel transaksi (peminjaman/pengembalian buku)
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_buku INT NOT NULL,
    id_anggota INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    due_date DATE NOT NULL,
    tanggal_kembali DATE,
    status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE
);
