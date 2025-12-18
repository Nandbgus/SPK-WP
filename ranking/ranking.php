<?php
// koneksi
include '../tools/connection.php';
// header
include '../blade/header.php';

// ==========================================
// FUNGSI RANK ORDER CENTROID (ROC)
// ==========================================
function getBobotROC($totalKriteria)
{
    $bobotROC = [];
    // Loop untuk setiap ranking (k)
    for ($k = 1; $k <= $totalKriteria; $k++) {
        $sum = 0;
        // Rumus ROC: Penjumlahan pecahan harmoni
        for ($i = $k; $i <= $totalKriteria; $i++) {
            $sum += (1 / $i);
        }
        // Hasil dibagi total kriteria
        $bobotROC[$k] = $sum / $totalKriteria;
    }
    return $bobotROC;
}

// 1. PERSIAPAN DATA KRITERIA & HITUNG ROC
// Ambil data kriteria dari database
$q_kriteria = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_id ASC");
// Catatan: Pastikan urutan 'ORDER BY' sesuai prioritas yang diinginkan (Ranking 1, 2, dst)

$data_kriteria = [];
while ($row = $q_kriteria->fetch_assoc()) {
    $data_kriteria[] = $row;
}

// Hitung ROC berdasarkan jumlah data
$jumlahKriteria = count($data_kriteria);
$nilaiROC = getBobotROC($jumlahKriteria);

