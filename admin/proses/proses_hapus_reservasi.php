<?php
// Cek apakah session sudah dimulai
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Akses Ditolak</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: 'Silakan login terlebih dahulu.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location = 'index.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

include '../../koneksi/koneksi.php'; // Pastikan Anda sudah menghubungkan ke database

// Ambil id_reservasi dari URL
if (isset($_GET['id_reservasi'])) {
    $id_reservasi = $_GET['id_reservasi'];

    // Ambil data reservasi terlebih dahulu untuk memastikan data ada
    $query = "SELECT * FROM reservasi WHERE id_reservasi = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_reservasi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Jika data reservasi tidak ditemukan
    if (mysqli_num_rows($result) == 0) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Reservasi Tidak Ditemukan</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Reservasi Tidak Ditemukan',
                    text: 'Reservasi dengan ID tersebut tidak ditemukan.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = '../show_reservasi.php';
                });
            </script>
        </body>
        </html>";
        exit();
    }

    // Ambil status pembayaran
    $row = mysqli_fetch_assoc($result);
    $status_pembayaran = $row['status_pembayaran'];

    // Mulai transaksi untuk memastikan kedua penghapusan berjalan bersamaan
    mysqli_begin_transaction($conn);

    try {
        // Jika status pembayaran gagal, hanya bisa dihapus
        if ($status_pembayaran == 'Gagal') {
            // Hapus data dari tabel pembayaran yang berelasi dengan id_reservasi
            $query_pembayaran = "DELETE FROM pembayaran WHERE id_reservasi = ?";
            $stmt_pembayaran = mysqli_prepare($conn, $query_pembayaran);
            mysqli_stmt_bind_param($stmt_pembayaran, "i", $id_reservasi);
            mysqli_stmt_execute($stmt_pembayaran);

            // Hapus data dari tabel reservasi
            $query_reservasi = "DELETE FROM reservasi WHERE id_reservasi = ?";
            $stmt_reservasi = mysqli_prepare($conn, $query_reservasi);
            mysqli_stmt_bind_param($stmt_reservasi, "i", $id_reservasi);
            mysqli_stmt_execute($stmt_reservasi);
        } else {
            // Untuk status selain "Gagal" (misalnya "Belum Dibayar", "Pending", dll), kita izinkan untuk menghapus dan mengedit.
            // Hapus data dari tabel pembayaran
            $query_pembayaran = "DELETE FROM pembayaran WHERE id_reservasi = ?";
            $stmt_pembayaran = mysqli_prepare($conn, $query_pembayaran);
            mysqli_stmt_bind_param($stmt_pembayaran, "i", $id_reservasi);
            mysqli_stmt_execute($stmt_pembayaran);

            // Hapus data dari tabel reservasi
            $query_reservasi = "DELETE FROM reservasi WHERE id_reservasi = ?";
            $stmt_reservasi = mysqli_prepare($conn, $query_reservasi);
            mysqli_stmt_bind_param($stmt_reservasi, "i", $id_reservasi);
            mysqli_stmt_execute($stmt_reservasi);
        }

        // Commit transaksi jika kedua query berhasil
        mysqli_commit($conn);

        // Menampilkan SweetAlert2 untuk sukses
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Reservasi Dihapus</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Reservasi Berhasil Dihapus',
                    text: 'Reservasi telah dihapus dengan sukses.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = '../show_reservasi.php';
                });
            </script>
        </body>
        </html>";
        exit();

    } catch (Exception $e) {
        // Jika ada kesalahan, rollback transaksi
        mysqli_rollback($conn);  // Perbaikan di sini
        // Menampilkan SweetAlert2 untuk error
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Gagal Menghapus</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus Reservasi',
                    text: 'Terjadi kesalahan saat menghapus reservasi.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = '../show_reservasi.php';
                });
            </script>
        </body>
        </html>";
        exit();
    }
} else {
    // Jika id_reservasi tidak ditemukan di URL
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>ID Reservasi Tidak Ditemukan</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'ID Reservasi Tidak Ditemukan',
                text: 'ID reservasi tidak ditemukan.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location = '../show_reservasi.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

// Tutup koneksi database
mysqli_close($conn);
?>
