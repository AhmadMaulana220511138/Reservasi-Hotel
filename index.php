<?php 
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card {
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-10px); /* Efek saat hover */
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .btn:hover {
        background-color: #007bff; /* Tombol berubah warna saat hover */
        box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3); /* Efek bayangan */
    }
</style>
</head>
<body>

<br>
<section class="py-5"> <!-- Menambahkan sedikit padding pada section -->
  <div class="container-fluid px-5"> <!-- Menggunakan padding horizontal px-4 -->
    <div class="row align-items-center">
      <!-- Teks di sebelah kiri -->
      <div class="col-md-4">
          <div class="max-w-lg">
              <h2 class="h2 font-weight-semibold text-dark">
                  Pengalaman Menginap yang Luar Biasa di My Hotel
              </h2>
              <p class="mt-4 text-muted">
                  My Hotel hadir untuk memberikan kenyamanan dan pengalaman menginap yang tak terlupakan. Dengan fasilitas lengkap dan pelayanan terbaik, kami memastikan setiap tamu merasa puas dan terlayani dengan baik.
              </p>
          </div>
      </div>


      <!-- Gambar di sebelah kanan tanpa space -->
      <div class="col-md-8 p-0">
        <img
          src="img/img2.jpg" 
          class="img-fluid rounded w-100"  
          style="height: 400px; object-fit: cover;"  
          alt="Hotel"
        />
      </div>
    </div>
  </div>
</section>

<!-- Tipe Kamar Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h3 class="text-center mb-4">Tipe Kamar Kami</h3>
    <div class="row">
        
        <!-- Tipe Kamar Standar -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="img/standar.jpg" class="card-img-top" alt="Kamar Standar" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Kamar Standar</h5>
                    <p class="card-text">Kamar yang nyaman dengan fasilitas dasar untuk kebutuhan menginap yang efisien.</p>
                    <h6><strong>Fasilitas:</strong></h6>
                    <ul>
                        <li>Tempat tidur double</li>
                        <li>AC</li>
                        <li>TV Kabel</li>
                        <li>Kamar mandi dalam dengan air panas</li>
                    </ul>
                    <p class="card-text"><strong>Harga:</strong> Rp 200.000 per malam</p>
                </div>
                <div class="card-footer text-center">
                    <a href="kamar.php?tipe=standar" class="btn btn-primary">Booking</a>
                </div>
            </div>
        </div>

        <!-- Tipe Kamar Deluxe -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="img/kmr1.jpg" class="card-img-top" alt="Kamar Deluxe" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Kamar Deluxe</h5>
                    <p class="card-text">Kamar mewah dengan fasilitas premium untuk kenyamanan ekstra selama menginap.</p>
                    <h6><strong>Fasilitas:</strong></h6>
                    <ul>
                        <li>Tempat tidur king size</li>
                        <li>AC</li>
                        <li>TV LED dengan berbagai saluran</li>
                        <li>Minibar</li>
                        <li>Shower air panas</li>
                    </ul>
                    <p class="card-text"><strong>Harga:</strong> Rp 400.000 per malam</p>
                </div>
                <div class="card-footer text-center">
                    <a href="kamar.php?tipe=deluxe" class="btn btn-primary">Booking</a>
                </div>
            </div>
        </div>

        <!-- Tipe Kamar Suite -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="img/kmr6.jpg" class="card-img-top" alt="Kamar Suite" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">Kamar Suite</h5>
                    <p class="card-text">Kamar eksklusif dengan fasilitas lengkap dan privasi tinggi untuk pengalaman menginap yang luar biasa.</p>
                    <h6><strong>Fasilitas:</strong></h6>
                    <ul>
                        <li>Ruang tamu pribadi</li>
                        <li>Tempat tidur king size</li>
                        <li>AC</li>
                        <li>TV layar datar besar</li>
                        <li>Minibar lengkap</li>
                        <li>Jaccuzi</li>
                        <li>Shower dan bathtub</li>
                    </ul>
                    <p class="card-text"><strong>Harga:</strong> Rp 600.000 per malam</p>
                </div>
                <div class="card-footer text-center">
                    <a href="kamar.php?tipe=suite" class="btn btn-primary">Booking</a>
                </div>
            </div>
        </div>

    </div>
  </div>
</section>

<?php 
include 'footer.php'; 
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
