<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    // Jika tidak ada sesi admin, redirect ke halaman login admin
    header("Location: index.php");
    exit();
}

// Memasukkan koneksi database
include '../koneksi/koneksi.php';

// Query untuk mendapatkan total kamar yang tersedia
$sql = "SELECT COUNT(*) AS total_kamar FROM kamar WHERE stok_kamar > 0";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$totalKamar = $data['total_kamar'];


// Query untuk mendapatkan total reservasi yang sudah dibayar
$sql_reservasi = "SELECT COUNT(*) AS total_reservasi_dibayar FROM reservasi WHERE status_pembayaran = 'Dibayar'";
$result_reservasi = $conn->query($sql_reservasi);
$data_reservasi = $result_reservasi->fetch_assoc();
$totalReservasiDibayar = $data_reservasi['total_reservasi_dibayar'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Memanggil file header_admin.php -->
<?php include 'header_admin.php'; ?>

<!-- Dashboard Content -->
<div class="container mt-5">
    <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <p>Ini adalah halaman dashboard admin.</p>

    <!-- Menampilkan total kamar dan total reservasi yang sudah dibayar dalam satu baris -->
    <div class="row">
        <!-- Total Kamar Card -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Kamar Tersedia</h5>
                    <p class="card-text">
                    <?php echo $totalKamar; ?> 
                    </p>

                </div>
            </div>
        </div>

        <!-- Total Reservasi yang Sudah Dibayar Card -->
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Reservasi Dibayar</h5>
                    <p class="card-text">
                        <?php echo $totalReservasiDibayar; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Rekapan Kamar (Clickable Card) -->
        <div class="col-md-3">
            <a href="rekapan_pemasukan.php" style="text-decoration: none;">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Rekapan Kamar</h5>
                        <p class="card-text">Klik untuk rekapan pemasukan.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
