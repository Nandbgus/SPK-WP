<?php
include '../tools/connection.php';

if (isset($_POST['update'])) {
    $altKode = $_POST['altKode'];   // Kode Alternatif (misal: A01)
    $nilaiData = $_POST['nilai'];   // Array Nilai (misal: [C01=>4, C02=>2])

    // Validasi sederhana
    if (empty($altKode) || empty($nilaiData)) {
        echo "<script>alert('Data tidak lengkap!'); window.location.href='faktorView.php';</script>";
        exit;
    }

    // Loop untuk update setiap kriteria
    foreach ($nilaiData as $kriKode => $nilai) {

        // Cek dulu apakah data nilai untuk kriteria ini sudah ada di database?
        $cek = $conn->query("SELECT * FROM tb_faktor WHERE alternatif_kode='$altKode' AND kriteria_kode='$kriKode'");

        if ($cek->num_rows > 0) {
            // JIKA ADA: Lakukan Update
            $stmt = $conn->prepare("UPDATE tb_faktor SET nilai_faktor=? WHERE alternatif_kode=? AND kriteria_kode=?");
            $stmt->bind_param("dss", $nilai, $altKode, $kriKode);
            $stmt->execute();
        } else {
            // JIKA BELUM ADA (Misal kriteria baru ditambah): Lakukan Insert
            $stmt = $conn->prepare("INSERT INTO tb_faktor (alternatif_kode, kriteria_kode, nilai_faktor) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $altKode, $kriKode, $nilai);
            $stmt->execute();
        }
    }

    echo "<script>alert('Data berhasil diperbarui!'); window.location.href='faktorView.php';</script>";
} else {
    // Jika file diakses langsung tanpa submit form
    header("Location: faktorView.php");
}
