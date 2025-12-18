<?php
include '../tools/connection.php';
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Sub-Kriteria</title>

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

    /* DataTables overrides for Glass Theme */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
      color: #fff !important;
      margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
      color: rgba(255, 255, 255, 0.5) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
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

          <h3 class="text-center mb-4 font-weight-bold">Data Sub-Kriteria</h3>

          <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-light text-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Tambah Sub-Kriteria
            </button>
          </div>

          <div class="table-responsive">
            <table id="tableSubKriteria" class="table table-striped table-hover align-middle text-center" style="width:100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kriteria</th>
                  <th>Kode Sub</th>
                  <th>Keterangan</th>
                  <th>Bobot</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_subkriteria 
                                                    INNER JOIN ta_kriteria 
                                                    ON ta_subkriteria.kriteria_kode = ta_kriteria.kriteria_kode");
                $no = 1;
                while ($row = $data->fetch_assoc()) { ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="fw-bold"><?= $row['kriteria_nama']; ?></td>
                    <td><span class="badge bg-secondary"><?= $row['subkriteria_kode']; ?></span></td>
                    <td><?= $row['subkriteria_keterangan']; ?></td>
                    <td><span class="badge bg-light text-dark"><?= $row['subkriteria_bobot']; ?></span></td>
                    <td>
                      <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['subkriteria_id'] ?>"><i class="bi bi-pencil-square"></i></button>
                      <a href="subkriteriaDelete.php?id=<?= $row['subkriteria_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash"></i></a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Tambah Data Sub-Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="subkriteriaAdd.php">
            <?php
            $q = $conn->query("SELECT * FROM ta_subkriteria ORDER BY subkriteria_id DESC LIMIT 1");
            $kode = 'S01';
            if (mysqli_num_rows($q) > 0) {
              $row = $q->fetch_assoc();
              $next = (int)$row['subkriteria_id'] + 1;
              $kode = ($next < 10) ? 'S0' . $next : 'S' . $next;
            }
            ?>
            <div class="mb-3">
              <label class="form-label">Kode Sub-Kriteria</label>
              <input type="text" class="form-control" name="subkriKode" value="<?= $kode ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Kriteria</label>
              <select class="form-select" name="kriKode" required>
                <option value="" disabled selected>Pilih Kriteria...</option>
                <?php
                $kri = $conn->query("SELECT * FROM ta_kriteria");
                while ($kr = $kri->fetch_assoc()) { ?>
                  <option value="<?= $kr['kriteria_kode']; ?>">
                    <?= $kr['kriteria_kode'] . ' - ' . $kr['kriteria_nama']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <input type="text" class="form-control" name="subkriKeterangan" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Bobot</label>
              <input type="number" step="0.01" class="form-control" name="subkriBobot" required>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary" name="save">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php
  $data = $conn->query("SELECT * FROM ta_subkriteria ORDER BY subkriteria_id");
  while ($row = $data->fetch_assoc()) { ?>
    <div class="modal fade" id="modalEdit<?= $row['subkriteria_id'] ?>" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Data Sub-Kriteria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="subkriteriaEdit.php">
              <input type="hidden" name="subkriId" value="<?= $row['subkriteria_id'] ?>">
              <div class="mb-3">
                <label class="form-label">Kode Sub-Kriteria</label>
                <input type="text" class="form-control" name="subkriKode" value="<?= $row['subkriteria_kode'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Kriteria</label>
                <select class="form-select" name="kriKode">
                  <?php
                  $k = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
                  while ($kr = $k->fetch_assoc()) { ?>
                    <option value="<?= $kr['kriteria_kode']; ?>" <?= ($kr['kriteria_kode'] == $row['kriteria_kode']) ? 'selected' : ''; ?>>
                      <?= $kr['kriteria_kode'] . ' - ' . $kr['kriteria_nama']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" class="form-control" name="subkriKeterangan" value="<?= $row['subkriteria_keterangan'] ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Bobot</label>
                <input type="number" step="0.01" class="form-control" name="subkriBobot" value="<?= $row['subkriteria_bobot'] ?>">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-warning text-white" name="update">Update</button>
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
      $('#tableSubKriteria').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 20],
        language: {
          search: "Cari:",
          zeroRecords: "Tidak ada data"
        }
      });
    });
  </script>
</body>

</html>