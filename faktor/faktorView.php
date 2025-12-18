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
    }

    nav.navbar {
      background: rgba(255, 255, 255, 0.9) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 40px;
    }

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

    h3 {
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .table {
      color: #fff !important;
      vertical-align: middle;
      border-color: rgba(255, 255, 255, 0.3);
    }

    .table thead {
      background-color: rgba(0, 0, 0, 0.2);
      color: #fff;
      border-bottom: 2px solid rgba(255, 255, 255, 0.4);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.2) !important;
      color: #fff;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
      background-color: rgba(255, 255, 255, 0.05) !important;
      color: #fff !important;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      color: #fff !important;
    }

    .modal-content {
      color: #333;
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

          <h3 class="text-center mb-4 font-weight-bold">Data Nilai Faktor</h3>

          <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-light text-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Tambah Nilai Faktor
            </button>
          </div>

          <div class="table-responsive">
            <table id="tableFaktor" class="table table-striped table-hover text-center" style="width:100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Alternatif</th>
                  <?php
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
                                        <button class='btn btn-warning btn-sm text-white' data-bs-toggle='modal' data-bs-target='#modalEdit{$a['alternatif_kode']}'><i class='bi bi-pencil-square'></i></button>
                                        <a href='faktorDelete.php?id={$a['alternatif_kode']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus data ini?')\"><i class='bi bi-trash'></i></a>
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