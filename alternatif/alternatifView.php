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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    body {
      /* Background Gradient Biru Segar */
      background: linear-gradient(135deg, #89f7fe, #66a6ff);
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      padding-bottom: 50px;
      color: #333;
      /* Default teks gelap */
    }

    /* Navbar Putih Solid agar bersih */
    nav.navbar {
      background: #ffffff !important;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 40px;
    }

    /* Main Card: Putih Susu (High Opacity) */
    .main-card {
      background: rgba(255, 255, 255, 0.85);
      /* Lebih solid/putih */
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 30px;
      color: #333;
      /* Teks Hitam Pasti */
    }

    h3 {
      font-weight: 700;
      color: #0d6efd;
      /* Biru Bootstrap */
      text-shadow: none;
    }

    /* Tabel Styling: Teks Gelap */
    .table {
      color: #333 !important;
      /* Paksa teks tabel hitam */
      vertical-align: middle;
      border-color: #dee2e6;
    }

    .table thead {
      background-color: #0dcaf0;
      /* Cyan Header */
      color: #fff;
      /* Teks Header Putih */
      border-bottom: none;
    }

    /* Zebra Striping Gelap Halus */
    .table-striped>tbody>tr:nth-of-type(odd)>* {
      background-color: rgba(0, 0, 0, 0.02) !important;
      /* Abu sangat muda */
      color: #333 !important;
    }

    /* Hover Effect */
    .table-hover tbody tr:hover>* {
      background-color: rgba(13, 110, 253, 0.05) !important;
      /* Biru muda saat hover */
      color: #333 !important;
    }

    .btn {
      border-radius: 50px;
    }

    .modal-content {
      color: #333;
      border-radius: 15px;
      border: none;
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

          <h3 class="text-center mb-4">Data Alternatif</h3>

          <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary shadow-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Tambah Alternatif
            </button>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center shadow-sm rounded overflow-hidden">
              <thead class="bg-primary text-white">
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama Alternatif</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_id ASC");
                $no = 1;
                while ($alternatif = $data->fetch_assoc()) { ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $alternatif['alternatif_kode'] ?></span></td>
                    <td class="text-start ps-5 fw-bold"><?= $alternatif['alternatif_nama'] ?></td>
                    <td>
                      <button class="btn btn-warning btn-sm text-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $alternatif['alternatif_id'] ?>"><i class="bi bi-pencil-square"></i> Edit</button>
                      <a href="alternatifDelete.php?id=<?= $alternatif['alternatif_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash"></i> Hapus</a>
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
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Data Alternatif</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
              <label class="form-label">Kode</label>
              <input type="text" class="form-control bg-light fw-bold" name="altKode" value="<?= $kode ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Alternatif</label>
              <input type="text" class="form-control" name="altNama" placeholder="Nama Pegawai..." required>
            </div>
            <div class="text-end">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Data Alternatif</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="alternatifEdit.php">
              <input type="hidden" name="altId" value="<?= $alternatif['alternatif_id'] ?>">
              <div class="mb-3">
                <label class="form-label">Kode</label>
                <input type="text" class="form-control bg-light" name="altKode" value="<?= $alternatif['alternatif_kode'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Alternatif</label>
                <input type="text" class="form-control" name="altNama" value="<?= $alternatif['alternatif_nama'] ?>" required>
              </div>
              <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning" name="update">Update</button>
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