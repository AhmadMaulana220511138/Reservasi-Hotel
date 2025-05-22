<?php
session_start();
include '../koneksi/koneksi.php';

// Ambil data dari form
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Query untuk mencari pengguna berdasarkan username
$query = "SELECT * FROM tbl_tamu WHERE nama = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah username dan password cocok
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Set sesi pengguna
    $_SESSION['user_id'] = $user['id_tamu'];
    $_SESSION['user_username'] = $user['nama'];

    // SweetAlert2 untuk login berhasil
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Login Berhasil</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang, {$user['nama']}!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '../index.php';
                }
            });
        </script>
    </body>
    </html>";
} else {
    // Login gagal
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Login</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: 'Username atau password salah.',
                showConfirmButton: true,
                confirmButtonText: 'Coba Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '../login.php';
                }
            });
        </script>
    </body>
    </html>";
}

$stmt->close();
$conn->close();
?>
