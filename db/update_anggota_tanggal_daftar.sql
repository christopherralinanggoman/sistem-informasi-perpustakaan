-- update_anggota_tanggal_daftar.sql

USE perpustakaan;

ALTER TABLE anggota 
    ADD COLUMN tanggal_daftar DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
