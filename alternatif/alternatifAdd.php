<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../tools/connection.php';

if (isset($_POST['save'])) {
    $altKode = trim($_POST['altKode']);
    $altNama = trim($_POST['altNama']);

    if ($altKode === "" || $altNama === "") {
        echo "<script>alert('Kode dan Nama tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }

    
    $stmt = $conn->prepare("INSERT INTO ta_alternatif (alternatif_kode, alternatif_nama) VALUES (?, ?)");
    $stmt->bind_param("ss", $altKode, $altNama);
    $result = $stmt->execute();

    if ($result) {
        echo "<script>
                alert('Data Berhasil Disimpan');
                window.location='alternatifView.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data!\\nError: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            alert('Akses tidak valid!');
            window.location='alternatifView.php';
          </script>";
}
?>
