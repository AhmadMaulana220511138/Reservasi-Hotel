<?php include '../header_admin.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar - My Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Kamar</h2>
        <form action="../proses/proses_add_kamar.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
                <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" required>
            </div>

            <div class="mb-3">
                <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
                <select class="form-select" id="tipe_kamar" name="tipe_kamar" required>
                    <option value="Standar">Standar</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Kamar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" required>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" required>
            </div>

            <div class="mb-3">
                <label for="stok_kamar" class="form-label">Stok Kamar</label>
                <input type="number" class="form-control" id="stok_kamar" name="stok_kamar" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Kamar</button>
            <a href="../show_kamar.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
