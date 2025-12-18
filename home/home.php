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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            /* Background Gradient Konsisten */
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 50px;
        }

        /* Styling Navbar Transparan (Sama dengan Alternatif) */
        nav.navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        /* Kartu Glassmorphism (Sama dengan Alternatif) */
        .main-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
            padding: 40px;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .main-card h3 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
        }

        .desc {
            font-size: 1rem;
            line-height: 1.8;
            color: #f9f9f9;
            text-align: justify;
            margin-top: 20px;
        }

        /* Styling Gambar Home */
        .gambar-hero img {
            max-width: 100%;
            height: auto;
            max-height: 320px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .gambar-hero img:hover {
            transform: scale(1.02);
        }

        /* Tombol Aksi */
        .btn-home {
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <?php include '../blade/nav.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <div class="main-card mt-4 text-center">

                    <div class="mb-4">
                        <?php include '../blade/namaProgram.php'; ?>
                    </div>

                    <h3>Rekomendasi Kenaikan Jabatan PNS<br><span class="fs-6 fw-light opacity-75">(Metode Weighted Product)</span></h3>

                    <div class="gambar-hero mb-4 mt-4">
                        <img src="../img/pnsImage.JPG" alt="Ilustrasi PNS" class="img-fluid">
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="../ranking/ranking.php" class="btn btn-warning btn-home text-dark shadow-sm">
                            <i class="bi bi-trophy-fill"></i> Lihat Ranking
                        </a>
                        <a href="../ranking/riwayat.php" class="btn btn-light btn-home text-primary shadow-sm">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </div>

                    <div class="p-3 rounded" style="background: rgba(0,0,0,0.1);">
                        <p class="desc mb-0">
                            Sistem Pendukung Keputusan ini dirancang menggunakan metode <b>Weighted Product (WP)</b>.
                            Metode ini melakukan normalisasi nilai dengan memangkatkan setiap atribut terhadap bobotnya.
                            Hasil perhitungan memberikan rekomendasi yang objektif, transparan, dan terukur untuk mendukung
                            proses seleksi kenaikan jabatan pegawai.
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-top border-light opacity-75">
                        <?php include '../blade/footer.php'; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>