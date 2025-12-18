<?php
include '../tools/connection.php';

if (isset($_POST['save'])) {
    $altKode = $_POST['altKode'];
    $nilaiData = $_POST['nilai']; // Array Nilai

    if (empty($altKode)) {
        echo "<script>alert('Pilih alternatif terlebih dahulu!'); window.location.href='faktorView.php';</script>";
        exit;
    }

    // Loop untuk menyimpan setiap nilai kriteria
    foreach ($nilaiData as $kriKode => $nilai) {

        // Hapus dulu data lama jika ada (untuk menghindari duplikat/double entry)
        $conn->query("DELETE FROM tb_faktor WHERE alternatif_kode='$altKode' AND kriteria_kode='$kriKode'");

        // Simpan data baru
        $stmt = $conn->prepare("INSERT INTO tb_faktor (alternatif_kode, kriteria_kode, nilai_faktor) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $altKode, $kriKode, $nilai);
        $stmt->execute();
    }

    echo "<script>alert('Data berhasil disimpan!'); window.location.href='faktorView.php';</script>";
} else {
    header("Location: faktorView.php");
}
