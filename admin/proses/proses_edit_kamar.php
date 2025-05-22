<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Sesuaikan path koneksi

// Validasi jika ada data POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];  // Perbaiki menjadi stok (bukan stok_kamar)
    
    // Jika ada gambar yang diupload, proses penguploadannya
    if ($_FILES['gambar']['error'] == 0) {
        $gambar_name = $_FILES['gambar']['name'];
        $gambar_tmp_name = $_FILES['gambar']['tmp_name'];
        $gambar_path = '../../img/' . $gambar_name;
        move_uploaded_file($gambar_tmp_name, $gambar_path);
    } else {
        // Gambar lama jika tidak diubah
        $gambar_name = $_POST['gambar_lama'];
    }

    // Update data kamar ke database tanpa status
    $query = "UPDATE kamar SET tipe_kamar = ?, harga = ?, stok_kamar = ?, gambar = ? WHERE nomor_kamar = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $tipe_kamar, $harga, $stok, $gambar_name, $nomor_kamar);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        // Menampilkan notifikasi sukses dengan SweetAlert dan HTML lengkap
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Update Kamar</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Kamar berhasil diperbarui!',
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
        // Menampilkan notifikasi gagal dengan SweetAlert dan HTML lengkap
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Update Kamar</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memperbarui kamar!',
                    text: 'Terjadi kesalahan saat memperbarui data kamar.',
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