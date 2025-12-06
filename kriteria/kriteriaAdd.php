<?php
include '../tools/connection.php';

if (isset($_POST['save'])) {

    $kriKode = $_POST['kriKode'];
    $kriNama = $_POST['kriNama'];
    $kriKategori = $_POST['kriKategori'];
    $kriBobotInput = floatval($_POST['kriBobot']); // bisa input 0–100

    // NORMALISASI OTOMATIS
    // Jika user memasukkan bobot lebih dari 1 (contoh 40 atau 25)
    // maka dianggap persen dan diubah jadi desimal.
    if ($kriBobotInput > 1) {
        $kriBobot = $kriBobotInput / 100;
    } else {
        $kriBobot = $kriBobotInput;
    }

    if ($kriKategori == 'benefit' || $kriKategori == 'cost') {

        $query = $conn->query(
            "INSERT INTO ta_kriteria(kriteria_kode, kriteria_nama, kriteria_kategori, kriteria_bobot)
            VALUES('$kriKode', '$kriNama', '$kriKategori', '$kriBobot')"
        );

        if ($query) {
            echo "<script>
                    alert('Data Berhasil Disimpan');
                    window.location='kriteriaView.php'
                  </script>";
        } else {
            die('MySQL error : ' . mysqli_error($conn));
        }
    } else {
        echo "<script>
                alert('Kategori Belum Dipilih');
                window.location='kriteriaView.php'
             </script>";
    }
}
