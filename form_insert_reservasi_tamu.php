<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Silakan login terlebih dahulu.");
    exit();
}

// Include koneksi database
include 'koneksi/koneksi.php';

// Ambil ID Tamu dari session
$id_tamu = $_SESSION['user_id'];  // Pastikan session menyimpan ID yang sesuai

// Cek apakah id_kamar ada di URL
if (isset($_GET['id_kamar'])) {
    $id_kamar = $_GET['id_kamar'];

    // Ambil data kamar berdasarkan id_kamar
    $query_kamar = "SELECT id_kamar, tipe_kamar, harga, stok_kamar FROM kamar WHERE id_kamar = '$id_kamar'";
    $result_kamar = mysqli_query($conn, $query_kamar);
    $row_kamar = mysqli_fetch_assoc($result_kamar);
    $tipe_kamar = $row_kamar['tipe_kamar'];
    $harga = $row_kamar['harga'];
    $stok = $row_kamar['stok_kamar'];
} else {
    // Jika id_kamar tidak ditemukan, arahkan ke halaman daftar kamar
    echo "<script>alert('Kamar tidak ditemukan!'); window.location='kamar.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $jumlah_kamar = $_POST['jumlah_kamar'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    // $lama_inap = $_POST['lama_inap']; // TIDAK DIPERLUKAN LAGI KARNA PERHITUNGAN LAMA INAP SUDAH PAKAI TRIGGER
    $status_pembayaran = 'Belum Dibayar'; // Status pembayaran awalnya

    // Hitung total biaya
    $total_biaya = $harga * $jumlah_kamar * $lama_inap;

    // Cek apakah stok kamar cukup
    if ($stok >= $jumlah_kamar) {
        // Simpan data reservasi ke tabel reservasi tanpa mengurangi stok kamar
        $query_reservasi = "INSERT INTO reservasi (id_tamu, id_kamar, jumlah_kamar, checkin, checkout, total_biaya, status_pembayaran)
        VALUES ('$id_tamu', '$id_kamar', '$jumlah_kamar', '$checkin', '$checkout', '$total_biaya', '$status_pembayaran')";
    if (mysqli_query($conn, $query_reservasi)) {
        // Jika berhasil disimpan, tunggu konfirmasi pembayaran
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Reservasi Berhasil</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Reservasi Berhasil!',
                        text: 'Reservasi berhasil dilakukan, silakan lanjutkan pembayaran.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'riwayat_tamu.php?id_reservasi=" . mysqli_insert_id($conn) . "';
                        }
                    });
                </script>
            </body>
            </html>";
    } else {
        // Jika gagal menyimpan reservasi
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Reservasi Gagal</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error: " . mysqli_error($conn) . "',
                        showConfirmButton: true,
                        confirmButtonText: 'Kembali'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'form_insert_reservasi_tamu.php';
                        }
                    });
                </script>
            </body>
            </html>";
    }
    } else {
        // Jika stok kamar tidak cukup
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Stok Kamar Tidak Cukup</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Kamar Tidak Cukup!',
                        text: 'Stok kamar tidak cukup, silakan pilih kamar lain.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'form_insert_reservasi_tamu.php';
                        }
                    });
                </script>
            </body>
            </html>";
    }

}


?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Reservasi - My Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Fungsi untuk menghitung lama inap secara otomatis
        // function hitungLamaInap() {
        //     var checkin = new Date(document.getElementById("checkin").value);
        //     var checkout = new Date(document.getElementById("checkout").value);
        //     var diffTime = checkout - checkin;
        //     var diffDays = diffTime / (1000 * 3600 * 24); // Menghitung dalam hari
        //     if (diffDays >= 0) {
        //         document.getElementById("lama_inap").value = diffDays;
        //     } else {
        //         document.getElementById("lama_inap").value = 0;
        //     }
        // }
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2>Form Reservasi</h2>
        <form method="POST" action="form_insert_reservasi_tamu.php?id_kamar=<?php echo $id_kamar; ?>">
            <!-- Tampilkan tipe kamar dan harga -->
            <div class="mb-3">
                <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
                <input type="text" class="form-control" id="tipe_kamar" name="tipe_kamar" value="<?php echo $tipe_kamar; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="Rp <?php echo number_format($harga, 0, ',', '.'); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="jumlah_kamar" class="form-label">Jumlah Kamar</label>
                <input type="number" class="form-control" id="jumlah_kamar" name="jumlah_kamar" required min="1" max="5">
            </div>

            <div class="mb-3">
                <label for="checkin" class="form-label">Check-In</label>
                <input type="date" class="form-control" id="checkin" name="checkin">
            </div>

            <div class="mb-3">
                <label for="checkout" class="form-label">Check-Out</label>
                <input type="date" class="form-control" id="checkout" name="checkout">
            </div>
<!-- 
            <div class="mb-3">
                <label for="lama_inap" class="form-label">Lama Inap (Hari)</label>
                <input type="number" class="form-control" id="lama_inap" name="lama_inap" readonly>
            </div> -->

            <button type="submit" class="btn btn-primary">Booking</button>
            <a href="kamar.php" class="btn btn-danger">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
mysqli_close($conn);
?>
