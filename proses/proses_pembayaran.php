<?php
// Koneksi ke database
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reservasi = $_POST['id_reservasi'];
    $jumlah_dibayar = $_POST['jumlah_dibayar'];
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];

    // Ambil data reservasi
    $query = "SELECT total_biaya, id_kamar, jumlah_kamar FROM reservasi WHERE id_reservasi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $total_biaya = $row['total_biaya'];
        $id_kamar = $row['id_kamar'];
        $jumlah_kamar = $row['jumlah_kamar'];

        // Jika pembayaran lebih kecil dari harga, tampilkan error
        if ($jumlah_dibayar < $total_biaya) {
            // Menampilkan alert jika jumlah yang dibayar kurang
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Kesalahan Pembayaran</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal!',
                        text: 'Jumlah yang dibayar kurang dari total biaya Rp {$total_biaya}.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '../form_pembayaran.php?id_reservasi=$id_reservasi';
                        }
                    });
                </script>
            </body>
            </html>";
            exit();
        }

        // Jika pembayaran lebih besar atau sama dengan harga, lanjutkan pembayaran
        $kembalian = $jumlah_dibayar - $total_biaya;

        // Masukkan data pembayaran dengan status 'Pending'
        $insert_query = "INSERT INTO pembayaran (id_reservasi, tanggal_pembayaran, jumlah_dibayar, status_pembayaran) VALUES (?, ?, ?, 'Pending')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isd", $id_reservasi, $tanggal_pembayaran, $jumlah_dibayar);

        if ($insert_stmt->execute()) {
            // Update status pembayaran di tabel reservasi menjadi 'Pending'
            $update_query = "UPDATE reservasi SET status_pembayaran = 'Pending' WHERE id_reservasi = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $id_reservasi);
            $update_stmt->execute();

            // Menampilkan notifikasi sukses menggunakan SweetAlert2
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Pembayaran</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pembayaran Anda sebesar Rp {$jumlah_dibayar} telah diterima. Kembalian Anda sebesar Rp {$kembalian}.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '../riwayat_tamu.php?success=Pembayaran berhasil. Menunggu konfirmasi admin.';
                        }
                    });
                </script>
            </body>
            </html>";
            exit();
        } else {
            // Jika terjadi kesalahan saat memproses pembayaran
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Pembayaran</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                        showConfirmButton: true,
                        confirmButtonText: 'Coba Lagi'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '../form_pembayaran.php?id_reservasi=$id_reservasi';
                        }
                    });
                </script>
            </body>
            </html>";
            exit();
        }
    } else {
        // Jika data reservasi tidak ditemukan
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Pembayaran</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Data Reservasi Tidak Ditemukan!',
                    text: 'Reservasi dengan ID tersebut tidak ditemukan. Pastikan ID yang dimasukkan benar.',
                    showConfirmButton: true,
                    confirmButtonText: 'Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = '../form_pembayaran.php';
                    }
                });
            </script>
        </body>
        </html>";
        exit();
    }
} else {
    // Jika metode tidak valid
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pembayaran</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Metode Tidak Valid!',
                text: 'Metode pengiriman data tidak valid. Silakan coba lagi.',
                showConfirmButton: true,
                confirmButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '../form_pembayaran.php';
                }
            });
        </script>
    </body>
    </html>";
    exit();
}
?>
