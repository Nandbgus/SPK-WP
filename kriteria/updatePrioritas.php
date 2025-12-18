<?php
include '../tools/connection.php';

// Cek apakah ada data yang dikirim via AJAX
if (isset($_POST['urutan'])) {
    $urutan = $_POST['urutan']; // Array ID Kriteria yang sudah urut

    // Loop array dan update prioritas (bobot) berdasarkan urutan index
    foreach ($urutan as $index => $id) {
        $rank = $index + 1; // Index array mulai dari 0, Ranking mulai dari 1

        // Update database
        $stmt = $conn->prepare("UPDATE ta_kriteria SET kriteria_bobot = ? WHERE kriteria_id = ?");
        $stmt->bind_param("ii", $rank, $id);
        $stmt->execute();
    }
    echo "Sukses";
}
