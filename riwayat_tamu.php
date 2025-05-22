<?php
session_start();

include 'header.php'; 

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

// Include koneksi database
include 'koneksi/koneksi.php'; 

// Ambil ID pengguna yang sedang login
$id_tamu = $_SESSION['user_id'];

// Query untuk mendapatkan data reservasi yang hanya untuk tamu yang sedang login
$query = "SELECT r.id_reservasi, t.nama, t.no_hp, t.jk, k.tipe_kamar, r.jumlah_kamar, r.checkin, r.checkout, r.lama_inap, r.total_biaya, r.status_pembayaran
            FROM reservasi r
            JOIN tbl_tamu t ON r.id_tamu = t.id_tamu
            JOIN kamar k ON r.id_kamar = k.id_kamar
            WHERE r.id_tamu = ?"; // Filter berdasarkan id_tamu

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_tamu); // Bind ID tamu untuk filter
$stmt->execute();
$result = $stmt->get_result();
?>

<br>
<br>
<br>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Tamu - My Hotel</title>
    <!-- Link ke Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Daftar Reservasi Tamu</h2>
        
        <!-- Tabel untuk menampilkan data reservasi -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Reservasi</th>
                    <th>Nama Tamu</th>
                    <th>No HP</th>
                    <th>Jenis Kelamin</th>
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
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id_reservasi'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['no_hp'] . "</td>";
                        echo "<td>" . $row['jk'] . "</td>";
                        echo "<td>" . $row['tipe_kamar'] . "</td>";
                        echo "<td>" . $row['jumlah_kamar'] . "</td>";
                        echo "<td>" . $row['checkin'] . "</td>";
                        echo "<td>" . $row['checkout'] . "</td>";
                        echo "<td>" . $row['lama_inap'] . "</td>";
                        echo "<td>" . number_format($row['total_biaya'], 0, ',', '.') . "</td>";


                        // Menampilkan warna untuk status pembayaran
                        $status_class = '';
                        if ($row['status_pembayaran'] == 'Belum Dibayar') {
                            $status_class = 'text-danger';  // Merah
                        } elseif ($row['status_pembayaran'] == 'Pending') {
                            $status_class = 'text-warning';  // Oranye
                        } elseif ($row['status_pembayaran'] == 'Dibayar') {
                            $status_class = 'text-success';  // Hijau
                        } elseif ($row['status_pembayaran'] == 'Gagal') {
                            $status_class = 'text-danger';  // Hijau
                        }
                        echo "<td class='$status_class'>" . $row['status_pembayaran'] . "</td>";

                        // Tombol aksi untuk mengubah status pembayaran
                        echo "<td>";
                        if ($row['status_pembayaran'] == 'Belum Dibayar') {
                            echo "<a href='form_pembayaran.php?id_reservasi=" . $row['id_reservasi'] . "' class='btn btn-primary btn-sm'>Bayar Sekarang</a>";
                        } elseif ($row['status_pembayaran'] == 'Pending') {
                            echo "-"; // Tidak ada aksi jika status "Pending"
                        }                        
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>Tidak ada data reservasi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Link ke Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>


<?php 
include 'footer.php'; 
?>

<?php
// Menutup koneksi
$stmt->close();
mysqli_close($conn);
?>
