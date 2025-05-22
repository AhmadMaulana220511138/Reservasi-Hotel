<?php
// Cek apakah session sudah dimulai
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include 'header_admin.php';

// Memasukkan koneksi database
include '../koneksi/koneksi.php';

// Ambil data kamar dari database
$query = "SELECT * FROM kamar";
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
    <title>Daftar Kamar - My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS untuk scroll tabel -->
    <style>
        .table-responsive {
            max-height: 600px; /* Batas tinggi tabel, sesuaikan dengan kebutuhan */
            overflow-y: auto;  /* Menambahkan scroll vertikal */
        }
    </style>
</head>
<body>

    <!-- Konten Daftar Kamar -->
    <div class="container my-5">
        <h2 class="mb-4">Daftar Kamar</h2>
        <a href="kamar/add_kamar.php" class="btn btn-primary mb-3">Tambah Kamar</a>

        <!-- Tambahkan div dengan class table-responsive -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe Kamar</th>
                        <th>Gambar</th>
                        <th>Harga</th>
                        <th>Stok Kamar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id_kamar']; ?></td>
                            <td><?php echo $row['nomor_kamar']; ?></td>
                            <td><?php echo $row['tipe_kamar']; ?></td>
                            <td>
                                <?php if ($row['gambar']): ?>
                                    <img src="../img/<?php echo $row['gambar']; ?>" alt="gambar kamar" width="100">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
                            <td><?php echo $row['stok_kamar']; ?> kamar</td>
                            <td>
                                <a href="kamar/edit_kamar.php?nomor_kamar=<?php echo $row['nomor_kamar']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="kamar/delete_kamar.php?nomor_kamar=<?php echo $row['nomor_kamar']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">Hapus</a>
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
