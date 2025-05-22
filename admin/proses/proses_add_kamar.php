<?php
// Cek apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?error=Silakan login terlebih dahulu.");
    exit();
}

// Memasukkan koneksi database
include '../../koneksi/koneksi.php';

// Inisialisasi variabel
$nomor_kamar = $tipe_kamar = $gambar = $harga = $stok_kamar = "";
$nomor_kamar_err = $tipe_kamar_err = $harga_err = $stok_kamar_err = $gambar_err = "";

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty(trim($_POST["nomor_kamar"]))) {
        $nomor_kamar_err = "Nomor kamar tidak boleh kosong.";
    } else {
        $nomor_kamar = trim($_POST["nomor_kamar"]);
    }

    if (empty($_POST["tipe_kamar"])) {
        $tipe_kamar_err = "Pilih tipe kamar.";
    } else {
        $tipe_kamar = $_POST["tipe_kamar"];
    }

    if (empty(trim($_POST["harga"]))) {
        $harga_err = "Harga kamar tidak boleh kosong.";
    } else {
        $harga = trim($_POST["harga"]);
    }

    if (empty(trim($_POST["stok_kamar"]))) {
        $stok_kamar_err = "Stok kamar tidak boleh kosong.";
    } elseif (!is_numeric($_POST["stok_kamar"]) || $_POST["stok_kamar"] < 0) {
        $stok_kamar_err = "Stok kamar harus berupa angka positif.";
    } else {
        $stok_kamar = intval($_POST["stok_kamar"]);
    }

    // Cek apakah gambar ada dan valid
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../../img/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["gambar"]["size"] > 5000000) {
                $gambar_err = "File terlalu besar. Maksimal 5MB.";
            } elseif (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                $gambar_err = "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
            } else {
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                    $gambar = basename($_FILES["gambar"]["name"]);
                } else {
                    $gambar_err = "Gagal mengupload gambar.";
                }
            }
        } else {
            $gambar_err = "File bukan gambar.";
        }
    }

    // Cek apakah nomor kamar sudah ada
    if (empty($nomor_kamar_err)) {
        $sql = "SELECT * FROM kamar WHERE nomor_kamar = ? LIMIT 1";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_nomor_kamar);
            $param_nomor_kamar = $nomor_kamar;

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    // Nomor kamar sudah ada, tampilkan pesan error dengan SweetAlert
                    echo "
                    <!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Gagal Menambahkan Kamar</title>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Nomor Kamar Sudah Ada!',
                                text: 'Gunakan nomor kamar yang berbeda.',
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../show_kamar.php';
                                }
                            });
                        </script>
                    </body>
                    </html>";
                    exit();
                }
            }
            $stmt->close();
        }
    }

    // Jika tidak ada error, simpan data ke database
    if (empty($nomor_kamar_err) && empty($tipe_kamar_err) && empty($harga_err) && empty($stok_kamar_err) && empty($gambar_err)) {
        // Query SQL untuk menambah kamar
        $sql = "INSERT INTO kamar (nomor_kamar, tipe_kamar, gambar, harga, stok_kamar) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameter
            $stmt->bind_param("ssssd", $param_nomor_kamar, $param_tipe_kamar, $param_gambar, $param_harga, $param_stok_kamar);

            // Set parameter
            $param_nomor_kamar = $nomor_kamar;
            $param_tipe_kamar = $tipe_kamar;
            $param_gambar = $gambar;
            $param_harga = $harga;
            $param_stok_kamar = $stok_kamar;

            // Eksekusi query
            if ($stmt->execute()) {
                echo "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Success</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Kamar berhasil ditambahkan.',
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../show_kamar.php';
                            }
                        });
                    </script>
                </body>
                </html>";
                exit();
            } else {
                echo "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Error</title>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan.',
                            text: 'Silakan coba lagi.',
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../show_kamar.php';
                            }
                        });
                    </script>
                </body>
                </html>";
                exit();
            }
        }
    } else {
        // Jika ada error validasi
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Gagal Menambahkan Kamar</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal menambahkan kamar.',
                    text: 'Pastikan semua field terisi dengan benar. {$nomor_kamar_err} {$tipe_kamar_err} {$harga_err} {$stok_kamar_err} {$gambar_err}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../show_kamar.php';
                    }
                });
            </script>
        </body>
        </html>";
        exit();
    }
}
// Tutup koneksi database
$conn->close();
?>
