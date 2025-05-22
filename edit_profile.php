<?php
session_start();
require 'koneksi/koneksi.php'; // Pastikan file koneksi ini benar

// Ambil ID tamu dari sesi
$id_tamu = $_SESSION['user_id'] ?? null;

if (!$id_tamu) {
    echo "ID tamu tidak ditemukan. Silakan login terlebih dahulu.";
    exit;
}

// Ambil data tamu dari database
$sql = "SELECT * FROM tbl_tamu WHERE id_tamu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tamu);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tamu = $result->fetch_assoc();
} else {
    echo "Data tamu tidak ditemukan.";
    exit;
}

// Proses update jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Gunakan password yang dimasukkan atau tetap dengan password lama jika kosong
    $password_hashed = !empty($password) ? $password : $tamu['password'];

    // Update data tamu
    $sql = "UPDATE tbl_tamu SET nama = ?, jk = ?, no_hp = ?, alamat = ?, email = ?, password = ? WHERE id_tamu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nama, $jk, $no_hp, $alamat, $email, $password_hashed, $id_tamu);

    if ($stmt->execute()) {
        // Menampilkan pesan sukses dan mengarahkan ke index.php
        echo "Profil berhasil diperbarui!";
        header("Location: index.php");  // Redirect ke index.php
        exit;
    } else {
        // Menampilkan pesan error dan mengarahkan ke index.php
        echo "Terjadi kesalahan: " . $conn->error;
        header("Location: index.php");  // Redirect ke index.php
        exit;
    }

    $stmt->close();
}
?>

<?php 
include 'header.php'; 
?>
<br>
<br>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Profil</h2>
    <form method="post">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($tamu['nama']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="jk" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jk" name="jk">
                <option value="L" <?= $tamu['jk'] === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="P" <?= $tamu['jk'] === 'P' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($tamu['no_hp']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($tamu['alamat']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($tamu['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a> 
    </form>
</div>
</body>
</html>
