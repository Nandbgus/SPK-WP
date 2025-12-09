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

  <!-- Bootstrap & DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #89f7fe, #66a6ff);
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 0;
    }

    .main-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
      width: 95%;
      max-width: 1150px;
      color: #fff;
      padding: 25px;
    }

    h3 {
      font-weight: 600;
      color: #fff;
      text-align: center;
      margin-bottom: 20px;
    }

    .table thead {
      background-color: #0dcaf0;
      color: #fff;
      text-align: center;
    }

    .btn { border-radius: 8px; }
    .modal-content { border-radius: 12px; }
    .form-label { font-weight: 500; }

    .dataTables_filter input {
      border-radius: 6px;
      border: 1px solid #ccc;
      padding: 5px;
    }
  </style>
</head>

<body>
  <div class="main-card">
    <div class="card-header bg-transparent mb-3">
      <?php include '../blade/namaProgram.php'; ?>
    </div>

    <?php include '../blade/nav.php'; ?>

    <div class="card-body">
      <h3>Data Sub-Kriteria</h3>

      <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
          + Tambah Sub-Kriteria
        </button>
      </div>

      <div class="table-responsive">
        <table id="tableSubKriteria" class="table table-striped table-hover align-middle text-center">
          <thead class="table-info">
            <tr>
              <th>No</th>
              <th>Kriteria</th>
              <th>Kode Sub-Kriteria</th>
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
                <td><?= $row['kriteria_nama']; ?></td>
                <td><?= $row['subkriteria_kode']; ?></td>
                <td><?= $row['subkriteria_keterangan']; ?></td>
                <td><?= $row['subkriteria_bobot']; ?></td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['subkriteria_id'] ?>">Edit</button>
                  <a href="subkriteriaDelete.php?id=<?= $row['subkriteria_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Tambah -->
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
                    <?= $kr['kriteria_kode'] . ' - ' . $kr['kriteria_nama'] . ' (' . $kr['kriteria_kategori'] . ')'; ?>
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

  <!-- Modal Edit -->
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
                      <?= $kr['kriteria_kode'] . ' - ' . $kr['kriteria_nama'] . ' (' . $kr['kriteria_kategori'] . ')'; ?>
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

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tableSubKriteria').DataTable({
        language: {
          lengthMenu: "Tampilkan _MENU_ data per halaman",
          zeroRecords: "Data tidak ditemukan",
          info: "Menampilkan _PAGE_ dari _PAGES_ halaman",
          infoEmpty: "Tidak ada data tersedia",
          infoFiltered: "(difilter dari total _MAX_ data)",
          search: "Cari:",
          paginate: {
            first: "Awal",
            last: "Akhir",
            next: "›",
            previous: "‹"
          }
        },
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50]
      });
    });
  </script>
</body>
</html>