// Masukkan Nilai ROC ke dalam Array Kriteria
foreach ($data_kriteria as $index => $kriteria) {
    $rank = $index + 1; // Ranking dimulai dari 1
    $data_kriteria[$index]['bobot_roc'] = $nilaiROC[$rank];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan (Metode ROC-WP)</title>

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

        /* Styling Tabel */
        .table {
            color: #fff;
            vertical-align: middle;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .table thead {
            background-color: rgba(0, 0, 0, 0.2);
            color: #fff;
            border-bottom: 2px solid rgba(255, 255, 255, 0.4);
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        /* Section Title */
        .section-title {
            background: rgba(0, 0, 0, 0.15);
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-weight: 700;
            border-left: 5px solid #fff;
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

                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light pb-3">
                        <div>
                            <h3 class="mb-0">Hasil Perhitungan</h3>
                            <small class="text-white opacity-75">Metode Rank Order Centroid (ROC) & Weighted Product (WP)</small>
                        </div>
                        <button type="button" class="btn btn-light text-primary shadow-sm fw-bold" onclick="window.open('../cetak/cetakPDF.php', '_blank')">
                            <i class="bi bi-printer-fill"></i> Cetak PDF
                        </button>
                    </div>

                    <?php
                    $array_vector_si = array();
                    $ranks = array();
                    ?>

                    <div class="mb-5">
                        <div class="section-title">1. Tabel Bobot Kriteria (Metode ROC)</div>
                        <div class="alert alert-light bg-opacity-25 text-white p-2 mb-3 shadow-sm">
                            <small><i class="bi bi-info-circle"></i> Bobot dihitung otomatis berdasarkan urutan prioritas kriteria.</small>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>Prioritas</th>
                                        <th>Kode</th>
                                        <th>Nama Kriteria</th>
                                        <th>Kategori</th>
                                        <th>Bobot ROC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_kriteria as $index => $row): ?>
                                        <tr>
                                            <td><span class="badge bg-warning text-dark">Ranking <?= $index + 1 ?></span></td>
                                            <td><?= $row['kriteria_kode'] ?></td>
                                            <td class="text-start ps-4"><?= $row['kriteria_nama'] ?></td>
                                            <td>
                                                <?php if ($row['kriteria_kategori'] == 'benefit'): ?>
                                                    <span class="badge bg-success">Benefit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Cost</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold text-warning"><?= number_format($row['bobot_roc'], 4) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">2. Tabel Perhitungan Vektor Si (WP)</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <th colspan="<?= count($data_kriteria); ?>">Pangkat Bobot ROC per Kriteria</th>
                                        <th rowspan="2" class="bg-primary text-white bg-opacity-50">Nilai Vektor Si</th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($data_kriteria as $k): ?>
                                            <th><small><?= $k['kriteria_kode']; ?></small></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    $nilai_vector_si = 0;

                                    // Array bantu penyimpanan S Vector untuk tabel berikutnya
                                    $s_vector_storage = [];

                                    while ($alternatif = $query_alternatif->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-3 fw-bold"><?= $alternatif['alternatif_nama'] ?></td>

                                            <?php
                                            $total_nilai_vektor = 1;
                                            $alternatifKode = $alternatif['alternatif_kode'];

                                            // LOOP PER KRITERIA MENGGUNAKAN DATA ROC
                                            foreach ($data_kriteria as $curr_kriteria) {
                                                $kriteriaKode = $curr_kriteria['kriteria_kode'];

                                                // Ambil nilai faktor (nilai asli)
                                                $q_faktor = $conn->query("SELECT nilai_faktor FROM tb_faktor WHERE alternatif_kode='$alternatifKode' AND kriteria_kode='$kriteriaKode'");
                                                $d_faktor = $q_faktor->fetch_assoc();
                                                $nilai_faktor = $d_faktor['nilai_faktor'] ?? 0;

                                                // Ambil Bobot ROC
                                                $bobotFinal = $curr_kriteria['bobot_roc'];

                                                // Cek Cost / Benefit untuk Pangkat
                                                if ($curr_kriteria['kriteria_kategori'] == "cost") {
                                                    $bobotFinal = $bobotFinal * -1;
                                                }

                                                // Rumus Pangkat
                                                // Mencegah error jika nilai 0 dipangkatkan negatif
                                                if ($nilai_faktor == 0) $nilai_faktor = 0.0001;

                                                $nilai_pangkat = pow($nilai_faktor, $bobotFinal);
                                                $total_nilai_vektor *= $nilai_pangkat;
                                            ?>

                                                <td><?= number_format($nilai_pangkat, 4); ?></td>

                                            <?php } ?>

                                            <td class="fw-bold bg-primary text-white bg-opacity-25"><?= number_format($total_nilai_vektor, 4); ?></td>

                                            <?php
                                            // Akumulasi Sigma S
                                            $nilai_vector_si += $total_nilai_vektor;
                                            $s_vector_storage[$alternatifKode] = $total_nilai_vektor;
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        // Total Sigma S
                        $jumlah_vektor_total = ($nilai_vector_si == 0) ? 1 : $nilai_vector_si;
                        ?>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">3. Tabel Perhitungan Vektor Vi (Preferensi)</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Alternatif</th>
                                        <th>Nilai Vektor Si</th>
                                        <th>Total Sigma Si</th>
                                        <th class="bg-success text-white bg-opacity-75">Nilai Vektor Vi (Hasil)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $query_alternatif->fetch_assoc()) {
                                        $kode = $alternatif['alternatif_kode'];
                                        $s_val = $s_vector_storage[$kode] ?? 0;

                                        // Rumus Vi = Si / Sigma Si
                                        $nilai_wp = $s_val / $jumlah_vektor_total;

                                        // Simpan ke array untuk ranking
                                        $rank['nilaiWP'] = $nilai_wp;
                                        $rank['alternatifNama'] = $alternatif['alternatif_nama'];
                                        $rank['alternatifKode'] = $alternatif['alternatif_kode'];
                                        array_push($ranks, $rank);
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-3"><?= $alternatif['alternatif_nama'] ?></td>
                                            <td><?= number_format($s_val, 4); ?></td>
                                            <td><?= number_format($jumlah_vektor_total, 4); ?></td>
                                            <td class="bg-success text-white bg-opacity-50 fw-bold"><?= number_format($nilai_wp, 4); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="section-title bg-warning text-dark border-start border-dark">4. Hasil Perangkingan Akhir</div>
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
                                    rsort($ranks); // Urutkan array dari besar ke kecil (DESC)

                                    // === LOGIC INSERT HISTORY (Hanya Juara 1) ===
                                    if (count($ranks) > 0) {
                                        $top = $ranks[0];
                                        $nama  = $top['alternatifNama'];
                                        $nilai = $top['nilaiWP'];

                                        // Cek apakah sudah ada data hari ini agar tidak double insert saat refresh (Opsional)
                                        // $cek = $conn->query("SELECT * FROM riwayat_perhitungan WHERE alternatif_tertinggi = '$nama' AND nilai_tertinggi = '$nilai' AND date(tanggal) = date(now())");
                                        // if($cek->num_rows == 0) { ... }

                                        // Insert database
                                        $conn->query("INSERT INTO riwayat_perhitungan (tanggal, alternatif_tertinggi, nilai_tertinggi) VALUES (NOW(), '$nama', '$nilai')");
                                    }

                                    foreach ($ranks as $r) {
                                    ?>
                                        <tr class="<?= ($ranking == 1) ? 'bg-warning bg-opacity-25' : '' ?>">
                                            <td>
                                                <?php if ($ranking == 1): ?>
                                                    <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-circle"><?= $ranking; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $r['alternatifKode']; ?></td>
                                            <td class="fw-bold"><?= $r['alternatifNama']; ?></td>
                                            <td><?= number_format($r['nilaiWP'], 4); ?></td>
                                            <td>
                                                <?php if ($ranking <= 3): ?>
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