<?php
include '../tools/connection.php';

if (isset($_POST['update'])) {

    $kriId = $_POST['kriId'];
    $kriKode = $_POST['kriKode'];
    $kriNama = $_POST['kriNama'];
    $kriKategori = $_POST['kriKategori'];
    $kriBobotInput = floatval($_POST['kriBobot']);

    // NORMALISASI
    if ($kriBobotInput > 1) {
        $kriBobot = $kriBobotInput / 100;
    } else {
        $kriBobot = $kriBobotInput;
    }

    $query = $conn->query(
        "UPDATE ta_kriteria SET
            kriteria_kode='$kriKode',
            kriteria_nama='$kriNama',
            kriteria_kategori='$kriKategori',
            kriteria_bobot='$kriBobot'
         WHERE kriteria_id='$kriId'"
    );

    if ($query) {
        echo "<script>
                alert('Data Berhasil Diupdate');
                window.location='kriteriaView.php'
              </script>";
    } else {
        die('MySQL error : ' . mysqli_error($conn));
    }
}
