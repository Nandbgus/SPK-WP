CREATE DATABASE db_wp;

USE db_wp;

-- ===========================
-- TABEL USER
-- ===========================
CREATE TABLE ta_user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_kode VARCHAR(20) NOT NULL,
    user_nama VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL
);

-- default admin
INSERT INTO
    ta_user (
        user_kode,
        user_nama,
        user_password
    )
VALUES (
        'ADM01',
        'Administrator',
        'admin'
    );

-- ===========================
-- TABEL ALTERNATIF
-- ===========================
CREATE TABLE ta_alternatif (
    alternatif_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) NOT NULL,
    alternatif_nama VARCHAR(100) NOT NULL
);

-- ===========================
-- TABEL KRITERIA
-- ===========================
CREATE TABLE ta_kriteria (
    kriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    kriteria_kode VARCHAR(20) NOT NULL,
    kriteria_nama VARCHAR(100) NOT NULL,
    kriteria_kategori ENUM('benefit', 'cost') NOT NULL,
    kriteria_bobot DECIMAL(10, 4) NOT NULL
);

-- ===========================
-- TABEL SUB-KRITERIA
-- ===========================
CREATE TABLE ta_subkriteria (
    subkriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    subkriteria_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    subkriteria_bobot DECIMAL(10, 4) NOT NULL,
    subkriteria_keterangan VARCHAR(255),
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode)
);

-- ===========================
-- TABEL FAKTOR (NILAI ALTERNATIF PER KRITERIA)
-- ===========================
CREATE TABLE tb_faktor (
    nilai_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    nilai_faktor DECIMAL(10, 4) NOT NULL,
    FOREIGN KEY (alternatif_kode) REFERENCES ta_alternatif (alternatif_kode),
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode)
);