<?php
include '../tools/connection.php';
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Kriteria</title>

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
      max-width: 1100px;
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

    .btn {
      border-radius: 8px;
    }

    .modal-content {
      border-radius: 12px;
    }

    .form-label {
      font-weight: 500;
    }

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
      <h3>Data Kriteria</h3>

      <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
          + Tambah Kriteria
        </button>
      </div>

      <div class="table-responsive">
        <table id="tableKriteria" class="table table-striped table-hover align-middle text-center">
          <thead class="table-info">
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Bobot</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $data = $conn->query("SELECT * FROM ta_kriteria");
            $no = 1;
            while ($kriteria = $data->fetch_assoc()) { ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= $kriteria['kriteria_kode'] ?></td>
                <td><?= $kriteria['kriteria_nama'] ?></td>
                <td><?= ucfirst($kriteria['kriteria_kategori']) ?></td>
                <td><?= $kriteria['kriteria_bobot'] ?></td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $kriteria['kriteria_id'] ?>">Edit</button>
                  <a href="kriteriaDelete.php?id=<?= $kriteria['kriteria_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
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
          <h5 class="modal-title">Tambah Data Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="kriteriaAdd.php">
            <?php
            $data = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_id DESC LIMIT 1");
            $kode = 'C01';
            if (mysqli_num_rows($data) > 0) {
              $row = $data->fetch_assoc();
              $next = (int)$row['kriteria_id'] + 1;
              $kode = ($next < 10) ? 'C0' . $next : 'C' . $next;
            }
            ?>
            <div class="mb-3">
              <label for="kriKode" class="form-label">Kode</label>
              <input type="text" class="form-control" name="kriKode" value="<?= $kode ?>" readonly>
            </div>

            <div class="mb-3">
              <label for="kriNama" class="form-label">Nama Kriteria</label>
              <input type="text" class="form-control" name="kriNama" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" name="kriKategori" required>
                <option value="" selected disabled>Pilih...</option>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="kriBobot" class="form-label">Bobot</label>
              <input type="number" step="0.01" class="form-control" name="kriBobot" required>
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
  $data = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_id");
  while ($kriteria = $data->fetch_assoc()) { ?>
    <div class="modal fade" id="modalEdit<?= $kriteria['kriteria_id'] ?>" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Data Kriteria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="kriteriaEdit.php">
              <input type="hidden" name="kriId" value="<?= $kriteria['kriteria_id'] ?>">
              <div class="mb-3">
                <label class="form-label">Kode</label>
                <input type="text" class="form-control" name="kriKode" value="<?= $kriteria['kriteria_kode'] ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="kriNama" value="<?= $kriteria['kriteria_nama'] ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select class="form-select" name="kriKategori">
                  <option value="benefit" <?= $kriteria['kriteria_kategori'] == 'benefit' ? 'selected' : ''; ?>>Benefit</option>
                  <option value="cost" <?= $kriteria['kriteria_kategori'] == 'cost' ? 'selected' : ''; ?>>Cost</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Bobot</label>
                <input type="number" step="0.01" class="form-control" name="kriBobot" value="<?= $kriteria['kriteria_bobot'] ?>" required>
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

  <!-- JS: Bootstrap + DataTables -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tableKriteria').DataTable({
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
