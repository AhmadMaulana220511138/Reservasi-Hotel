<?php
// Cek apakah session sudah dimulai
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Pastikan Anda sudah menghubungkan ke database

// Ambil id_reservasi dari URL
if (isset($_GET['id_reservasi'])) {
    $id_reservasi = $_GET['id_reservasi'];

    // Ambil data reservasi
    $query = "SELECT reservasi.id_reservasi, tbl_tamu.nama AS nama_tamu, reservasi.status_pembayaran 
    FROM reservasi 
    JOIN tbl_tamu ON reservasi.id_tamu = tbl_tamu.id_tamu 
    WHERE reservasi.id_reservasi = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_reservasi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


    // Jika data reservasi tidak ditemukan
    if (mysqli_num_rows($result) == 0) {
        header("Location: daftar_reservasi.php?error=Reservasi tidak ditemukan.");
        exit();
    }

    // Ambil data untuk ditampilkan
    $row = mysqli_fetch_assoc($result);
    $nama_tamu = htmlspecialchars($row['nama_tamu']);
    $status_pembayaran = htmlspecialchars($row['status_pembayaran']);
} else {
    header("Location: daftar_reservasi.php?error=ID reservasi tidak ditemukan.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Reservasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h4>Hapus Reservasi</h4>
        </div>
        <div class="card-body">
            <p>Apakah Anda yakin ingin menghapus reservasi ini? Data yang dihapus tidak dapat dikembalikan.</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>ID Reservasi:</strong> <?php echo $id_reservasi; ?></li>
                <li class="list-group-item"><strong>Nama Tamu:</strong> <?php echo $nama_tamu; ?></li>
                <li class="list-group-item"><strong>Status Pembayaran:</strong> <?php echo $status_pembayaran; ?></li>
            </ul>
            <div class="d-flex justify-content-between">
                <a href="../proses/proses_hapus_reservasi.php?id_reservasi=<?php echo $id_reservasi; ?>" class="btn btn-danger">Hapus</a>
                <a href="../show_reservasi.php" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
