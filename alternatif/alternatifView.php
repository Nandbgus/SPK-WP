<?php
include '../tools/connection.php';
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Alternatif</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    @media (max-width: 768px) {
      .main-card {
        width: 100%;
        margin: 15px;
      }
      .table {
        font-size: 0.9rem;
      }
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
      <h3>Data Alternatif</h3>

      <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
          + Tambah Alternatif
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode Alternatif</th>
              <th>Nama Alternatif</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $data = $conn->query("SELECT * FROM ta_alternatif");
            $no = 1;
            while ($alternatif = $data->fetch_assoc()) { ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= $alternatif['alternatif_kode'] ?></td>
                <td><?= $alternatif['alternatif_nama'] ?></td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $alternatif['alternatif_id'] ?>">Edit</button>
                  <a href="alternatifDelete.php?id=<?= $alternatif['alternatif_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  
  <div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Tambah Data Alternatif</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="alternatifAdd.php">
            <?php
            $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_id DESC LIMIT 1");
            $kode = 'A01';
            if (mysqli_num_rows($data) > 0) {
              $row = $data->fetch_assoc();
              $next = (int)$row['alternatif_id'] + 1;
              $kode = ($next < 10) ? 'A0' . $next : 'A' . $next;
            }
            ?>
            <div class="mb-3">
              <label for="altKode" class="form-label">Kode</label>
              <input type="text" class="form-control" id="altKode" name="altKode" value="<?= $kode ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="altNama" class="form-label">Nama Alternatif</label>
              <input type="text" class="form-control" id="altNama" name="altNama" required>
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
  $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_id");
  while ($alternatif = $data->fetch_assoc()) { ?>
    <div class="modal fade" id="modalEdit<?= $alternatif['alternatif_id'] ?>" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Data Alternatif</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="alternatifEdit.php">
              <input type="hidden" name="altId" value="<?= $alternatif['alternatif_id'] ?>">
              <div class="mb-3">
                <label class="form-label">Kode</label>
                <input type="text" class="form-control" name="altKode" value="<?= $alternatif['alternatif_kode'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Alternatif</label>
                <input type="text" class="form-control" name="altNama" value="<?= $alternatif['alternatif_nama'] ?>" required>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-warning text-white">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php include '../blade/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
