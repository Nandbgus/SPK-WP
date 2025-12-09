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
      max-width: 1200px;
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
      <h3>Data Nilai Faktor</h3>

      <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
          + Tambah Nilai Faktor
        </button>
      </div>

      <div class="table-responsive">
        <table id="tableFaktor" class="table table-striped table-hover align-middle text-center">
          <thead class="table-info">
            <tr>
              <th>No</th>
              <th>Alternatif</th>
              <?php
              $kriteria = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
              while ($kr = $kriteria->fetch_assoc()) {
                echo "<th>{$kr['kriteria_nama']}</th>";
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
              echo "<tr><td>{$no}</td><td>{$a['alternatif_nama']}</td>";

              $altKode = $a['alternatif_kode'];
              $nilai = $conn->query("SELECT * FROM tb_faktor WHERE alternatif_kode='$altKode' ORDER BY kriteria_kode");
              while ($n = $nilai->fetch_assoc()) {
                echo "<td>{$n['nilai_faktor']}</td>";
              }

              echo "
                <td>
                  <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#modalEdit{$a['alternatif_kode']}'>Edit</button>
                  <a href='faktorDelete.php?id={$a['alternatif_kode']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                </td>
              </tr>";
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Tambah Nilai Faktor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="faktorAdd.php">
            <div class="mb-3">
              <label class="form-label">Alternatif</label>
              <select class="form-select" name="altKode" required>
                <option value="" disabled selected>Pilih Alternatif...</option>
                <?php
                $alt = $conn->query("SELECT * FROM ta_alternatif");
                while ($a = $alt->fetch_assoc()) {
                  echo "<option value='{$a['alternatif_kode']}'>{$a['alternatif_nama']} ({$a['alternatif_kode']})</option>";
                }
                ?>
              </select>
            </div>

            <p class="text-center fw-semibold text-dark">Isi Nilai Faktor Berdasarkan Sub-Kriteria</p>

            <?php
            $kriteria = $conn->query("SELECT * FROM ta_kriteria ORDER BY kriteria_kode");
            while ($kr = $kriteria->fetch_assoc()) {
              echo "
              <div class='mb-3'>
                <label class='form-label'>{$kr['kriteria_kode']} - {$kr['kriteria_nama']}</label>
                <input type='hidden' name='kriKode[]' value='{$kr['kriteria_kode']}'>
                <select class='form-select' name='nilaiFaktor[]' required>
                  <option value='' disabled selected>Pilih Nilai...</option>";
              $sub = $conn->query("SELECT * FROM ta_subkriteria WHERE kriteria_kode='{$kr['kriteria_kode']}'");
              while ($s = $sub->fetch_assoc()) {
                echo "<option value='{$s['subkriteria_bobot']}'>{$s['subkriteria_keterangan']} (Bobot: {$s['subkriteria_bobot']})</option>";
              }
              echo "</select></div>";
            }
            ?>

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
  $alt = $conn->query("SELECT * FROM ta_alternatif ORDER BY alternatif_kode");
  while ($a = $alt->fetch_assoc()) { ?>
    <div class="modal fade" id="modalEdit<?= $a['alternatif_kode'] ?>" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Edit Nilai Faktor - <?= $a['alternatif_nama'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="faktorEdit.php">
              <?php
              $altKode = $a['alternatif_kode'];
              $nilai = $conn->query("SELECT * FROM tb_faktor WHERE alternatif_kode='$altKode'");
              while ($n = $nilai->fetch_assoc()) {
                $kri = $n['kriteria_kode'];
                $k = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kri'")->fetch_assoc();
                echo "
                <div class='mb-3'>
                  <label class='form-label'>{$k['kriteria_kode']} - {$k['kriteria_nama']}</label>
                  <input type='hidden' name='nilaiId[]' value='{$n['nilai_id']}'>
                  <input type='hidden' name='altKode[]' value='{$n['alternatif_kode']}'>
                  <input type='hidden' name='kriKode[]' value='{$n['kriteria_kode']}'>
                  <select class='form-select' name='nilaiFaktor[]'>";
                $sub = $conn->query("SELECT * FROM ta_subkriteria WHERE kriteria_kode='$kri'");
                while ($s = $sub->fetch_assoc()) {
                  $selected = ($s['subkriteria_bobot'] == $n['nilai_faktor']) ? 'selected' : '';
                  echo "<option value='{$s['subkriteria_bobot']}' $selected>{$s['subkriteria_keterangan']} (Bobot: {$s['subkriteria_bobot']})</option>";
                }
                echo "</select></div>";
              }
              ?>
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
      $('#tableFaktor').DataTable({
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
        lengthMenu: [5, 10, 20, 50],
        scrollX: true
      });
    });
  </script>
</body>
</html>
