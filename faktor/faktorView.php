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
      padding: 40px 0;
    }

    /* CARD SAMA DENGAN HALAMAN LAIN */
    .main-card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(12px);
      border-radius: 16px;
      width: 90%;
      max-width: 1100px;  /* ukuran sama dengan halaman lain */
      margin: auto;
      padding: 25px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    h3 {
      font-weight: 600;
      color: #fff;
      text-align: center;
    }

    .table thead {
      background-color: #0dcaf0;
      color: #fff;
    }

    /* Table Responsive */
    .table-responsive {
      overflow-x: auto;
      width: 100%;
    }

    table.dataTable th,
    table.dataTable td {
      white-space: nowrap;
      padding: 10px 16px !important;
    }
  </style>
</head>

<body>

<div class="main-card">

    <!-- JUDUL SISTEM -->
    <?php include '../blade/namaProgram.php'; ?>

    <!-- NAVIGATION -->
    <?php include '../blade/nav.php'; ?>

    <h3 class="mt-3 mb-4">Data Nilai Faktor</h3>

    <!-- TOMBOL TAMBAH -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
          + Tambah Nilai Faktor
        </button>
    </div>

    <!-- TABEL -->
    <div class="table-responsive">
        <table id="tableFaktor" class="table table-striped table-hover text-center">
            <thead class="table-info">
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
                    echo "<td>{$a['alternatif_nama']}</td>";

                    foreach ($kriteriaList as $kodeKri) {
                        $nilai = $conn->query("
                            SELECT nilai_faktor FROM tb_faktor 
                            WHERE alternatif_kode='{$a['alternatif_kode']}' 
                            AND kriteria_kode='$kodeKri'
                        ")->fetch_assoc();

                        $val = $nilai['nilai_faktor'] ?? "-";
                        echo "<td>$val</td>";
                    }

                    echo "
                    <td>
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#modalEdit{$a['alternatif_kode']}'>Edit</button>
                        <a href='faktorDelete.php?id={$a['alternatif_kode']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus data ini?')\">Hapus</a>
                    </td>";

                    echo "</tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL-MODAL (ADD + EDIT) TETAP SAMA, TIDAK PERLU DIUBAH ) -->

<?php include '../blade/footer.php'; ?>

<!-- SCRIPT JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tableFaktor').DataTable({
        scrollX: false,  // tidak lebay melebar
        autoWidth: true,
        language: {
          lengthMenu: "Tampilkan _MENU_ data",
          search: "Cari:",
          paginate: { next: "›", previous: "‹" }
        }
    });
});
</script>

</body>
</html>
