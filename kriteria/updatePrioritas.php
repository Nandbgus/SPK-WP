<?php
include '../tools/connection.php';

if (isset($_POST['urutan'])) {

    // =====================================
    // 1. UPDATE RANKING DARI DRAG & DROP
    // =====================================
    foreach ($_POST['urutan'] as $index => $id) {
        $rank = $index + 1;
        $stmt = $conn->prepare(
            "UPDATE ta_kriteria 
             SET kriteria_bobot = ? 
             WHERE kriteria_id = ?"
        );
        $stmt->bind_param("ii", $rank, $id);
        $stmt->execute();
    }

    // =====================================
    // 2. AMBIL ULANG KRITERIA DARI DATABASE
    //    (INI KUNCI UTAMA)
    // =====================================
    $q = $conn->query(
        "SELECT kriteria_id 
         FROM ta_kriteria 
         ORDER BY kriteria_bobot ASC"
    );

    $kriteria = [];
    while ($row = $q->fetch_assoc()) {
        $kriteria[] = $row['kriteria_id'];
    }

    $n = count($kriteria);

    // =====================================
    // 3. HITUNG ROC BERDASARKAN URUTAN DB
    // =====================================
    foreach ($kriteria as $index => $id) {
        $rank = $index + 1;
        $sum = 0;

        for ($i = $rank; $i <= $n; $i++) {
            $sum += (1 / $i);
        }

        $bobotROC = $sum / $n;

        $stmt = $conn->prepare(
            "UPDATE ta_kriteria 
             SET kriteria_bobot_ROC = ? 
             WHERE kriteria_id = ?"
        );
        $stmt->bind_param("di", $bobotROC, $id);
        $stmt->execute();
    }

    echo "success";
}
