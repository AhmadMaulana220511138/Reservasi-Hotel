<?php
session_start();
include '../../koneksi/koneksi.php'; // Pastikan path ini benar

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk mencari admin berdasarkan username dan password
$query = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Mengecek apakah username dan password cocok
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    // Simpan data login ke session
    $_SESSION['admin_id'] = $admin['id_admin']; // Simpan ID admin
    $_SESSION['admin_username'] = $admin['username']; // Simpan username admin

    // Tampilkan SweetAlert untuk login berhasil dengan tombol OK
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
                title: 'Login Berhasil',
                text: 'Selamat datang, " . $admin['username'] . "!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../dashboard.php'; // Arahkan ke halaman dashboard admin
            });
        </script>
    </body>
    </html>";
    exit();
} else {
    // Username atau password salah
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Login Gagal</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Username atau Password Salah',
                text: 'Periksa kembali username dan password Anda.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../index.php'; // Arahkan ke halaman login
                }
            });
        </script>
    </body>
    </html>";
}
$stmt->close();
$conn->close();
?>
