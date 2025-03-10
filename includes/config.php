<?php
// config.php: file konfigurasi database dan settingan lainnya
$host = "localhost";
$user = "root";
$password = "";
$database = "perpustakaan";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
