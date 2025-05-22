<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Sesuaikan dengan path koneksi database

// Cek apakah ada nomor_kamar yang diterima dari URL
if (isset($_GET['nomor_kamar'])) {
    $nomor_kamar = $_GET['nomor_kamar'];

    // Ambil data kamar berdasarkan nomor_kamar
    $query = "SELECT * FROM kamar WHERE nomor_kamar = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nomor_kamar);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $kamar = mysqli_fetch_assoc($result);

    if (!$kamar) {
        echo "<script>
                alert('Kamar tidak ditemukan!');
                window.location.href='show_kamar.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Nomor kamar tidak ditemukan!');
            window.location.href='show_kamar.php';
          </script>";
    exit();
}
?>

<?php include '../header_admin.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Kamar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Konfirmasi Hapus Kamar</h2>
    <div class="alert alert-warning">
        <strong>Warning!</strong> Anda akan menghapus kamar <strong><?php echo $kamar['nomor_kamar']; ?> - <?php echo $kamar['tipe_kamar']; ?></strong>. Apakah Anda yakin ingin melanjutkan?
    </div>

    <form action="../proses/proses_delete_kamar.php" method="post">
        <input type="hidden" name="nomor_kamar" value="<?php echo $kamar['nomor_kamar']; ?>">
        <div class="mb-3">
            <button type="submit" class="btn btn-danger">Hapus Kamar</button>
            <a href="../show_kamar.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
