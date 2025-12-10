<?php 
    $page = 'home';
    include '../blade/header.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SPK WP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;

            
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            padding: 30px;
            max-width: 800px;
            color: #fff;
        }

        .main-card h3 {
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .desc {
            font-size: 0.95rem;
            line-height: 1.6;
            text-align: justify;
        }

        .gambar img {
            max-width: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            .main-card {
                margin: 20px;
                padding: 20px;
            }
            .gambar img {
                width: 80%;
            }
        }
    </style>
</head>

<body>
    <div class="main-card text-center">
        <?php include '../blade/namaProgram.php'; ?>
        <?php include '../blade/nav.php'; ?>

        <div class="mb-3">
            <a href="../ranking/ranking.php" class="btn btn-warning me-2">Lihat Ranking</a>
            <a href="../ranking/riwayat.php" class="btn btn-secondary">Riwayat Perhitungan</a>
        </div>

        <h3>Rekomendasi Kenaikan Jabatan PNS (Metode WP)</h3>

        <div class="gambar mb-4">
            <img src="../img/pnsImage.JPG" alt="Pegawai Negeri Sipil" class="img-fluid">
        </div>

        <p class="desc">
            Metode <b>Weighted Product (WP)</b> menggunakan pendekatan perkalian untuk
            menggabungkan nilai atribut, di mana setiap atribut dipangkatkan dengan bobotnya
            masing-masing. Teknik ini berfungsi sebagai proses normalisasi untuk menilai alternatif
            berdasarkan kriteria yang telah ditentukan. Hasilnya digunakan untuk mendukung proses
            pengambilan keputusan secara objektif dan terukur.
        </p>

        <div class="mt-3">
            <?php include '../blade/footer.php'; ?>
        </div>
    </div>
</body>
</html>
