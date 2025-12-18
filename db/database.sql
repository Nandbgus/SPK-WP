-- HAPUS DB LAMA DAN BUAT BARU
DROP DATABASE IF EXISTS db_wp;

CREATE DATABASE db_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE db_wp;

-- ======================================================
-- 1. TABEL USER
-- ======================================================
CREATE TABLE ta_user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_kode VARCHAR(20) UNIQUE,
    user_nama VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL
);

INSERT INTO
    ta_user (
        user_kode,
        user_nama,
        user_password
    )
VALUES ('U01', 'Admin', MD5('admin'));

-- ======================================================
-- 2. TABEL ALTERNATIF
-- ======================================================
CREATE TABLE ta_alternatif (
    alternatif_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) UNIQUE NOT NULL,
    alternatif_nama VARCHAR(100) NOT NULL
) ENGINE = InnoDB;

INSERT INTO
    ta_alternatif (
        alternatif_kode,
        alternatif_nama
    )
VALUES ('A01', 'Nanda'),
    ('A02', 'Jofan'),
    ('A03', 'Dika');

-- ======================================================
-- 3. TABEL KRITERIA (Setting untuk ROC)
-- ======================================================
CREATE TABLE ta_kriteria (
    kriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    kriteria_kode VARCHAR(20) UNIQUE NOT NULL,
    kriteria_nama VARCHAR(100) NOT NULL,
    kriteria_kategori ENUM('benefit', 'cost') NOT NULL,
    kriteria_bobot DECIMAL(10, 0) NOT NULL -- Ubah ke integer (0 desimal) untuk Ranking
) ENGINE = InnoDB;

-- INPUT PRIORITAS (1 = Terpenting)
INSERT INTO
    ta_kriteria (
        kriteria_kode,
        kriteria_nama,
        kriteria_kategori,
        kriteria_bobot
    )
VALUES ('C01', 'Skill', 'benefit', 1), -- Rank 1
    ('C02', 'Gaji', 'cost', 2), -- Rank 2
    (
        'C03',
        'Attitude',
        'benefit',
        3
    );
-- Rank 3

-- ======================================================
-- 4. TABEL SUB-KRITERIA
-- ======================================================
CREATE TABLE ta_subkriteria (
    subkriteria_id INT AUTO_INCREMENT PRIMARY KEY,
    subkriteria_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    subkriteria_bobot DECIMAL(10, 2) NOT NULL,
    subkriteria_keterangan VARCHAR(255),
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- SKILL (Benefit: Semakin tinggi semakin baik)
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

-- GAJI (Cost: Semakin kecil nilainya semakin baik)
-- REVISI LOGIKA: Gaji Murah (<=4jt) diberi nilai 1 (Kecil), Gaji Mahal (>7jt) diberi nilai 5 (Besar).
-- Karena sifatnya COST, WP akan membalik ini (1 jadi bagus, 5 jadi jelek).
INSERT INTO
    ta_subkriteria (
        subkriteria_kode,
        kriteria_kode,
        subkriteria_bobot,
        subkriteria_keterangan
    )
VALUES ('G01', 'C02', 1, '<= 4 juta'),
    ('G02', 'C02', 2, '4–5 juta'),
    ('G03', 'C02', 3, '5–6 juta'),
    ('G04', 'C02', 4, '6–7 juta'),
    ('G05', 'C02', 5, '> 7 juta');

-- ATTITUDE (Benefit)
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
-- 5. TABEL FAKTOR (NILAI ALTERNATIF)
-- ======================================================
CREATE TABLE tb_faktor (
    nilai_id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_kode VARCHAR(20) NOT NULL,
    kriteria_kode VARCHAR(20) NOT NULL,
    nilai_faktor DECIMAL(10, 4) NOT NULL,
    FOREIGN KEY (alternatif_kode) REFERENCES ta_alternatif (alternatif_kode) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kriteria_kode) REFERENCES ta_kriteria (kriteria_kode) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- DATA SESUAI KASUS ANDA
-- NANDA: Skill 4, Gaji 2 (4-5jt), Attitude 5
INSERT INTO
    tb_faktor (
        alternatif_kode,
        kriteria_kode,
        nilai_faktor
    )
VALUES ('A01', 'C01', 4),
    ('A01', 'C02', 2),
    ('A01', 'C03', 5);

-- JOFAN: Skill 5, Gaji 3 (5-6jt), Attitude 4
INSERT INTO
    tb_faktor (
        alternatif_kode,
        kriteria_kode,
        nilai_faktor
    )
VALUES ('A02', 'C01', 5),
    ('A02', 'C02', 3),
    ('A02', 'C03', 4);

-- DIKA: Skill 3, Gaji 4 (6-7jt), Attitude 3
INSERT INTO
    tb_faktor (
        alternatif_kode,
        kriteria_kode,
        nilai_faktor
    )
VALUES ('A03', 'C01', 3),
    ('A03', 'C02', 4),
    ('A03', 'C03', 3);

-- ======================================================
-- 6. TABEL RIWAYAT
-- ======================================================
CREATE TABLE riwayat_perhitungan (
    id INT(11) NOT NULL AUTO_INCREMENT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    alternatif_tertinggi VARCHAR(100) NOT NULL,
    nilai_tertinggi FLOAT NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;