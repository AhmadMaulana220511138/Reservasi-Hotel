<?php
// Koneksi ke database
include '../koneksi/koneksi.php';

include 'header_admin.php';

// Tangkap parameter pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tangkap parameter filter status pembayaran jika ada
$filter_status = isset($_GET['status_pembayaran']) ? $_GET['status_pembayaran'] : '';

// Query untuk menampilkan reservasi dengan status pembayaran "Pending"
$query = "SELECT r.id_reservasi, t.nama, t.no_hp, t.jk, k.tipe_kamar, r.jumlah_kamar, r.checkin, r.checkout, r.lama_inap, r.total_biaya, r.status_pembayaran
          FROM reservasi r
          JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
          JOIN kamar k ON r.id_kamar = k.id_kamar
          WHERE r.status_pembayaran = 'Pending' AND t.nama LIKE '%$search%'"; // Tambahkan kondisi pencarian berdasarkan nama tamu

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Konfirmasi Pembayaran</h3>

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
                        <option value="Pending" <?php echo ($filter_status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Gagal" <?php echo ($filter_status == 'Gagal') ? 'selected' : ''; ?>>Gagal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Reservasi</th>
                        <th>Nama Tamu</th>
                        <th>Tipe Kamar</th>
                        <th>Lama Inap (Hari)</th>
                        <th>Total Biaya</th>
                        <th>ID Pembayaran</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Jumlah Dibayar</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Memastikan query menggunakan parameter yang benar
                        $query = "SELECT 
                            r.id_reservasi,
                            t.nama,
                            k.tipe_kamar,
                            r.lama_inap,
                            r.total_biaya,
                            r.status_pembayaran AS status_reservasi,
                            p.id_pembayaran,
                            p.tanggal_pembayaran,
                            p.jumlah_dibayar,
                            p.status_pembayaran AS status_pembayaran
                        FROM 
                            reservasi r
                        JOIN 
                            tbl_tamu t ON r.id_tamu = t.id_tamu
                        JOIN 
                            kamar k ON r.id_kamar = k.id_kamar
                        LEFT JOIN 
                            pembayaran p ON r.id_reservasi = p.id_reservasi
                        WHERE 
                            (r.status_pembayaran IN ('Pending', 'Dibayar', 'Gagal') 
                            OR p.status_pembayaran IN ('Pending', 'Dibayar', 'Gagal'))
                            AND (t.nama LIKE ? OR r.id_reservasi LIKE ?)";


                        // Tambahkan filter status pembayaran jika ada
                        if (!empty($filter_status)) {
                            $query .= " AND (r.status_pembayaran = ? OR p.status_pembayaran = ?)";
                        }

                        // Tambahkan ORDER BY
                        $query .= " ORDER BY r.id_reservasi ASC";

                        // Siapkan statement
                        $stmt = $conn->prepare($query);

                        // Binding parameter berdasarkan kondisi
                        if (!empty($filter_status)) {
                            $searchParam = '%' . $search . '%';
                            $stmt->bind_param('ssss', $searchParam, $searchParam, $filter_status, $filter_status);
                        } else {
                            $searchParam = '%' . $search . '%';
                            $stmt->bind_param('ss', $searchParam, $searchParam);
                        }

                        // Eksekusi query
                        $stmt->execute();
                        $result = $stmt->get_result();


    
                    // Tampilkan data
                    while ($row = $result->fetch_assoc()): 
                        // Tentukan kelas warna berdasarkan status pembayaran
                        $rowClass = '';
                        if ($row['status_pembayaran'] === 'Dibayar') {
                            $rowClass = 'table-success'; // Hijau
                        } elseif ($row['status_pembayaran'] === 'Pending') {
                            $rowClass = 'table-warning'; // Oranye
                        } elseif ($row['status_pembayaran'] === 'Gagal') {
                            $rowClass = 'table-danger'; // Merah
                        }
                    ?>
                    <tr class="<?= $rowClass; ?>">
                        <td><?= $row['id_reservasi']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['tipe_kamar']; ?></td>
                        <td><?= $row['lama_inap']; ?></td>
                        <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                        <td><?= $row['id_pembayaran'] ?? '-'; ?></td>
                        <td><?= $row['tanggal_pembayaran'] ?? '-'; ?></td>
                        <td>Rp <?= $row['jumlah_dibayar'] ? number_format($row['jumlah_dibayar'], 0, ',', '.') : '-'; ?></td>
                        <td><?= $row['status_pembayaran'] ?? $row['status_reservasi']; ?></td>
                        <td>
                            <?php if ($row['status_pembayaran'] === 'Pending'): ?>
                                <!-- Tombol Konfirmasi -->
                                <form action="proses/proses_konfirmasi_pembayaran.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran ini?');">Konfirmasi</button>
                                </form>
                                <!-- Tombol Batal -->
                                <form action="proses/proses_batal_pembayaran.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">Batal</button>
                                </form>
                            <?php elseif ($row['status_pembayaran'] === 'Dibayar'): ?>
                                <span class="text-success">Sudah Dibayar</span>
                            <?php elseif ($row['status_pembayaran'] === 'Gagal'): ?>
                                <span class="text-danger">Pembayaran Gagal</span>
                            <?php else: ?>
                                <span class="text-muted">Tidak tersedia</span>
                            <?php endif; ?>
                        </td>
                        <td>
                        <a href="reservasi/detail_reservasi.php?id_reservasi=<?= $row['id_reservasi']; ?>&source=pembayaran" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
