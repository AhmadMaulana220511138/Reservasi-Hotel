<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

include '../koneksi/koneksi.php';

$resultTransaksi = null;

$totalRekap = 0.0;
$tanggalAwal = '';
$tanggalAkhir = '';

if (isset($_POST['rekap'])) {
    // Ambil tanggal yang dipilih dari form
    $tanggalAwal = $_POST['tanggal_awal'];
    $tanggalAkhir = $_POST['tanggal_akhir'];

    // echo "Tanggal Awal: " . $tanggalAwal . "<br>";
    // echo "Tanggal Akhir: " . $tanggalAkhir . "<br>";

    // Query untuk mendapatkan total pendapatan dari fungsi database
    $sqlRekap = "SELECT HitungPemasukanDibayarByTanggal(?, ?) AS total_pendapatan";
    $stmtRekap = $conn->prepare($sqlRekap);
    $stmtRekap->bind_param("ss", $tanggalAwal, $tanggalAkhir);

    if ($stmtRekap->execute()) {
        $resultRekap = $stmtRekap->get_result();
        if ($resultRekap) {
            $dataRekap = $resultRekap->fetch_assoc();
            $totalRekap = $dataRekap['total_pendapatan'] ?? 0.0;
        } else {
            echo "Error dalam mengambil data rekap: " . $stmtRekap->error;
        }
    } else {
        echo "Error eksekusi rekap query: " . $stmtRekap->error;
    }

    // Query untuk mengambil transaksi berdasarkan tanggal
    $sqlTransaksi = "SELECT r.id_reservasi, t.nama, k.tipe_kamar, r.lama_inap, r.total_biaya, 
    p.id_pembayaran, p.tanggal_pembayaran, p.jumlah_dibayar, p.status_pembayaran AS pembayaran_status,
    r.status_pembayaran AS reservasi_status
    FROM reservasi r
    JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
    JOIN kamar k ON r.id_kamar = k.id_kamar
    JOIN pembayaran p ON r.id_reservasi = p.id_reservasi
    WHERE p.status_pembayaran = 'Dibayar' 
    AND r.status_pembayaran = 'Dibayar' 
    AND (r.checkin BETWEEN ? AND ? OR r.checkout BETWEEN ? AND ?)";

    // Perbaiki bind_param untuk mengikat 4 parameter
    $stmtTransaksi = $conn->prepare($sqlTransaksi);
    $stmtTransaksi->bind_param("ssss", $tanggalAwal, $tanggalAkhir, $tanggalAwal, $tanggalAkhir);

    if ($stmtTransaksi->execute()) {
        $resultTransaksi = $stmtTransaksi->get_result();
        // Proses hasil transaksi jika diperlukan
    } else {
        echo "Error eksekusi query transaksi: " . $stmtTransaksi->error;
    }
}


