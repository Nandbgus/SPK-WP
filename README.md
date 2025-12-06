# Sistem SPK Metode Weighted Product (WP)

Aplikasi ini merupakan Sistem Pendukung Keputusan (SPK) yang menggunakan metode **Weighted Product (WP)** untuk menentukan ranking suatu alternatif berdasarkan nilai faktor dan bobot kriteria.
Aplikasi dibangun menggunakan **PHP + MySQL** serta mendukung pengelolaan Alternatif, Kriteria, Sub‑Kriteria, Nilai Faktor, dan Perankingan.

---

## 1. Struktur Database

Basis data yang digunakan: **db_wp**

Tabel yang tersedia:

* `ta_user`
* `ta_alternatif`
* `ta_kriteria`
* `ta_subkriteria`
* `tb_faktor`

Semua tabel menggunakan kolom **AUTO_INCREMENT ID** untuk kompatibilitas penuh dengan kode PHP.

Contoh:

* `alternatif_id`
* `kriteria_id`
* `subkriteria_id`
* `nilai_id`

---

## 2. Penulisan Bobot Kriteria

Sistem mendukung input bobot menggunakan **angka desimal** maupun **persentase**.

| Input | Interpretasi   | Hasil disimpan |
| ----- | -------------- | -------------- |
| `40`  | dianggap 40%   | `0.40`         |
| `25`  | dianggap 25%   | `0.25`         |
| `0.3` | bobot langsung | `0.3`          |
| `1`   | bobot penuh    | `1`            |

### Cara Kerja

Jika input **lebih dari 1**, sistem otomatis membaginya dengan **100** sehingga menjadi bobot desimal.

Contoh:

```
Input: 35
Output: 0.35
```

---

## 3. Cara Kerja Metode Weighted Product (WP)

Metode WP menggunakan rumus:

```
S_i = Π (X_ij ^ W_j)
```

* `X_ij` → nilai faktor sebuah alternatif pada kriteria j
* `W_j` → bobot kriteria (sudah dinormalisasi 0–1)
* Benefit → pangkat positif
* Cost → pangkat negatif

Setelah nilai S diperoleh, sistem menghitung nilai V:

```
V_i = S_i / Σ S
```

Nilai V kemudian diurutkan menjadi ranking.

---

## 4. Fitur Utama Aplikasi

### ✔ Manajemen Alternatif

CRUD lengkap beserta auto‑kode (A01, A02, dst.)

### ✔ Manajemen Kriteria

Memasukkan nama kriteria, kategori (benefit/cost), serta bobot otomatis ternormalisasi.

### ✔ Manajemen Sub‑Kriteria

Menyimpan bobot sub‑kriteria sebagai nilai faktor.

### ✔ Input Nilai Faktor

User memilih nilai setiap alternatif berdasarkan sub‑kriteria.

### ✔ Perhitungan Ranking

Sistem menghitung nilai Weighted Product dan menampilkan peringkat.

### ✔ Normalisasi Bobot Otomatis

User dapat input bobot apa pun (0.3 atau 30), sistem mengonversinya otomatis.

### ✔ Modal Bootstrap

Semua input menggunakan modal untuk tampilan yang lebih rapi.

---

## 5. Cara Menggunakan Aplikasi

### 1. Login

Akun default:

```
Username: ADM01
Password: admin
```

### 2. Tambah Alternatif

Masukkan nama alternatif, kode akan dibuat otomatis.

### 3. Tambah Kriteria

Isi:

* Kode kriteria (C01, C02 otomatis)
* Nama kriteria
* Kategori (benefit/cost)
* Bobot kriteria (0.4 atau 40 → otomatis normalisasi)

### 4. Tambah Sub‑Kriteria

Setiap kriteria dapat memiliki sub‑kriteria dengan bobot nilai.

### 5. Input Nilai Faktor

Pada halaman faktor, pilih alternatif dan isi nilai sub‑kriteria.

### 6. Lihat Ranking

Sistem menghitung ranking WP otomatis.

---

## 6. Kebutuhan Sistem

* PHP 7+
* MySQL
* Apache / Nginx
* Browser modern

---

## 7. Catatan Pengembangan

Fitur normalisasi bobot otomatis telah ditambahkan pada:

* `kriteriaAdd.php`
* `kriteriaEdit.php`

Pengembangan lanjutan yang disarankan:

* Validasi total bobot harus 1
* Grafik visualisasi perankingan
* Mode export PDF/Excel

---

## 8. Lisensi

Aplikasi ini bebas digunakan untuk pembelajaran, riset, dan pengembangan.
Tidak untuk diperdagangkan tanpa izin pembuat asli.
