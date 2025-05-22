<?php
// Koneksi ke database
include '../../koneksi/koneksi.php'; 

// Ambil id_reservasi dari URL
$id_reservasi = $_GET['id_reservasi'] ?? '';
$source = isset($_GET['source']) ? $_GET['source'] : ''; // Pastikan parameter 'source' diambil

// Tentukan URL kembali
$backUrl = ($source === 'rekapan') ? '../rekapan_pemasukan.php' : '../form_pembayaran_admin.php';

// Query untuk mendapatkan detail reservasi berdasarkan id_reservasi
$query = "SELECT 
            r.id_reservasi, 
            t.nama, 
            t.no_hp, 
            t.jk, 
            k.nomor_kamar, 
            k.tipe_kamar, 
            r.jumlah_kamar, 
            r.checkin, 
            r.checkout, 
            r.lama_inap, 
            r.total_biaya, 
            r.status_pembayaran
          FROM 
            reservasi r
          JOIN 
            tbl_tamu t ON r.id_tamu = t.id_tamu
          JOIN 
            kamar k ON r.id_kamar = k.id_kamar
          WHERE 
            r.id_reservasi = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id_reservasi); // Bind parameter untuk menghindari SQL Injection
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Ambil data dari query
    $row = $result->fetch_assoc();
} else {
    // Jika tidak ada data, tampilkan pesan error
    echo "Data reservasi tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th {
            white-space: nowrap;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        td {
            white-space: nowrap;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Detail Reservasi</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>ID Reservasi</th>
                    <td><?= $row['id_reservasi']; ?></td>
                </tr>
                <tr>
                    <th>Nama Tamu</th>
                    <td><?= $row['nama']; ?></td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td><?= $row['no_hp']; ?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td><?= $row['jk'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                </tr>
                <tr>
                    <th>Nomor Kamar</th>
                    <td><?= $row['nomor_kamar']; ?></td>
                </tr>
                <tr>
                    <th>Tipe Kamar</th>
                    <td><?= $row['tipe_kamar']; ?></td>
                </tr>
                <tr>
                    <th>Jumlah Kamar</th>
                    <td><?= $row['jumlah_kamar']; ?></td>
                </tr>
                <tr>
                    <th>Check-In</th>
                    <td><?= $row['checkin']; ?></td>
                </tr>
                <tr>
                    <th>Check-Out</th>
                    <td><?= $row['checkout']; ?></td>
                </tr>
                <tr>
                    <th>Lama Inap (Hari)</th>
                    <td><?= $row['lama_inap']; ?></td>
                </tr>
                <tr>
                    <th>Total Biaya</th>
                    <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td class="<?php 
                        if ($row['status_pembayaran'] === 'Belum Dibayar') {
                            echo 'text-danger';
                        } elseif ($row['status_pembayaran'] === 'Gagal') {
                            echo 'text-danger';
                        } elseif ($row['status_pembayaran'] === 'Pending') {
                            echo 'text-warning';
                        } elseif ($row['status_pembayaran'] === 'Dibayar') {
                            echo 'text-success';
                        } 
                    ?>">
                        <?= $row['status_pembayaran']; ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tombol Kembali -->
        <a href="<?= $backUrl; ?>" class="btn btn-primary">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