// Bagian untuk download file Word
if (isset($_GET['download_word']) && $_GET['download_word'] == 'true') {
    require_once '../vendor/autoload.php';
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    if (!isset($_GET['tanggal_awal']) || !isset($_GET['tanggal_akhir'])) {
        echo "Tanggal tidak valid!";
        exit();
    }
    $tanggalAwal = $_GET['tanggal_awal'];
    $tanggalAkhir = $_GET['tanggal_akhir'];

    // Query ulang untuk mendapatkan data transaksi
    $sqlTransaksi = "SELECT r.id_reservasi, t.nama, k.tipe_kamar, r.lama_inap, r.total_biaya, 
    p.id_pembayaran, p.tanggal_pembayaran, p.jumlah_dibayar, p.status_pembayaran AS pembayaran_status
    FROM reservasi r
    JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
    JOIN kamar k ON r.id_kamar = k.id_kamar
    JOIN pembayaran p ON r.id_reservasi = p.id_reservasi
    WHERE p.status_pembayaran = 'Dibayar' 
    AND r.status_pembayaran = 'Dibayar' 
    AND (r.checkin BETWEEN ? AND ? OR r.checkout BETWEEN ? AND ?)";
    
    $stmtTransaksi = $conn->prepare($sqlTransaksi);
    $stmtTransaksi->bind_param("ssss", $tanggalAwal, $tanggalAkhir, $tanggalAwal, $tanggalAkhir);
    $stmtTransaksi->execute();
    $resultTransaksi = $stmtTransaksi->get_result();

    // Lanjutkan dengan membuat file Word seperti sebelumnya
    $section = $phpWord->addSection();
    $section->addText("Rekapan Pemasukan - My Hotel");
    $section->addText("Tanggal: " . date('d/m/Y', strtotime($tanggalAwal)) . " hingga " . date('d/m/Y', strtotime($tanggalAkhir)));

    $totalRekap = 0;

    $table = $section->addTable();
    $table->addRow();
    $table->addCell(2000)->addText("ID Reservasi");
    $table->addCell(2000)->addText("Nama Tamu");
    $table->addCell(2000)->addText("Tipe Kamar");
    $table->addCell(2000)->addText("Lama Inap");
    $table->addCell(2000)->addText("Total Biaya");
    $table->addCell(2000)->addText("ID Pembayaran");
    $table->addCell(2000)->addText("Tanggal Pembayaran");
    $table->addCell(2000)->addText("Jumlah Dibayar");
    $table->addCell(2000)->addText("Status Pembayaran");

    // Cek apakah ada transaksi
    if ($resultTransaksi && $resultTransaksi->num_rows > 0) {
        // Menambahkan transaksi ke dalam tabel
        while ($row = $resultTransaksi->fetch_assoc()) {
            $totalRekap += $row['total_biaya']; // Gunakan total_biaya untuk menghitung pendapatan sebenarnya
    
            // Menambahkan data transaksi ke dalam tabel
            $table->addRow();
            $table->addCell(2000)->addText($row['id_reservasi']);
            $table->addCell(2000)->addText($row['nama']);
            $table->addCell(2000)->addText($row['tipe_kamar']);
            $table->addCell(2000)->addText($row['lama_inap']);
            $table->addCell(2000)->addText("Rp " . number_format($row['total_biaya'], 0, ',', '.'));
            $table->addCell(2000)->addText($row['id_pembayaran']);
            $table->addCell(2000)->addText($row['tanggal_pembayaran']);
            $table->addCell(2000)->addText("Rp " . number_format($row['jumlah_dibayar'], 0, ',', '.'));
            $table->addCell(2000)->addText($row['pembayaran_status']);
        }
    
        // Menambahkan total pendapatan setelah data transaksi
        $section->addText("Total Pendapatan: Rp " . number_format($totalRekap, 0, ',', '.'));
    
    } else {
        // Jika tidak ada data transaksi, tampilkan pesan
        $section->addText("Tidak ada transaksi yang ditemukan untuk periode ini.");
    }

    // Menyimpan file Word
    $filename = 'rekapan_pemasukan_' . date('Y-m-d') . '.docx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $phpWord->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapan Pemasukan - My Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header_admin.php'; ?>

<div class="container mt-5">
    <h1>Rekapan Pemasukan</h1>
    <form method="POST" action="">
        <div class="row">
            <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required>
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
            </div>
            <div class="col-md-4 d-flex flex-column">
                <label class="form-label invisible">Tombol</label>
                <button type="submit" class="btn btn-primary mt-auto" name="rekap">Rekap</button>
            </div>
        </div>
    </form>



    <?php
    // Cek apakah query berhasil dan ada hasil
    if ($resultTransaksi && $resultTransaksi->num_rows > 0) {
        // Jika ada transaksi, tampilkan data
        echo "<h5 class='mt-4'>Tabel Transaksi</h5>";
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID Reservasi</th>";
        echo "<th>Nama Tamu</th>";
        echo "<th>Tipe Kamar</th>";
        echo "<th>Lama Inap (Hari)</th>";
        echo "<th>Total Biaya</th>";
        echo "<th>ID Pembayaran</th>";
        echo "<th>Tanggal Pembayaran</th>";
        echo "<th>Jumlah Dibayar</th>";
        echo "<th>Status Pembayaran</th>";
        echo "<th>Aksi</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $resultTransaksi->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_reservasi'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['tipe_kamar'] . "</td>";
            echo "<td>" . $row['lama_inap'] . "</td>";
            echo "<td>Rp " . number_format($row['total_biaya'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['id_pembayaran'] . "</td>";
            echo "<td>" . $row['tanggal_pembayaran'] . "</td>";
            echo "<td>Rp " . number_format($row['jumlah_dibayar'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['pembayaran_status'] . "</td>";
            echo "<td><a href='reservasi/detail_reservasi.php?id_reservasi=" . $row['id_reservasi'] . "&source=rekapan' class='btn btn-info btn-sm'>Detail</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        // Jika tidak ada transaksi ditemukan
        echo "<p>Data transaksi tidak ditemukan.</p>";
    }
    ?>

    <!-- Pindahkan bagian rekap dan tombol unduhan ke bawah tabel transaksi -->
    <?php
    if (isset($totalRekap)) {
        echo "<h5>Rekap Pemasukan dari " . htmlspecialchars($tanggalAwal) . " hingga " . htmlspecialchars($tanggalAkhir) . "</h5>";
        echo "<p>Total Pendapatan: Rp " . number_format($totalRekap, 0, ',', '.') . "</p>";
    }
    ?>

<div class="d-flex justify-content-center gap-3">
    <!-- Tautan untuk mengunduh Rekap dalam format Word -->
    <a href="rekapan_pemasukan.php?download_word=true&tanggal_awal=<?php echo $tanggalAwal; ?>&tanggal_akhir=<?php echo $tanggalAkhir; ?>" class="btn btn-success">
        Download Rekapan (.docx)
    </a>

    <!-- Tautan untuk kembali ke halaman utama -->
    <a href="dashboard.php" class="btn btn-secondary">Kembali ke Halaman Utama</a>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
