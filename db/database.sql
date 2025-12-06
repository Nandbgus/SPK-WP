-- ======================================================
-- 1. BUAT DATABASE
-- ======================================================
DROP DATABASE IF EXISTS db_wp;

CREATE DATABASE db_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE db_wp;

-- ======================================================
-- 2. TABEL USER
-- ======================================================
CREATE TABLE ta_user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_kode VARCHAR(20) UNIQUE,
    user_nama VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL
);

-- DEFAULT ADMIN
INSERT INTO
    ta_user (
        user_kode,
        user_nama,
        user_password
    )
VALUES ('U01', 'Admin', MD5('admin'));

-- ======================================================
-- 3. TABEL ALTERNATIF
-- ======================================================
CREATE TABLE ta_alternatif (
    alternatif_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) UNIQUE NOT NULL,
    alternatif_nama VARCHAR(100) NOT NULL
) ENGINE = InnoDB;

-- DEFAULT ALTERNATIF
INSERT INTO
    ta_alternatif (
        alternatif_kode,
        alternatif_nama
    )
VALUES ('A01', 'Nanda'),
    ('A02', 'Jofan'),
    ('A03', 'Dika');

-- ======================================================
-- 4. TABEL KRITERIA
-- ======================================================
CREATE TABLE ta_kriteria (
    kriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    kriteria_kode VARCHAR(20) UNIQUE NOT NULL,
    kriteria_nama VARCHAR(100) NOT NULL,
    kriteria_kategori ENUM('benefit', 'cost') NOT NULL,
    kriteria_bobot DECIMAL(10, 4) NOT NULL
) ENGINE = InnoDB;

-- DEFAULT KRITERIA
INSERT INTO
    ta_kriteria (
        kriteria_kode,
        kriteria_nama,
        kriteria_kategori,
        kriteria_bobot
    )
VALUES (
        'C01',
        'Skill',
        'benefit',
        0.6000
    ),
    ('C02', 'Gaji', 'cost', 0.2000),
    (
        'C03',
        'Attitude',
        'benefit',
        0.2000
    );

-- ======================================================
-- 5. TABEL SUB-KRITERIA
-- ======================================================
CREATE TABLE ta_subkriteria (
    subkriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    subkriteria_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    subkriteria_bobot DECIMAL(10, 4) NOT NULL,
    subkriteria_keterangan VARCHAR(255),
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- SUB-KRITERIA: SKILL (C01)
INSERT INTO
    ta_subkriteria (
        subkriteria_kode,
        kriteria_kode,
        subkriteria_bobot,
        subkriteria_keterangan
    )
VALUES (
        'S01',
        'C01',
        5,
        'Sangat Baik'
    ),
    ('S02', 'C01', 4, 'Baik'),
    ('S03', 'C01', 3, 'Cukup'),
    ('S04', 'C01', 2, 'Kurang'),
    ('S05', 'C01', 1, 'Buruk');

-- SUB-KRITERIA: GAJI (C02)
INSERT INTO
    ta_subkriteria (
        subkriteria_kode,
        kriteria_kode,
        subkriteria_bobot,
        subkriteria_keterangan
    )
VALUES ('G01', 'C02', 5, '<= 4 juta'),
    ('G02', 'C02', 4, '4–5 juta'),
    ('G03', 'C02', 3, '5–6 juta'),
    ('G04', 'C02', 2, '6–7 juta'),
    ('G05', 'C02', 1, '> 7 juta');

-- SUB-KRITERIA: ATTITUDE (C03)
INSERT INTO
    ta_subkriteria (
        subkriteria_kode,
        kriteria_kode,
        subkriteria_bobot,
        subkriteria_keterangan
    )
VALUES (
        'A01',
        'C03',
        5,
        'Sangat Baik'
    ),
    ('A02', 'C03', 4, 'Baik'),
    ('A03', 'C03', 3, 'Cukup'),
    ('A04', 'C03', 2, 'Kurang'),
    ('A05', 'C03', 1, 'Buruk');

-- ======================================================
-- 6. TABEL FAKTOR
-- ======================================================
CREATE TABLE tb_faktor (
    nilai_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    nilai_faktor DECIMAL(10, 4) NOT NULL,
    FOREIGN KEY (alternatif_kode) REFERENCES ta_alternatif (alternatif_kode) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- ======================================================
-- 7. DATA DEFAULT NILAI FAKTOR (CONTOH)
-- ======================================================
INSERT INTO
    tb_faktor (
        alternatif_kode,
        kriteria_kode,
        nilai_faktor
    )
VALUES ('A01', 'C01', 4), -- Skill Nanda
    ('A01', 'C02', 2), -- Gaji Nanda
    ('A01', 'C03', 5), -- Attitude Nanda
    ('A02', 'C01', 5),
    ('A02', 'C02', 3),
    ('A02', 'C03', 4),
    ('A03', 'C01', 3),
    ('A03', 'C02', 4),
    ('A03', 'C03', 3);