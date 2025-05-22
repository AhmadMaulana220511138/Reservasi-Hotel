<?php
// Koneksi ke database
include '../../koneksi/koneksi.php';

// Pastikan id_reservasi ada dalam request POST
if (isset($_POST['id_reservasi'])) {
    $id_reservasi = $_POST['id_reservasi'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Query untuk mengupdate status pembayaran menjadi 'Dibayar' di tabel reservasi
        $query_reservasi = "UPDATE reservasi SET status_pembayaran = 'Dibayar' WHERE id_reservasi = ?";
        $stmt_reservasi = $conn->prepare($query_reservasi);
        $stmt_reservasi->bind_param("s", $id_reservasi);
        $stmt_reservasi->execute();

        // Query untuk mengupdate status pembayaran menjadi 'Dibayar' di tabel pembayaran
        $query_pembayaran = "UPDATE pembayaran SET status_pembayaran = 'Dibayar' WHERE id_reservasi = ?";
        $stmt_pembayaran = $conn->prepare($query_pembayaran);
        $stmt_pembayaran->bind_param("s", $id_reservasi);
        $stmt_pembayaran->execute();

        // Ambil data jumlah kamar yang dipesan dan id_kamar dari tabel reservasi
        $query_kamar = "SELECT id_kamar, jumlah_kamar FROM reservasi WHERE id_reservasi = ?";
        $stmt_kamar = $conn->prepare($query_kamar);
        $stmt_kamar->bind_param("s", $id_reservasi);
        $stmt_kamar->execute();
        $result_kamar = $stmt_kamar->get_result();

        if ($result_kamar->num_rows > 0) {
            $row = $result_kamar->fetch_assoc();
            $id_kamar = $row['id_kamar'];
            $jumlah_kamar = $row['jumlah_kamar'];

            // Kurangi stok kamar
            $query_update_stok = "UPDATE kamar SET stok_kamar = stok_kamar - ? WHERE id_kamar = ?";
            $stmt_update_stok = $conn->prepare($query_update_stok);
            $stmt_update_stok->bind_param("is", $jumlah_kamar, $id_kamar);
            $stmt_update_stok->execute();
        }

        // Commit transaksi
        $conn->commit();

        // Redirect dengan SweetAlert notifikasi sukses
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
                    title: 'Pembayaran Berhasil',
                    text: 'Status pembayaran telah berhasil diperbarui dan stok kamar telah terkurangi.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../form_pembayaran_admin.php?status=success';
                    }
                });
            </script>
        </body>
        </html>";
        exit();
        
    } catch (Exception $e) {
        // Jika terjadi error, rollback transaksi dan tampilkan notifikasi error
        $conn->rollback();

        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Gagal Memperbarui Pembayaran</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memperbarui Pembayaran',
                    text: 'Terjadi kesalahan saat memperbarui status pembayaran atau stok kamar.',
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
    // Jika id_reservasi tidak ditemukan
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
