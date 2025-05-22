<?php
// Koneksi ke database
include '../../koneksi/koneksi.php';

// Cek jika ID reservasi ada di POST
if (isset($_POST['id_reservasi'])) {
    // Ambil id_reservasi dari POST
    $id_reservasi = $_POST['id_reservasi'];

    // Mulai transaksi untuk menjaga konsistensi data
    $conn->begin_transaction();

    try {
        // Query untuk memperbarui status pembayaran di tabel reservasi
        $query_reservasi = "UPDATE reservasi SET status_pembayaran = 'Gagal' WHERE id_reservasi = ?";
        
        // Persiapkan statement untuk reservasi
        $stmt_reservasi = $conn->prepare($query_reservasi);
        $stmt_reservasi->bind_param("i", $id_reservasi);
        $stmt_reservasi->execute();

        // Query untuk memperbarui status pembayaran di tabel pembayaran
        $query_pembayaran = "UPDATE pembayaran SET status_pembayaran = 'Gagal' WHERE id_reservasi = ?";
        
        // Persiapkan statement untuk pembayaran
        $stmt_pembayaran = $conn->prepare($query_pembayaran);
        $stmt_pembayaran->bind_param("i", $id_reservasi);
        $stmt_pembayaran->execute();

        // Jika kedua query berhasil, commit transaksi
        $conn->commit();

        // Redirect ke halaman konfirmasi pembayaran dengan SweetAlert
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Konfirmasi Pembayaran</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Gagal',
                    text: 'Status pembayaran telah diperbarui menjadi gagal.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../form_pembayaran_admin.php?status=berhasil';
                    }
                });
            </script>
        </body>
        </html>";
        exit();

    } catch (Exception $e) {
        // Jika ada error, rollback transaksi dan tampilkan notifikasi error
        $conn->rollback();

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Gagal Membatalkan Reservasi</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memperbarui Pembayaran!',
                    text: 'Terjadi kesalahan saat memperbarui status pembayaran.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../form_pembayaran_admin.php';
                    }
                });
            </script>
        </body>
        </html>";
    }
} else {
    // Jika id_reservasi tidak ada
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
                text: 'ID reservasi yang Anda cari tidak ditemukan.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../form_pembayaran_admin.php';
                }
            });
        </script>
    </body>
    </html>";
}
?>
