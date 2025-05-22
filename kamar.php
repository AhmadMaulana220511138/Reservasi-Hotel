<?php
    include 'koneksi/koneksi.php';  // Pastikan nama file koneksi benar
?>

<?php 
    include 'header.php';
?>  

<br>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kamar - Hotelku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the room cards */
        .card {
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card-body {
            text-align: center;
        }

        .card-footer {
            background-color: #f8f9fa;
        }

        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }

        .btn-warning:hover {
            background-color: #e67e22;
            border-color: #e67e22;
        }

        /* Custom Button Size */
        .custom-btn {
            padding: 8px 20px; /* Mengatur padding untuk memperkecil tombol */
            font-size: 14px; /* Mengatur ukuran font agar lebih kecil */
        }

        /* Styling untuk Form Pencarian dan Filter */
        .search-filter-form .form-control {
            border-radius: 50px;
            box-shadow: none;
            height: 40px;
            padding-left: 20px;
        }

        .search-filter-form button {
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            height: 40px;
        }

        .search-filter-form button:hover {
            background-color: #f39c12;
        }

        .search-filter-form .row {
            justify-content: center;
        }

        .search-filter-form .col-md-4 {
            max-width: 200px;
        }
    </style>
</head>
<body>
<div class="container" style="padding-top: 60px; padding-bottom: 20px">
    <h3 class="text-center mb-4">Data Kamar My Hotel</h3>

<!-- Form Pencarian -->
<form action="" method="GET" class="search-filter-form mb-4">
    <div class="row">
        <div class="col-md-6 mb-3"> <!-- Mengubah dari col-md-4 menjadi col-md-6 -->
            <input type="text" name="search" class="form-control" placeholder="Cari Tipe Kamar..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <div class="col-md-3 mb-3">
            <button type="submit" class="btn btn-warning w-100">Cari</button>
        </div>
    </div>
</form>

<!-- Form Filter Tipe Kamar -->
<form action="" method="GET" class="search-filter-form mb-4">
    <div class="row">
        <div class="col-md-6 mb-3"> <!-- Mengubah dari col-md-4 menjadi col-md-6 -->
            <select name="filter" class="form-control">
                <option value="">Semua</option>
                <option value="Standar" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Standar') ? 'selected' : ''; ?>>Standar</option>
                <option value="Deluxe" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                <option value="Suite" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <button type="submit" class="btn btn-warning w-100">Filter</button>
        </div>
    </div>
</form>


    <div class="row">
    <?php 
        // Menyusun query berdasarkan pencarian dan filter
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

        // Query SQL dengan kondisi pencarian dan filter
        $sql = "SELECT * FROM kamar WHERE stok_kamar > 0";

        if (!empty($search)) {
            $sql .= " AND tipe_kamar LIKE '%$search%'";
        }

        if (!empty($filter)) {
            $sql .= " AND tipe_kamar = '$filter'";
        }

        // Eksekusi query
        $query = mysqli_query($conn, $sql);

        while ($data = mysqli_fetch_assoc($query)) {
            $image = $data["gambar"];
            $kode_kamar = $data["id_kamar"]; // id_kamar sebagai identifier
            $tipe_kamar = $data["tipe_kamar"];
            $harga = number_format($data["harga"], 0, ',', '.');
            $stok_kamar = $data["stok_kamar"]; // Mengambil stok_kamar
    ?>
        <!-- Card untuk setiap kamar -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-lg h-100">
                <img src="img/<?php echo $image; ?>" class="card-img-top" alt="Gambar Kamar">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $tipe_kamar; ?></h5> <!-- Menampilkan tipe kamar -->
                    <p class="card-text">
                        <i class="fa fa-tag" aria-hidden="true"></i> <strong>Harga:</strong> Rp <?php echo $harga; ?>
                    </p>
                    <p class="card-text">
                        <i class="fa fa-bed" aria-hidden="true"></i> <strong>Stok Tersedia:</strong> <?php echo $stok_kamar; ?> kamar
                    </p>
                </div>
                <div class="card-footer text-center">
                    <a href="form_insert_reservasi_tamu.php?id_kamar=<?php echo $kode_kamar; ?>" class="btn btn-warning custom-btn">Booking Sekarang</a>
                </div>
            </div>
        </div>
    <?php 
        } 
    ?>
    </div>
</div>

<?php 
include 'footer.php'; 
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
