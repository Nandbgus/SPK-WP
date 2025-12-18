<?php
include '../tools/connection.php';
include '../blade/header.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Kriteria (Drag & Drop)</title>

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
      background: rgba(255, 255, 255, 0.9);
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

    /* Tabel Styling */
    .table {
      color: #333 !important;
      border-color: #dee2e6;
      vertical-align: middle;
    }

    .table thead {
      background-color: #0d6efd;
      color: #fff;
      border: none;
    }

    /* Styling Baris saat di-drag */
    .draggable-row {
      cursor: move;
      /* Ubah kursor jadi ikon geser */
      transition: background 0.2s;
    }

    .draggable-row:hover {
      background-color: #e9ecef !important;
      /* Highlight saat hover */
    }

    /* Agar baris tidak mengecil saat ditarik */
    .ui-sortable-helper {
      display: table;
      background: #fff;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-content {
      color: #333;
      border: none;
      border-radius: 15px;
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

          <h3 class="text-center mb-4">Data Kriteria (Prioritas ROC)</h3>

          <div class="alert alert-info shadow-sm d-flex align-items-center gap-3 mb-4" role="alert">
            <i class="bi bi-arrows-move fs-3"></i>
            <div>
              <strong>Fitur Drag & Drop Aktif!</strong>
              <br>Klik dan tahan pada baris tabel, lalu geser ke atas/bawah untuk mengubah urutan prioritas. Ranking akan otomatis diperbarui.
            </div>
          </div>

          <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary shadow-sm fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAdd">
              <i class="bi bi-plus-lg"></i> Tambah Kriteria
            </button>
          </div>

          <div class="table-responsive">
            <table id="tableKriteria" class="table table-striped table-hover align-middle text-center shadow-sm rounded overflow-hidden">
              <thead class="bg-primary text-white">
                <tr>
                  <th><i class="bi bi-grip-vertical"></i></th>
                  <th>Rank</th>
                  <th>Kode</th>
                  <th>Nama Kriteria</th>
                  <th>Kategori</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="sortableKriteria">
                <?php
                // Urutkan berdasarkan bobot (ranking)
                $data = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_bobot ASC");
                $rank = 1;
                while ($kriteria = $data->fetch_assoc()) { ?>
                  <tr class="draggable-row" data-id="<?= $kriteria['kriteria_id'] ?>">
                    <td class="text-muted"><i class="bi bi-grip-horizontal fs-4"></i></td>

                    <td class="fw-bold text-primary rank-number">
                      <span class="badge bg-warning text-dark rounded-circle fs-6" style="width: 30px; height: 30px; line-height: 20px;"><?= $rank++ ?></span>
                    </td>

                    <td><span class="badge bg-light text-dark border"><?= $kriteria['kriteria_kode'] ?></span></td>
                    <td class="text-start ps-4 fw-bold"><?= $kriteria['kriteria_nama'] ?></td>
                    <td>
                      <?php if ($kriteria['kriteria_kategori'] == 'benefit'): ?>
                        <span class="badge bg-success bg-opacity-75 rounded-pill">Benefit</span>
                      <?php else: ?>
                        <span class="badge bg-danger bg-opacity-75 rounded-pill">Cost</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <button class="btn btn-warning btn-sm text-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $kriteria['kriteria_id'] ?>"><i class="bi bi-pencil-square"></i></button>
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
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Kriteria Baru</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="kriteriaAdd.php">
            <?php
            $data = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_id DESC LIMIT 1");
            $kode = 'C01';
            if (mysqli_num_rows($data) > 0) {
              $row = $data->fetch_assoc();
              $lastCode = intval(substr($row['kriteria_kode'], 1));
              $next = $lastCode + 1;
              $kode = ($next < 10) ? 'C0' . $next : 'C' . $next;
            }
            // Auto Ranking Terakhir
            $qRank = $conn->query("SELECT COUNT(*) as total FROM ta_kriteria");
            $dRank = $qRank->fetch_assoc();
            $nextRank = $dRank['total'] + 1;
            ?>
            <div class="mb-3">
              <label class="form-label">Kode Kriteria</label>
              <input type="text" class="form-control bg-light fw-bold" name="kriKode" value="<?= $kode ?>" readonly>
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
            <input type="hidden" name="kriBobot" value="<?= $nextRank ?>">

            <div class="text-end">
              <button type="submit" class="btn btn-primary" name="save">Simpan Data</button>
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
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Kriteria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="kriteriaEdit.php">
              <input type="hidden" name="kriId" value="<?= $kriteria['kriteria_id'] ?>">
              <input type="hidden" name="kriBobot" value="<?= $kriteria['kriteria_bobot'] ?>">

              <div class="mb-3">
                <label class="form-label">Kode</label>
                <input type="text" class="form-control bg-light" name="kriKode" value="<?= $kriteria['kriteria_kode'] ?>" readonly>
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
              <div class="text-end">
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
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      // 1. Inisialisasi DataTable (Matikan sorting bawaan agar tidak bentrok)
      var table = $('#tableKriteria').DataTable({
        "paging": false, // Matikan paging agar semua data tampil dan bisa di-sort
        "ordering": false, // Matikan sorting DataTable
        "info": false,
        "searching": false, // Opsional: Matikan search jika data sedikit
        language: {
          zeroRecords: "Data tidak ditemukan"
        }
      });

      // 2. Inisialisasi Sortable (Drag & Drop)
      $("#sortableKriteria").sortable({
        placeholder: "ui-state-highlight", // Class placeholder saat digeser
        cursor: "move",
        axis: "y", // Hanya geser vertikal
        update: function(event, ui) {
          // A. Ambil urutan ID baru
          var order = [];
          $('#sortableKriteria tr').each(function() {
            order.push($(this).data('id'));
          });

          // B. Update Angka Ranking di Tampilan (Visual Saja)
          $('#sortableKriteria tr').each(function(index) {
            $(this).find('.rank-number span').text(index + 1);
          });

          // C. Kirim urutan baru ke Database via AJAX
          $.ajax({
            url: "updatePrioritas.php",
            method: "POST",
            data: {
              urutan: order
            },
            success: function(response) {
              // console.log("Prioritas Berhasil Diupdate");
            },
            error: function() {
              alert("Gagal mengupdate prioritas.");
            }
          });
        }
      });
    });
  </script>
</body>

</html>