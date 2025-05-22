<?php
session_start();

// Cek jika admin belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ./index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - My Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">My Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="admin.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Sidebar and Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light p-4">
            <h4>Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#kamar" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="kamar">Kamar</a>
                    <div class="collapse" id="kamar">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link" href="kamar.php">Lihat Kamar</a></li>
                            <li class="nav-item"><a class="nav-link" href="tambah_kamar.php">Tambah Kamar</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservasi.php">Reservasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pembayaran.php">Pembayaran</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2>Dashboard Admin</h2>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Selamat datang, <?php echo $_SESSION['username']; ?>! Anda dapat mengelola kamar, reservasi, dan pembayaran di sini.
                    </div>
                </div>
            </div>

            <!-- Stats or Dashboard Content (Optional) -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kamar Tersedia</h5>
                            <p class="card-text">Jumlah kamar yang tersedia saat ini.</p>
                            <a href="kamar.php" class="btn btn-primary">Lihat Kamar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reservasi</h5>
                            <p class="card-text">Lihat daftar reservasi tamu.</p>
                            <a href="reservasi.php" class="btn btn-primary">Lihat Reservasi</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pembayaran</h5>
                            <p class="card-text">Lihat status pembayaran tamu.</p>
                            <a href="pembayaran.php" class="btn btn-primary">Lihat Pembayaran</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
