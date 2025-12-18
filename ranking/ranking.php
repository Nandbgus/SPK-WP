<?php
// koneksi
include '../tools/connection.php';
// header
include '../blade/header.php';

// ==========================================
// 1. FUNGSI & LOGIC ROC (Rank Order Centroid)
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

// Ambil data kriteria dari database
// PENTING: Diasumsikan urutan di database sudah sesuai prioritas (ID 1 = Prioritas 1, dst)
// Jika ingin manual, pastikan ada kolom 'urutan_prioritas' dan ubah query jadi ORDER BY urutan_prioritas ASC
$q_kriteria = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_id ASC");

$data_kriteria = [];
while ($row = $q_kriteria->fetch_assoc()) {
    $data_kriteria[] = $row;
}

// Hitung Bobot ROC
$jumlahKriteria = count($data_kriteria);
$nilaiROC = getBobotROC($jumlahKriteria);

// Masukkan Nilai ROC ke dalam Array Kriteria
foreach ($data_kriteria as $index => $kriteria) {
    $urutan = $index + 1; // Ranking dimulai dari 1
    $data_kriteria[$index]['bobot_roc'] = $nilaiROC[$urutan];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan (ROC-WP)</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            /* Background Gradient */
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 50px;
            color: #333;
            /* Default text color: Dark Grey */
        }

        /* Navbar Putih Bersih */
        nav.navbar {
            background: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }

        /* Main Card: Putih Susu (Milky Glass) - High Opacity agar teks jelas */
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            color: #333;
            /* Text hitam */
        }

        /* Typography */
        h3 {
            font-weight: 700;
            color: #0d6efd;
            /* Bootstrap Primary Blue */
            text-shadow: none;
        }

        .section-title {
            background: #e9ecef;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 700;
            color: #495057;
            border-left: 5px solid #0d6efd;
        }

        /* Styling Tabel yang Jelas */
        .table {
            color: #333 !important;
            vertical-align: middle;
            background-color: #fff;
        }

        .table thead {
            background-color: #0d6efd;
            /* Header Biru */
            color: #fff;
            /* Teks Header Putih */
            text-align: center;
            border-bottom: none;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(0, 0, 0, 0.03);
            /* Zebra striping halus */
            color: #333;
        }

        .table-hover tbody tr:hover>* {
            background-color: rgba(13, 110, 253, 0.1);
            /* Hover biru muda */
        }

        /* Buttons */
        .btn {
            border-radius: 50px;
            font-weight: 600;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
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

                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <div>
                            <h3 class="mb-0">Hasil Perhitungan</h3>
                            <small class="text-muted">Metode Rank Order Centroid (ROC) & Weighted Product (WP)</small>
                        </div>
                        <button type="button" class="btn btn-primary shadow-sm" onclick="window.open('../cetak/cetakPDF.php', '_blank')">
                            <i class="bi bi-printer-fill"></i> Cetak PDF
                        </button>
                    </div>

                    <?php
                    $array_vector_si = array();
                    $ranks = array();
                    ?>

                    <div class="mb-5">
                        <div class="section-title">1. Tabel Bobot Kriteria (Metode ROC)</div>
                        <div class="alert alert-primary py-2 mb-3 shadow-sm">
                            <small><i class="bi bi-info-circle-fill"></i> Bobot dihitung otomatis berdasarkan urutan prioritas kriteria di database.</small>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle shadow-sm rounded overflow-hidden">
                                <thead class="bg-primary text-white">
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
                                            <td><span class="fw-bold"><?= $row['kriteria_kode'] ?></span></td>
                                            <td class="text-start ps-4"><?= $row['kriteria_nama'] ?></td>
                                            <td>
                                                <?php if ($row['kriteria_kategori'] == 'benefit'): ?>
                                                    <span class="badge bg-success">Benefit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Cost</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-bold text-primary"><?= number_format($row['bobot_roc'], 4) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">2. Tabel Perhitungan Vektor Si (WP)</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle shadow-sm rounded overflow-hidden">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th rowspan="2" class="align-middle">No</th>
                                        <th rowspan="2" class="align-middle">Nama Alternatif</th>
                                        <th colspan="<?= count($data_kriteria); ?>" class="align-middle">Pangkat (Nilai ^ Bobot ROC)</th>
                                        <th rowspan="2" class="align-middle bg-info text-white">Nilai Vektor Si</th>
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

                                            // === LOGIC UTAMA WP DENGAN ROC ===
                                            foreach ($data_kriteria as $curr_kriteria) {
                                                $kriteriaKode = $curr_kriteria['kriteria_kode'];

                                                // 1. Ambil nilai faktor (nilai asli)
                                                $q_faktor = $conn->query("SELECT nilai_faktor FROM tb_faktor WHERE alternatif_kode='$alternatifKode' AND kriteria_kode='$kriteriaKode'");
                                                $d_faktor = $q_faktor->fetch_assoc();
                                                $nilai_faktor = $d_faktor['nilai_faktor'] ?? 0;

                                                // 2. Ambil Bobot dari hasil ROC
                                                $bobotFinal = $curr_kriteria['bobot_roc'];

                                                // 3. Cek Cost / Benefit untuk Pangkat
                                                if ($curr_kriteria['kriteria_kategori'] == "cost") {
                                                    $bobotFinal = $bobotFinal * -1;
                                                }

                                                // 4. Hitung Pangkat
                                                // Mencegah error jika nilai 0 dipangkatkan negatif
                                                if ($nilai_faktor == 0) $nilai_faktor = 0.0001;

                                                $nilai_pangkat = pow($nilai_faktor, $bobotFinal);
                                                $total_nilai_vektor *= $nilai_pangkat;
                                            ?>

                                                <td class="text-muted"><small><?= number_format($nilai_pangkat, 4); ?></small></td>

                                            <?php } ?>

                                            <td class="fw-bold bg-info bg-opacity-10 text-dark"><?= number_format($total_nilai_vektor, 4); ?></td>

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
                        // Total Sigma S untuk pembagi Vi
                        $jumlah_vektor_total = ($nilai_vector_si == 0) ? 1 : $nilai_vector_si;
                        ?>
                    </div>

                    <div class="mb-5">
                        <div class="section-title">3. Tabel Perhitungan Vektor Vi (Preferensi)</div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle shadow-sm rounded overflow-hidden">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>Alternatif</th>
                                        <th>Nilai Vektor Si</th>
                                        <th>Total Sigma Si</th>
                                        <th class="bg-success text-white">Nilai Vektor Vi (Hasil Akhir)</th>
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

                                        // === FIX ERROR SCALAR VALUE DISINI ===
                                        // Gunakan variabel baru $data_rank, JANGAN gunakan $rank (karena $rank dipakai di loop atas sebagai integer)
                                        $data_rank = [];
                                        $data_rank['nilaiWP'] = $nilai_wp;
                                        $data_rank['alternatifNama'] = $alternatif['alternatif_nama'];
                                        $data_rank['alternatifKode'] = $alternatif['alternatif_kode'];

                                        // Masukkan ke array utama
                                        array_push($ranks, $data_rank);
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-start ps-3"><?= $alternatif['alternatif_nama'] ?></td>
                                            <td><?= number_format($s_val, 4); ?></td>
                                            <td><?= number_format($jumlah_vektor_total, 4); ?></td>
                                            <td class="bg-success bg-opacity-10 text-success fw-bold"><?= number_format($nilai_wp, 4); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="section-title bg-warning text-dark border-start border-dark">4. Hasil Perangkingan Akhir</div>
                        <div class="table-responsive">
                            <table class="table table-hover text-center align-middle shadow-sm rounded overflow-hidden">
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
                                    $nomor_rank = 1; // Ubah nama variabel agar aman
                                    rsort($ranks); // Urutkan array dari besar ke kecil (DESC)

                                    // === LOGIC INSERT HISTORY (Hanya Juara 1) ===
                                    if (count($ranks) > 0) {
                                        $top = $ranks[0];
                                        $nama  = $top['alternatifNama'];
                                        $nilai = $top['nilaiWP'];

                                        // Insert database
                                        $conn->query("INSERT INTO riwayat_perhitungan (tanggal, alternatif_tertinggi, nilai_tertinggi) VALUES (NOW(), '$nama', '$nilai')");
                                    }

                                    foreach ($ranks as $r) {
                                    ?>
                                        <tr class="<?= ($nomor_rank == 1) ? 'table-warning' : '' ?>">
                                            <td>
                                                <?php if ($nomor_rank == 1): ?>
                                                    <i class="bi bi-trophy-fill text-warning fs-4"></i>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-circle" style="width: 25px; height: 25px; line-height: 18px;"><?= $nomor_rank; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $r['alternatifKode']; ?></td>
                                            <td class="fw-bold"><?= $r['alternatifNama']; ?></td>
                                            <td><?= number_format($r['nilaiWP'], 4); ?></td>
                                            <td>
                                                <?php if ($nomor_rank <= 3): ?>
                                                    <span class="badge bg-success rounded-pill">Sangat Direkomendasikan</span>
                                                <?php elseif ($nomor_rank <= 5): ?>
                                                    <span class="badge bg-info rounded-pill text-dark">Direkomendasikan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-pill">Belum Direkomendasikan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $nomor_rank++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4 border-top pt-3 opacity-75">
                        <?php include '../blade/footer.php' ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>