<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu - My Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php 
include 'header.php';
?>  

    <!-- Register Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding-top: 70px;">
        <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
            <h2 class="text-center mb-4">Daftar Tamu</h2>

            <!-- Register Form -->
            <form action="proses/insert_register_tamu.php" method="POST">
                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                </div>

                <!-- Jenis Kelamin -->
                <div class="mb-3">
                    <label for="jk" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jk" name="jk" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <!-- Nomor HP -->
                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor Handphone</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" required placeholder="Masukkan nomor handphone">
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" required placeholder="Masukkan alamat" rows="3"></textarea>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Masukkan email">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>

                <!-- Login Link -->
                <div class="mt-3 text-center">
                    <p>Sudah punya akun? <a href="login.php" class="text-decoration-none">Login Sekarang</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
