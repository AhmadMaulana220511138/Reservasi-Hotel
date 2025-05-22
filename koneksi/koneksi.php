<?php
$host = "localhost";  // Nama host, biasanya 'localhost'
$username = "root";   // Nama pengguna database
$password = "";       // Password database
$dbname = "my_hotel";  // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil!";

?>
