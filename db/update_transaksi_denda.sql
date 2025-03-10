-- update_transaksi_denda.sql

USE perpustakaan;

-- Add a column to track if the fine has been paid (0 = not paid, 1 = paid)
ALTER TABLE transaksi 
    ADD COLUMN IF NOT EXISTS denda_lunas TINYINT(1) NOT NULL DEFAULT 0;
