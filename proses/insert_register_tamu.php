<?php
// Menyertakan file koneksi
include '../koneksi/koneksi.php';

// Mendapatkan data dari form pendaftaran
$nama = trim($_POST['nama']);
$jk = $_POST['jk'];
$no_hp = trim($_POST['no_hp']);
$alamat = trim($_POST['alamat']);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];  // Password langsung diambil dari form

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Email tidak valid!');window.location='../register.php';</script>";
    exit;
}

// Melakukan validasi email (pastikan tidak ada email yang sama)
$query_check_email = "SELECT * FROM tbl_tamu WHERE email = ?";
$stmt = $conn->prepare($query_check_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jika email sudah terdaftar
    echo "<script>alert('Email sudah terdaftar!');window.location='../register.php';</script>";
    exit;
}

// Query untuk menyimpan data tamu ke tabel
$query = "INSERT INTO tbl_tamu (nama, jk, no_hp, alamat, email, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssss", $nama, $jk, $no_hp, $alamat, $email, $password);  // Menggunakan $password langsung

// Eksekusi query
if ($stmt->execute()) {
    // Jika berhasil, tampilkan notifikasi dan redirect ke halaman login
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Registrasi Berhasil</title>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Registrasi Berhasil!',
                      text: 'Silakan login untuk melanjutkan.',
                      showConfirmButton: true,
                      confirmButtonText: 'Ke Halaman Login'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location = '../login.php';
                      }
                  });
              </script>
          </body>
          </html>";
} else {
    // Jika gagal, tampilkan notifikasi error
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Registrasi Gagal</title>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Terjadi Kesalahan!',
                      text: 'Pendaftaran gagal, silakan coba lagi.',
                      showConfirmButton: true,
                      confirmButtonText: 'Coba Lagi'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location = '../register.php';
                      }
                  });
              </script>
          </body>
          </html>";
}

// Menutup koneksi
$stmt->close();
$conn->close();
