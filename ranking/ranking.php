<?php
// koneksi
include '../tools/connection.php';
// header
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan & Ranking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 50px;
        }

        /* Navbar Transparan */
        nav.navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        /* Kartu Glassmorphism */
        .main-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
            padding: 30px;
            color: #fff;
        }

        h3,
        h4,
        p {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Styling Tabel Custom */
        .table {
            color: #fff;
            vertical-align: middle;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .table thead {
            background-color: rgba(0, 0, 0, 0.2);
            color: #fff;
            border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Striped effect halus */
        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        /* Tombol */
        .btn {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
        }

        .section-title {
            background: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>

<body>

    <?php include '../blade/nav.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                <div class="main-card mt-4">

                    <div class="mb-4 text-center">
                        <?php include '../blade/namaProgram.php'; ?>
                    </div>

                    <?php
                    $array_vector_si = array();
                    $ranks = array();
                    ?>

                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light pb-3">
                        <h3 class="mb-0">Hasil Akhir & Perangkingan</h3>
                        <button type="button" class="btn btn-light text-primary shadow-sm" onclick="window.open('../cetak/cetakPDF.php', '_blank')">
                            <i class="bi bi-printer-fill"></i> Cetak PDF
                        </button>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">1. Tabel Perbaikan Bobot Kriteria</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kriteria</th>
                                        <th>Kode</th>
                                        <th>Kategori</th>
                                        <th>Bobot Awal</th>
                                        <th>Bobot Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_kriteria = $conn->query("SELECT * FROM ta_kriteria");
                                    $no = 1;
                                    while ($kriteria = $query_kriteria->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-4"><?= $kriteria['kriteria_nama'] ?></td>
                                            <td><span class="badge bg-info text-dark"><?= $kriteria['kriteria_kode'] ?></span></td>
                                            <td><?= strtoupper($kriteria['kriteria_kategori']) ?></td>
                                            <td><?= $kriteria['kriteria_bobot'] ?></td>
                                            <?php
                                            $sql_sum = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                            while ($kriteriaBobot_total = $sql_sum->fetch_assoc()) { ?>
                                                <td class="fw-bold"><?= number_format($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot'], 4) ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">2. Tabel Perhitungan Vektor Si</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="align-middle">No</th>
                                        <th rowspan="2" class="align-middle">Nama Alternatif</th>
                                        <?php
                                        $query_kriteria = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($query_kriteria);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Pangkat Bobot per Kriteria</th>
                                        <th rowspan="2" class="align-middle bg-primary text-white">Nilai Vektor Si</th>
                                    </tr>
                                    <tr>
                                        <?php
                                        $query_alternatif = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $query_alternatif->fetch_assoc()) { ?>
                                            <th><?= $kriteria['kriteria_kode']; ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    $nilai_vector_si = 0;
                                    while ($alternatif = $query_alternatif->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-3 fw-bold"><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php $total_nilai_vektor = 1; ?>

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $query_faktor = $conn->query("SELECT * FROM tb_faktor WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_faktor = $query_faktor->fetch_assoc()) {
                                                $kriteriaKode = $data_faktor['kriteria_kode'];
                                                $query_kriteria_faktor = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $query_kriteria_faktor->fetch_assoc()) {
                                                    $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                    $kriteriaBobot_total = $sql->fetch_assoc();

                                                    if ($kriteria['kriteria_kategori'] == "benefit") {
                                                        $nilai_vektor = $data_faktor['nilai_faktor'] ** ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot']);
                                                    } else {
                                                        $nilai_vektor = $data_faktor['nilai_faktor'] ** (-1 * ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot']));
                                                    }

                                                    $total_nilai_vektor *= $nilai_vektor;
                                            ?>
                                                    <td><?= number_format($nilai_vektor, 4); ?></td>
                                            <?php }
                                            } ?>

                                            <td class="fw-bold bg-primary text-white bg-opacity-25"><?= number_format($total_nilai_vektor, 4); ?></td>

                                            <?php
                                            $nilai_vector_si += $total_nilai_vektor;
                                            $vector_si['jumlah_semua_vector'] = $nilai_vector_si;
                                            array_push($array_vector_si, $vector_si);
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $array_vector_total = array();
                        if (!empty($array_vector_si)) {
                            array_push($array_vector_total, end($array_vector_si[count($array_vector_si) - 1]));
                            $jumlah_vektor_total = end($array_vector_total);
                        } else {
                            $jumlah_vektor_total = 1; // Default avoid division by zero
                        }
                        ?>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">3. Tabel Perhitungan Vektor Vi (Preferensi)</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Alternatif</th>
                                        <th>Nilai Vektor Si</th>
                                        <th>Total Sigma Si</th>
                                        <th class="bg-success text-white">Nilai Vektor Vi (WP)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $query_alternatif->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-3"><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php
                                            $total_nilai_vektor = 1;
                                            // Re-calculate Vektor S for Display (Logic sama seperti diatas)
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $query_faktor = $conn->query("SELECT * FROM tb_faktor WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_faktor = $query_faktor->fetch_assoc()) {
                                                $kriteriaKode = $data_faktor['kriteria_kode'];
                                                $q_krit = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode'");
                                                while ($k = $q_krit->fetch_assoc()) {
                                                    $q_sum = $conn->query("SELECT SUM(kriteria_bobot) as total FROM ta_kriteria");
                                                    $sum = $q_sum->fetch_assoc();
                                                    if ($k['kriteria_kategori'] == "benefit") {
                                                        $nv = $data_faktor['nilai_faktor'] ** ($k['kriteria_bobot'] / $sum['total']);
                                                    } else {
                                                        $nv = $data_faktor['nilai_faktor'] ** (-1 * ($k['kriteria_bobot'] / $sum['total']));
                                                    }
                                                    $total_nilai_vektor *= $nv;
                                                }
                                            }

                                            // Hitung Vi
                                            $nilai_wp = $total_nilai_vektor / $jumlah_vektor_total;
                                            ?>

                                            <td><?= number_format($total_nilai_vektor, 4); ?></td>
                                            <td><?= number_format($jumlah_vektor_total, 4); ?></td>
                                            <td class="bg-success text-white bg-opacity-50 fw-bold"><?= number_format($nilai_wp, 4); ?></td>

                                            <?php
                                            // Masukan ke array Ranking
                                            $rank['nilaiWP'] = $nilai_wp;
                                            $rank['alternatifNama'] = $alternatif['alternatif_nama'];
                                            $rank['alternatifKode'] = $alternatif['alternatif_kode'];
                                            array_push($ranks, $rank);
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="section-title bg-warning text-dark">4. Hasil Perangkingan Akhir</div>
                        <div class="table-responsive">
                            <table class="table table-hover text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Kode</th>
                                        <th>Nama Alternatif</th>
                                        <th>Nilai Akhir (WP)</th>
                                        <th>Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ranking = 1;
                                    rsort($ranks); // Urutkan array dari besar ke kecil

                                    // === LOGIC INSERT HISTORY (Hanya Juara 1) ===
                                    if (count($ranks) > 0) {
                                        $top = $ranks[0];
                                        $nama  = $top['alternatifNama'];
                                        $nilai = $top['nilaiWP'];
                                        // Insert database (Hati-hati: ini akan insert setiap refresh)
                                        $conn->query("INSERT INTO riwayat_perhitungan (tanggal, alternatif_tertinggi, nilai_tertinggi) VALUES (NOW(), '$nama', '$nilai')");
                                    }

                                    foreach ($ranks as $r) {
                                    ?>
                                        <tr class="<?= ($ranking == 1) ? 'bg-warning bg-opacity-25' : '' ?>">
                                            <td>
                                                <?php if ($ranking == 1): ?>
                                                    <i class="bi bi-trophy-fill text-warning fs-5"></i> 1
                                                <?php else: ?>
                                                    <?= $ranking; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $r['alternatifKode']; ?></td>
                                            <td class="fw-bold"><?= $r['alternatifNama']; ?></td>
                                            <td><?= number_format($r['nilaiWP'], 4); ?></td>
                                            <td>
                                                <?php if ($ranking <= 3): // Contoh Top 3 
                                                ?>
                                                    <span class="badge bg-success rounded-pill">Sangat Direkomendasikan</span>
                                                <?php elseif ($ranking <= 5): ?>
                                                    <span class="badge bg-info rounded-pill text-dark">Direkomendasikan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-pill">Belum Direkomendasikan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $ranking++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4 border-top border-light pt-3 opacity-75">
                        <?php include '../blade/footer.php' ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>