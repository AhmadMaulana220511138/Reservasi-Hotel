<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Pastikan Anda sudah menghubungkan ke database

// Cek apakah ada ID reservasi yang dikirimkan melalui URL
if (isset($_GET['id_reservasi'])) {
    $id_reservasi = $_GET['id_reservasi'];

    // Ambil data reservasi dari database berdasarkan ID
    $query = "
        SELECT r.id_reservasi, t.nama AS nama_tamu, t.no_hp, t.jk AS jenis_kelamin, 
               r.id_kamar, k.nomor_kamar, k.tipe_kamar, r.jumlah_kamar, r.checkin, r.checkout, 
               r.lama_inap
        FROM reservasi r
        JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
        JOIN kamar k ON r.id_kamar = k.id_kamar
        WHERE r.id_reservasi = '$id_reservasi'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        die("Reservasi tidak ditemukan.");
    }

    // Ambil data reservasi
    $row = mysqli_fetch_assoc($result);
} else {
    die("ID reservasi tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data yang diubah dari form
    $id_kamar = $_POST['id_kamar'];
    $jumlah_kamar = $_POST['jumlah_kamar'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // Hitung lama inap
    $checkinDate = new DateTime($checkin);
    $checkoutDate = new DateTime($checkout);
    $lama_inap = $checkoutDate->diff($checkinDate)->days;
    $lama_inap = $lama_inap < 1 ? 1 : $lama_inap; // Minimal 1 hari

    // Ambil harga per malam dari database
    $harga_query = "SELECT harga FROM kamar WHERE id_kamar = '$id_kamar'";
    $harga_result = mysqli_query($conn, $harga_query);
    $harga_row = mysqli_fetch_assoc($harga_result);
    $harga_per_malam = $harga_row['harga'];

    // Hitung total biaya
    $total_biaya = $lama_inap * $harga_per_malam * $jumlah_kamar;

    // Update data reservasi ke database
    $update_query = "
        UPDATE reservasi
        SET id_kamar = '$id_kamar', 
            jumlah_kamar = '$jumlah_kamar', 
            checkin = '$checkin', 
            checkout = '$checkout',
            total_biaya = '$total_biaya'
        WHERE id_reservasi = '$id_reservasi'";

        if (mysqli_query($conn, $update_query)) {
            // Jika berhasil, tampilkan alert dan redirect ke halaman daftar reservasi
            echo "
            <!DOCTYPE html>
            <html lang='id'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Edit Reservasi</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Reservasi berhasil diperbarui!',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../show_reservasi.php';
                        }
                    });
                </script>
            </body>
            </html>";
        } else {
            // Jika gagal, tampilkan alert
            echo "
            <!DOCTYPE html>
            <html lang='id'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Edit Reservasi</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memperbarui reservasi!',
                        text: 'Terjadi kesalahan saat memperbarui data.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../show_reservasi.php';
                        }
                    });
                </script>
            </body>
            </html>";
        }
        exit();
    }
    ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi - My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Edit Reservasi</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="nama_tamu" class="form-label">Nama Tamu</label>
            <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" value="<?php echo $row['nama_tamu']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $row['no_hp']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" disabled>
                <option value="L" <?php echo ($row['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="P" <?php echo ($row['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_kamar" class="form-label">Nomor Kamar</label>
            <select class="form-select" id="id_kamar" name="id_kamar">
                <?php
                // Ambil data kamar yang memiliki stok > 0
                $kamar_query = "SELECT * FROM kamar WHERE stok_kamar > 0";
                $kamar_result = mysqli_query($conn, $kamar_query);
                while ($kamar = mysqli_fetch_assoc($kamar_result)) {
                    echo "<option value='" . $kamar['id_kamar'] . "' " . ($row['id_kamar'] == $kamar['id_kamar'] ? 'selected' : '') . ">" . $kamar['nomor_kamar'] . " (" . $kamar['tipe_kamar'] . ")</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah_kamar" class="form-label">Jumlah Kamar</label>
            <input type="number" class="form-control" id="jumlah_kamar" name="jumlah_kamar" value="<?php echo $row['jumlah_kamar']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="checkin" class="form-label">Check-In</label>
            <input type="date" class="form-control" id="checkin" name="checkin" value="<?php echo $row['checkin']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="checkout" class="form-label">Check-Out</label>
            <input type="date" class="form-control" id="checkout" name="checkout" value="<?php echo $row['checkout']; ?>" required onchange="updateLamaInap()">
        </div>
        <div class="mb-3">
            <label for="lama_inap" class="form-label">Lama Inap (Hari)</label>
            <input type="number" class="form-control" id="lama_inap" name="lama_inap" value="<?php echo $row['lama_inap']; ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Fungsi untuk menghitung lama inap berdasarkan tanggal checkin dan checkout
    function updateLamaInap() {
        var checkin = document.getElementById('checkin').value;
        var checkout = document.getElementById('checkout').value;
        
        if (checkin && checkout) {
            var checkinDate = new Date(checkin);
            var checkoutDate = new Date(checkout);

            // Menghitung selisih hari
            var diffTime = checkoutDate - checkinDate;
            var diffDays = diffTime / (1000 * 3600 * 24); // Menghitung hari
            
            // Jika lama inap kurang dari 1 hari, set ke 1 hari
            diffDays = diffDays < 1 ? 1 : diffDays;
            
            // Set nilai lama inap
            document.getElementById('lama_inap').value = diffDays;
        }
    }
</script>

</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
