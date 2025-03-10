-- Pilih database yang akan digunakan
USE perpustakaan;

-- Buat tabel `visitors` jika belum ada
CREATE TABLE IF NOT EXISTS visitors (
    id_visitor INT AUTO_INCREMENT PRIMARY KEY,
    nama_visitor VARCHAR(255) NOT NULL,
    visit_date DATETIME NOT NULL
);

-- Contoh data pengunjung
INSERT INTO visitors (nama_visitor, visit_date) VALUES
('Pengunjung 1', '2025-03-01 09:00:00'),
('Pengunjung 2', '2025-03-01 10:30:00'),
('Pengunjung 3', '2025-03-02 14:20:00'),
('Pengunjung 4', '2025-03-03 11:45:00'),
('Pengunjung 5', '2025-03-03 13:15:00'),
('Pengunjung 6', '2025-03-04 08:10:00'),
('Pengunjung 7', '2025-03-05 16:40:00'),
('Pengunjung 8', '2025-03-05 17:05:00'),
('Pengunjung 9', '2025-03-06 09:25:00'),
('Pengunjung 10', '2025-03-07 15:00:00');
