<?php 
include 'header.php';  // Pastikan Anda sudah memiliki header.php
?>
<br>
<br>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - My Hotel</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Hero Section -->
    <section class="bg-light py-5 text-center">
        <div class="container">
            <h1 class="display-3 font-weight-bold text-dark">Tentang My Hotel</h1>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center mb-4">
                    <h2 class="display-4 text-primary">Selamat Datang di My Hotel</h2>
                    <p class="lead text-muted">Kami berkomitmen untuk memberikan pengalaman menginap terbaik dengan pelayanan profesional dan fasilitas yang lengkap.</p>
                </div>

                <!-- Card 1: Sejarah -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-light h-100">
                        <div class="card-body">
                            <h3 class="card-title">Sejarah Kami</h3>
                            <p class="card-text">
                                My Hotel didirikan pada tahun 2010 dengan tujuan untuk memberikan pengalaman menginap yang nyaman dan menyenangkan. Dengan pelayanan yang ramah dan fasilitas modern, kami terus berupaya menjadi pilihan utama bagi wisatawan dan pebisnis.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Visi dan Misi -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-light h-100">
                        <div class="card-body">
                            <h3 class="card-title">Visi dan Misi</h3>
                            <p class="card-text"><strong>Visi:</strong> Menjadi hotel terbaik yang memberikan kenyamanan dan pelayanan luar biasa bagi setiap tamu kami.</p>
                            <p class="card-text"><strong>Misi:</strong> Menyediakan pengalaman menginap yang tak terlupakan dengan fasilitas lengkap dan pelayanan profesional.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Lokasi -->
                <div class="col-md-12 text-center mt-4">
                    <h3 class="text-dark">Lokasi Kami</h3>
                    <p class="text-muted">
                        My Hotel terletak di jantung kota, memberikan akses mudah ke tempat-tempat wisata, pusat perbelanjaan, dan transportasi umum.
                    </p>
                    <a href="https://www.google.com/maps" target="_blank" class="btn btn-primary btn-lg">Lihat di Google Maps</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
include 'footer.php'; 
?>