<?php
// Koneksi ke database
include 'koneksi/koneksi.php';

// Ambil ID reservasi dari URL
if (isset($_GET['id_reservasi'])) {
    $id_reservasi = $_GET['id_reservasi'];

    // Query untuk mengambil data reservasi yang dipilih
    $query = "SELECT r.id_reservasi, r.total_biaya, r.status_pembayaran, t.nama, k.tipe_kamar, r.jumlah_kamar, r.checkin, r.checkout, r.lama_inap
    FROM reservasi r
    JOIN kamar k ON r.id_kamar = k.id_kamar
    JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
    WHERE r.id_reservasi = ? AND r.status_pembayaran = 'Belum Dibayar'"; 

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        // Jika tidak ada data atau sudah dibayar
        header("Location: index.php?error=Reservasi tidak ditemukan atau sudah dibayar.");
        exit();
    }
} else {
    // Jika ID reservasi tidak ada di URL
    header("Location: index.php?error=ID Reservasi tidak ditemukan.");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h3 class="text-center">Form Pembayaran</h3>
        <?php
        if (isset($_GET['success'])) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['success']) . "</div>";
        }
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID Reservasi</th>
                        <th>Nama</th>
                        <th>Tipe Kamar</th>
                        <th>Jumlah Kamar</th>
                        <th>Lama Inap (Hari)</th>
                        <th>Total Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $row['id_reservasi']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['tipe_kamar']; ?></td>
                        <td><?= $row['jumlah_kamar']; ?></td>
                        <td><?= $row['lama_inap']; ?></td>
                        <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                        <td>
                            <form action="proses/proses_pembayaran.php" method="POST">
                                <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi']; ?>">
                                <label for="jumlah_dibayar" class="form-label">Jumlah Bayar:</label>
                                <input type="number" name="jumlah_dibayar" class="form-control" min="1" required>
                                <input type="hidden" name="tanggal_pembayaran" value="<?= date('Y-m-d'); ?>">
                                <button type="submit" class="btn btn-primary mt-2">Bayar</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</body>
</html>
