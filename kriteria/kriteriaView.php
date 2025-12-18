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

    .table-striped>tbody>tr:nth-of-type(odd)>* {
      background-color: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    .modal-content {
      color: #333;
    }

    /* Accordion Styling for Glass */
    .accordion-item {
      background-color: rgba(255, 255, 255, 0.8);
      border: none;
    }

    .accordion-button {
      background-color: rgba(255, 255, 255, 0.9);
      color: #333;
      font-weight: 600;
    }

    .accordion-button:not(.collapsed) {
      background-color: #e7f1ff;
      color: #0c63e4;
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

          <h3 class="text-center mb-4 font-weight-bold">Data Kriteria</h3>

          <div class="mb-4">
            <div class="alert alert-light bg-opacity-75 shadow-sm text-dark" role="alert">
              <div class="d-flex align-items-start gap-2">
                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                <div>
                  <strong>Panduan Metode Weighted Product (WP):</strong><br>
                  Halaman ini untuk mengelola <em>kriteria</em> penilaian. Pastikan total bobot bernilai 1.0 (jika dilakukan normalisasi manual) atau sistem akan menormalisasinya.
                </div>
              </div>
            </div>
            <div class="accordion shadow-sm rounded" id="panduanKriteria">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFungsi">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFungsi">
                    Fungsi & Komponen Data Kriteria
                  </button>
                </h2>
                <div id="collapseFungsi" class="accordion-collapse collapse" data-bs-parent="#panduanKriteria">
                  <div class="accordion-body text-dark">
                    <ul>
                      <li><strong>Kode</strong> → Identitas unik (C01, C02, dst).</li>
                      <li><strong>Nama</strong> → Aspek penilaian (Harga, Kualitas).</li>
                      <li><strong>Kategori</strong>:
                        <ul>
                          <li><em>Benefit</em> (Nilai besar lebih baik)</li>
                          <li><em>Cost</em> (Nilai kecil lebih baik)</li>
                        </ul>
                      </li>
                      <li><strong>Bobot</strong> → Tingkat kepentingan.</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-light text-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Tambah Kriteria
            </button>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
              <thead>
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
                    <td><span class="badge bg-info text-dark"><?= $kriteria['kriteria_kode'] ?></span></td>
                    <td class="text-start ps-4 fw-bold"><?= $kriteria['kriteria_nama'] ?></td>
                    <td>
                      <?php if ($kriteria['kriteria_kategori'] == 'benefit'): ?>
                        <span class="badge bg-success">Benefit</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Cost</span>
                      <?php endif; ?>
                    </td>
                    <td><?= $kriteria['kriteria_bobot'] ?></td>
                    <td>
                      <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $kriteria['kriteria_id'] ?>"><i class="bi bi-pencil-square"></i></button>
                      <a href="kriteriaDelete.php?id=<?= $kriteria['kriteria_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash"></i></a>
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
              <label class="form-label">Kode</label>
              <input type="text" class="form-control" name="kriKode" value="<?= $kode ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Kriteria</label>
              <input type="text" class="form-control" name="kriNama" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" name="kriKategori" required>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Bobot</label>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>