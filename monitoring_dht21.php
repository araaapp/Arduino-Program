<?php
$host = 'localhost';  
$user = 'root';       
$pass = '';           
$db = 'monitoring_dht21';  

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suhu = $_POST['suhu'];
    $kelembapan = $_POST['kelembapan'];
    
    $sql = "INSERT INTO data_suhu_kelembapan (suhu, kelembapan) VALUES ('$suhu', '$kelembapan')";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil disimpan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
