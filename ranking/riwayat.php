<?php
$page = 'riwayat';
include '../tools/connection.php';

// Hapus semua riwayat
if (isset($_GET['clear'])) {
    $conn->query("TRUNCATE TABLE riwayat_perhitungan");
    header("Location: riwayat.php");
    exit;
}

// Ambil data riwayat
$result = $conn->query("SELECT * FROM riwayat_perhitungan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Perhitungan - WP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            padding: 30px;
            margin-top: 40px;
            color: #fff;
        }

        .table {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: rgba(255, 255, 255, 0.3);
            color: #000;
        }

        .btn-hapus {
            background: #ff4e4e;
            border: none;
            font-weight: 600;
        }

        .btn-hapus:hover {
            background: #d63030;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="main-card">

        <!-- Judul -->
        <h3 class="text-center fw-bold mb-4">Riwayat Perhitungan Metode WP</h3>

        <!-- Tombol hapus -->
        <div class="text-end mb-3">
            <a href="riwayat.php?clear=1" class="btn btn-hapus btn-sm"
               onclick="return confirm('Yakin ingin menghapus semua riwayat?')">
               Hapus Semua Riwayat
            </a>
        </div>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-striped text-white">
                <thead>
                    <tr class="table-warning text-dark">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Alternatif Tertinggi</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['tanggal']; ?></td>
                        <td><?= $row['alternatif_tertinggi'] ?: '-' ?></td>
                        <td><?= number_format($row['nilai_tertinggi'], 4); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>

</html>
