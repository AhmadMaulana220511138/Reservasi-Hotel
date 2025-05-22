<?php
// Cek apakah session sudah dimulai
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include 'header_admin.php';
include '../koneksi/koneksi.php'; // Pastikan Anda sudah menghubungkan ke database

// Tangkap parameter pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_status = isset($_GET['status_pembayaran']) ? $_GET['status_pembayaran'] : '';

// Tangkap parameter filter status pembayaran jika ada
$filter_status = isset($_GET['status_pembayaran']) ? $_GET['status_pembayaran'] : '';

// Tambahkan kondisi pencarian dan filter dalam query
$query = "
    SELECT r.id_reservasi, t.nama AS nama_tamu, t.no_hp, t.jk AS jenis_kelamin, 
           r.id_kamar, k.nomor_kamar, k.tipe_kamar, r.jumlah_kamar, r.checkin, r.checkout, 
           r.lama_inap, r.total_biaya, r.status_pembayaran
    FROM reservasi r
    JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
    JOIN kamar k ON r.id_kamar = k.id_kamar
    WHERE t.nama LIKE '%$search%'";

// Tambahkan filter status pembayaran jika ada
if ($filter_status) {
    $query .= " AND r.status_pembayaran = '$filter_status'";
}

$query .= " ORDER BY r.id_reservasi ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Reservasi - My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Daftar Reservasi</h2>

<!-- Form Pencarian -->
<form method="GET" action="" class="mb-4">
    <div class="row g-2">
        <div class="col-md-10">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama tamu" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
    </div>
</form>

<!-- Form Filter Status Pembayaran -->
<form method="GET" action="" class="mb-4">
    <div class="row g-2">
        <div class="col-md-10">
            <select name="status_pembayaran" class="form-select">
                <option value="">Semua Status Pembayaran</option>
                <option value="Dibayar" <?php echo ($filter_status == 'Dibayar') ? 'selected' : ''; ?>>Dibayar</option>
                <option value="Belum Dibayar" <?php echo ($filter_status == 'Belum Dibayar') ? 'selected' : ''; ?>>Belum Dibayar</option>
                <option value="Pending" <?php echo ($filter_status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Gagal" <?php echo ($filter_status == 'Gagal') ? 'selected' : ''; ?>>Gagal</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>


    <!-- Tabel Reservasi -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Reservasi</th>
                    <th>Nama Tamu</th>
                    <th>No HP</th>
                    <th>Jenis Kelamin</th>
                    <th>Nomor Kamar</th>
                    <th>Tipe Kamar</th>
                    <th>Jumlah Kamar</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Lama Inap (Hari)</th>
                    <th>Total Biaya</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="
                        <?php 
                            if ($row['status_pembayaran'] == 'Gagal') {
                                echo 'table-danger'; // Merah untuk Gagal
                            } elseif ($row['status_pembayaran'] == 'Belum Dibayar') {
                                echo 'table-danger'; // Oranye untuk Belum Dibayar
                            } elseif ($row['status_pembayaran'] == 'Pending') {
                                echo 'table-warning'; // Oranye untuk Pending
                            } elseif ($row['status_pembayaran'] == 'Dibayar') {
                                echo 'table-success'; // Hijau untuk Dibayar
                            }
                        ?>
                    ">
                        <td><?php echo $row['id_reservasi']; ?></td>
                        <td><?php echo $row['nama_tamu']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                        <td><?php echo ($row['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td><?php echo $row['nomor_kamar']; ?></td>
                        <td><?php echo $row['tipe_kamar']; ?></td>
                        <td><?php echo $row['jumlah_kamar']; ?> kamar</td>
                        <td><?php echo $row['checkin']; ?></td>
                        <td><?php echo $row['checkout']; ?></td>
                        <td><?php echo $row['lama_inap']; ?> hari</td>
                        <td><?php echo number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['status_pembayaran']; ?></td>
                        <td>
                            <?php if ($row['status_pembayaran'] == 'Gagal'): ?>
                                <a href="reservasi/hapus_reservasi.php?id_reservasi=<?php echo $row['id_reservasi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">Hapus</a>
                            <?php elseif ($row['status_pembayaran'] == 'Belum Dibayar' || $row['status_pembayaran'] == 'Pending'): ?>
                                <a href="reservasi/edit_reservasi.php?id_reservasi=<?php echo $row['id_reservasi']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="reservasi/hapus_reservasi.php?id_reservasi=<?php echo $row['id_reservasi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">Hapus</a>
                            <?php elseif ($row['status_pembayaran'] == 'Dibayar'): ?>
                                <a href="reservasi/hapus_reservasi.php?id_reservasi=<?php echo $row['id_reservasi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
