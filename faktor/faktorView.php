<?php
include '../tools/connection.php';
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Nilai Faktor</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #89f7fe, #66a6ff);
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      padding-bottom: 50px;
      color: #333;
    }

    nav.navbar {
      background: #ffffff !important;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 40px;
    }

    .main-card {
      background: rgba(255, 255, 255, 0.85);
      /* Putih Susu */
      backdrop-filter: blur(20px);
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 30px;
      color: #333;
    }

    h3 {
      font-weight: 700;
      color: #0d6efd;
    }

    /* TABEL */
    .table {
      color: #333 !important;
      border-color: #dee2e6;
    }

    .table thead {
      background-color: #0d6efd;
      color: #fff;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
      background-color: rgba(0, 0, 0, 0.02) !important;
      color: #333 !important;
    }

    /* DATA TABLES OVERRIDE */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      color: #333 !important;
    }

    /* Styling Modal agar Konsisten */
    .modal-content {
      color: #333;
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      border-bottom: 1px solid #eee;
    }

    .modal-footer {
      border-top: 1px solid #eee;
    }
  </style>
</head>

<body>

  <?php include '../blade/nav.php'; ?>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-11">
        <div class="main-card mt-4">

          <div class="mb-4 text-center">
            <?php include '../blade/namaProgram.php'; ?>
          </div>

          <h3 class="text-center mb-4">Data Nilai Faktor (Input Nilai)</h3>

          <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary shadow-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Input Nilai Baru
            </button>
          </div>

          <div class="table-responsive">
            <table id="tableFaktor" class="table table-striped table-hover text-center align-middle shadow-sm" style="width:100%">
              <thead class="bg-primary text-white">
                <tr>
                  <th>No</th>
                  <th>Alternatif</th>
                  <?php
                  // Header Kriteria Dinamis
                  $kri = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
                  $kriteriaList = [];
                  while ($row = $kri->fetch_assoc()) {
                    $kriteriaList[] = $row["kriteria_kode"];
                    echo "<th>{$row['kriteria_nama']}</th>";
                  }
                  ?>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $alt = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
                $no = 1;

                while ($a = $alt->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>$no</td>";
                  echo "<td class='fw-bold text-start ps-4'>{$a['alternatif_nama']}</td>";

                  foreach ($kriteriaList as $kodeKri) {
                    $nilai = $conn->query("SELECT nilai_faktor FROM tb_faktor WHERE alternatif_kode='{$a['alternatif_kode']}' AND kriteria_kode='$kodeKri'")->fetch_assoc();
                    $val = $nilai['nilai_faktor'] ?? "-";
                    echo "<td>$val</td>";
                  }

                  echo "<td>
                          <button class='btn btn-warning btn-sm text-dark' data-bs-toggle='modal' data-bs-target='#modalEdit{$a['alternatif_kode']}'><i class='bi bi-pencil-square'></i></button>
                          <a href='faktorDelete.php?id={$a['alternatif_kode']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus semua nilai untuk alternatif ini?')\"><i class='bi bi-trash'></i></a>
                        </td>";
                  echo "</tr>";
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Input Nilai Faktor Baru</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="faktorAdd.php">

            <div class="mb-4">
              <label class="form-label fw-bold">Pilih Alternatif / Kandidat</label>
              <select class="form-select bg-light" name="altKode" required>
                <option value="" disabled selected>-- Pilih Alternatif --</option>
                <?php
                // Ambil alternatif yang belum punya nilai (Opsional, hapus WHERE jika ingin dobel)
                // $sqlAlt = "SELECT * FROM ta_alternatif WHERE alternatif_kode NOT IN (SELECT DISTINCT alternatif_kode FROM tb_faktor)";
                $sqlAlt = "SELECT * FROM ta_alternatif";
                $qAlt = $conn->query($sqlAlt);
                while ($rowA = $qAlt->fetch_assoc()) {
                  echo "<option value='" . $rowA['alternatif_kode'] . "'>" . $rowA['alternatif_kode'] . " - " . $rowA['alternatif_nama'] . "</option>";
                }
                ?>
              </select>
            </div>

            <hr>
            <h6 class="mb-3 text-primary">Isi Nilai Kriteria:</h6>

            <div class="row">
              <?php
              $qKri = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
              while ($k = $qKri->fetch_assoc()) {
              ?>
                <div class="col-md-6 mb-3">
                  <label class="form-label"><?= $k['kriteria_nama'] ?> (<?= $k['kriteria_kode'] ?>)</label>
                  <input type="number" step="0.01" class="form-control" name="nilai[<?= $k['kriteria_kode'] ?>]" placeholder="Nilai 1-5" required>
                </div>
              <?php } ?>
            </div>

            <div class="text-end mt-3">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" name="save">Simpan Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php
  // Kita perlu meloop ulang data Alternatif untuk membuat modal edit masing-masing
  $qAltEdit = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
  while ($altData = $qAltEdit->fetch_assoc()) {
    $kodeAlt = $altData['alternatif_kode'];
  ?>
    <div class="modal fade" id="modalEdit<?= $kodeAlt ?>" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Nilai: <strong><?= $altData['alternatif_nama'] ?></strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="faktorEdit.php">
              <input type="hidden" name="altKode" value="<?= $kodeAlt ?>">

              <div class="row">
                <?php
                // Loop Kriteria lagi untuk form edit
                $qKriEdit = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
                while ($kEdit = $qKriEdit->fetch_assoc()) {
                  $kodeKri = $kEdit['kriteria_kode'];

                  // Ambil nilai lama
                  $qNilai = $conn->query("SELECT nilai_faktor FROM tb_faktor WHERE alternatif_kode='$kodeAlt' AND kriteria_kode='$kodeKri'");
                  $dNilai = $qNilai->fetch_assoc();
                  $nilaiLama = $dNilai['nilai_faktor'] ?? '';
                ?>
                  <div class="col-md-6 mb-3">
                    <label class="form-label"><?= $kEdit['kriteria_nama'] ?></label>
                    <input type="number" step="0.01" class="form-control" name="nilai[<?= $kodeKri ?>]" value="<?= $nilaiLama ?>" required>
                  </div>
                <?php } ?>
              </div>

              <div class="text-end mt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning" name="update">Update Data</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php include '../blade/footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tableFaktor').DataTable({
        scrollX: true,
        language: {
          search: "Cari:",
          zeroRecords: "Data tidak ditemukan"
        }
      });
    });
  </script>
</body>

</html>