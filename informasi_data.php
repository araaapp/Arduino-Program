<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "connect_to_android_studio");

if ($conn->connect_error) {
    die(json_encode(array("error" => "Koneksi gagal: " . $conn->connect_error)));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = isset($_POST['temperature']) ? $_POST['temperature'] : null;
    $humidity = isset($_POST['humidity']) ? $_POST['humidity'] : null;
    $ppm = isset($_POST['ppm']) ? $_POST['ppm'] : null;

    if ($temperature !== null && $humidity !== null && $ppm !== null) {
        $query = "UPDATE informasi_data SET temperature=?, humidity=?, dht11_updated=NOW(), ppm=?, tds_updated=NOW() WHERE id=1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ddi", $temperature, $humidity, $ppm);

        if ($stmt->execute()) {
            echo json_encode(array("success" => "Data Suhu berhasil disimpan."));
        } else {
            echo json_encode(array("error" => "Gagal menyimpan data: " . $stmt->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Data tidak lengkap"));
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['status'])) {
       
        $status = $_GET['status'];

        $stmt = $conn->prepare("UPDATE informasi_data SET status=?, updated_at=NOW() WHERE id=1");
        $stmt->bind_param("s", $status);

        if ($stmt->execute()) {
            echo json_encode(array("success" => "Status LED berhasil diperbarui"));
        } else {
            echo json_encode(array("error" => "Terjadi kesalahan: " . $stmt->error));
        }

        $stmt->close();
    } else {
        
        $query = "SELECT status, temperature, humidity, ppm, tds_updated FROM informasi_data WHERE id=1";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(array(
                "status" => $row['status'],
                "temperature" => $row['temperature'],
                "humidity" => $row['humidity'],
                "ppm" => $row['ppm'],
                "tds_updated" => $row['tds_updated']
            ));
        } else {
            echo json_encode(array("error" => "Data tidak ditemukan"));
        }
    }
}

$conn->close();
?>
