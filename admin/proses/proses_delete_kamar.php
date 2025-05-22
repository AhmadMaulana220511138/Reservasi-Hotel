<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Sesuaikan dengan path koneksi database

// Validasi jika ada data POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nomor_kamar yang akan dihapus
    $nomor_kamar = $_POST['nomor_kamar'];

    // Query untuk menghapus data kamar berdasarkan nomor_kamar
    $query = "DELETE FROM kamar WHERE nomor_kamar = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nomor_kamar); // Menggunakan 's' untuk string (nomor_kamar)
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Menampilkan notifikasi sukses dengan SweetAlert dan redirect ke halaman daftar kamar
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Hapus Kamar</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Kamar berhasil dihapus!',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../show_kamar.php';
                    }
                });
            </script>
        </body>
        </html>";
        exit();
    } else {
        // Menampilkan notifikasi gagal dengan SweetAlert dan redirect ke halaman daftar kamar
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Hapus Kamar</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menghapus kamar!',
                    text: 'Terjadi kesalahan saat menghapus data kamar.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../show_kamar.php';
                    }
                });
            </script>
        </body>
        </html>";
        exit();
    }
}
?>
