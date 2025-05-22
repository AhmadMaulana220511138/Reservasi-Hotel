<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

include '../../koneksi/koneksi.php'; // Sesuaikan dengan path koneksi Anda

// Validasi nomor kamar yang diterima dari URL
if (isset($_GET['nomor_kamar']) && !empty(trim($_GET['nomor_kamar']))) {
    $nomor_kamar = trim($_GET['nomor_kamar']);
} else {
    echo "<script>alert('Nomor kamar tidak valid!'); window.location='../show_kamar.php';</script>";
    exit;
}


// Ambil data kamar berdasarkan nomor_kamar
$query = "SELECT * FROM kamar WHERE nomor_kamar = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $nomor_kamar);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $kamar = mysqli_fetch_assoc($result); // Ambil data kamar ke dalam array $kamar
    } else {
        echo "<script>alert('Kamar tidak ditemukan!'); window.location='../show_kamar.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Terjadi kesalahan pada query!'); window.location='../show_kamar.php';</script>";
    exit;
}
?>

<?php include '../header_admin.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar - My Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Kamar</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> mt-3">
                <?php echo $_SESSION['message']['text']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <!-- Form untuk edit kamar -->
            <form action="../proses/proses_edit_kamar.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
                    <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" value="<?php echo $kamar['nomor_kamar']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
                    <select class="form-select" id="tipe_kamar" name="tipe_kamar">
                        <option value="Standar" <?php echo ($kamar['tipe_kamar'] == "Standar") ? 'selected' : ''; ?>>Standar</option>
                        <option value="Deluxe" <?php echo ($kamar['tipe_kamar'] == "Deluxe") ? 'selected' : ''; ?>>Deluxe</option>
                        <option value="Suite" <?php echo ($kamar['tipe_kamar'] == "Suite") ? 'selected' : ''; ?>>Suite</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Kamar</label>
                    <input type="file" class="form-control" id="gambar" name="gambar">
                    <p>Gambar saat ini:</p>
                    <img src="../../img/<?php echo $kamar['gambar']; ?>" alt="Gambar Kamar" style="max-width: 200px; display: block;">
                    <!-- Menambahkan input hidden untuk gambar lama -->
                    <input type="hidden" name="gambar_lama" value="<?php echo $kamar['gambar']; ?>">
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $kamar['harga']; ?>" step="0.01">
                </div>

                <div class="mb-3">
                    <label for="stok" class="form-label">Stok Kamar</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $kamar['stok_kamar']; ?>" min="0">
                </div>

                <button type="submit" class="btn btn-primary">Update Kamar</button>
                <a href="../show_kamar.php" class="btn btn-secondary">Kembali</a>
            </form>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
