-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2025 at 01:37 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_hotel`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `HitungPemasukanDibayarByTanggal` (`tanggal_awal` DATE, `tanggal_akhir` DATE) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
    DECLARE total_pemasukan DECIMAL(10,2);

    SELECT COALESCE(SUM(LEAST(p.jumlah_dibayar, r.total_biaya)), 0)
    INTO total_pemasukan
    FROM pembayaran p
    JOIN reservasi r ON p.id_reservasi = r.id_reservasi
    WHERE p.status_pembayaran = 'Dibayar' 
      AND r.status_pembayaran = 'Dibayar'
      AND r.checkin BETWEEN tanggal_awal AND tanggal_akhir;

    RETURN total_pemasukan;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `tipe_kamar` enum('Standar','Deluxe','Suite') NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok_kamar` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `nomor_kamar`, `tipe_kamar`, `gambar`, `harga`, `stok_kamar`) VALUES
(1, 'K001', 'Standar', 'kmr7.jpg', '200000.00', 29),
(2, 'K002', 'Deluxe', 'kmr1.jpg', '400000.00', 49),
(3, 'K003', 'Suite', 'kmr6.jpg', '600000.00', 30),
(4, 'K004', 'Standar', 'kmr3.jpg', '200000.00', 21),
(5, 'K005', 'Standar', 'alena-aenami-wait.jpg', '400000.00', 0),
(6, 'K006', 'Suite', 'alena-aenami-witcher-1k.jpg', '600000.00', 0),
(8, 'K007', 'Standar', 'alena-aenami-horizon-1k.jpg', '200000.00', 0),
(9, 'K008', 'Standar', '', '200000.00', 0),
(10, 'K009', 'Standar', 'alena-aenami-15step.jpg', '200000.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_tamu`
--

CREATE TABLE `login_tamu` (
  `id_login` int NOT NULL,
  `id_tamu` int DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login_tamu`
--

INSERT INTO `login_tamu` (`id_login`, `id_tamu`, `username`, `password`) VALUES
(2, 3, 'Ahmad', '123'),
(3, 4, 'aldi', '123');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_reservasi` int NOT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `jumlah_dibayar` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('Belum Dibayar','Dibayar','Pending','Gagal') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_reservasi`, `tanggal_pembayaran`, `jumlah_dibayar`, `status_pembayaran`) VALUES
(27, 14, '2024-12-23', '1000000.00', 'Gagal'),
(28, 15, '2024-12-23', '200000.00', 'Gagal'),
(29, 16, '2024-12-23', '200000.00', 'Gagal'),
(30, 17, '2024-12-23', '400000.00', 'Gagal'),
(31, 18, '2024-12-23', '400000.00', 'Dibayar'),
(32, 19, '2024-12-23', '400000.00', 'Dibayar'),
(33, 20, '2024-12-23', '400000.00', 'Gagal'),
(34, 21, '2024-12-23', '200000.00', 'Gagal'),
(35, 22, '2024-12-23', '400000.00', 'Gagal'),
(36, 23, '2024-12-23', '200000.00', 'Gagal'),
(37, 24, '2024-12-23', '200000.00', 'Gagal'),
(38, 25, '2024-12-24', '200000.00', 'Gagal'),
(39, 26, '2024-12-24', '200000.00', 'Gagal'),
(40, 27, '2024-12-24', '200000.00', 'Dibayar'),
(41, 28, '2024-12-24', '200000.00', 'Dibayar'),
(42, 29, '2024-12-24', '200000.00', 'Gagal'),
(43, 30, '2024-12-24', '200000.00', 'Dibayar'),
(44, 32, '2024-12-25', '200000.00', 'Dibayar'),
(45, 31, '2024-12-25', '200000.00', 'Gagal'),
(46, 33, '2024-12-25', '200000.00', 'Dibayar'),
(47, 34, '2024-12-25', '200000.00', 'Gagal'),
(50, 37, '2024-12-27', '1100000.00', 'Dibayar'),
(51, 38, '2024-12-27', '200000.00', 'Dibayar'),
(53, 40, '2024-12-27', '400000.00', 'Gagal'),
(54, 41, '2024-12-27', '12000000.00', 'Gagal'),
(55, 42, '2024-12-27', '400000.00', 'Dibayar'),
(56, 43, '2024-12-27', '400000.00', 'Dibayar'),
(57, 45, '2024-12-27', '1000000.00', 'Dibayar'),
(58, 44, '2024-12-27', '200000.00', 'Dibayar'),
(59, 46, '2024-12-27', '400000.00', 'Gagal'),
(60, 48, '2024-12-29', '200000.00', 'Gagal'),
(61, 47, '2024-12-29', '10000000.00', 'Dibayar'),
(62, 49, '2024-12-30', '4000000.00', 'Dibayar'),
(63, 55, '2025-01-10', '1600000.00', 'Dibayar'),
(64, 54, '2025-01-10', '400000.00', 'Pending'),
(65, 53, '2025-01-10', '1000000.00', 'Pending'),
(66, 57, '2025-01-10', '2400000.00', 'Pending'),
(67, 58, '2025-01-15', '200000.00', 'Dibayar'),
(68, 59, '2025-01-16', '2000000.00', 'Dibayar'),
(69, 60, '2025-01-19', '1000000.00', 'Dibayar'),
(70, 56, '2025-01-19', '600000.00', 'Gagal'),
(71, 52, '2025-01-27', '800000.00', 'Dibayar'),
(72, 61, '2025-02-05', '800000.00', 'Dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int NOT NULL,
  `id_tamu` int DEFAULT NULL,
  `id_kamar` int DEFAULT NULL,
  `jumlah_kamar` int NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `lama_inap` int GENERATED ALWAYS AS ((to_days(`checkout`) - to_days(`checkin`))) STORED,
  `total_biaya` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('Belum Dibayar','Dibayar','Pending','Gagal') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `id_tamu`, `id_kamar`, `jumlah_kamar`, `checkin`, `checkout`, `total_biaya`, `status_pembayaran`) VALUES
(14, 3, 1, 5, '2024-12-23', '2024-12-24', '1000000.00', 'Gagal'),
(15, 3, 1, 1, '2024-12-23', '2024-12-24', '200000.00', 'Gagal'),
(16, 3, 1, 1, '2024-12-23', '2024-12-24', '200000.00', 'Gagal'),
(17, 3, 2, 1, '2024-12-23', '2024-12-24', '400000.00', 'Gagal'),
(18, 3, 2, 1, '2024-12-23', '2024-12-24', '400000.00', 'Dibayar'),
(19, 3, 2, 1, '2024-12-23', '2024-12-24', '400000.00', 'Dibayar'),
(20, 3, 2, 1, '2024-12-23', '2024-12-24', '400000.00', 'Gagal'),
(21, 3, 1, 1, '2024-12-23', '2024-12-24', '200000.00', 'Gagal'),
(22, 3, 1, 2, '2024-12-23', '2024-12-24', '400000.00', 'Gagal'),
(23, 3, 1, 1, '2024-12-23', '2024-12-24', '200000.00', 'Gagal'),
(24, 3, 1, 1, '2024-12-23', '2024-12-24', '200000.00', 'Gagal'),
(25, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Gagal'),
(26, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Gagal'),
(27, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Dibayar'),
(28, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Dibayar'),
(29, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Gagal'),
(30, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Dibayar'),
(31, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Gagal'),
(32, 3, 1, 1, '2024-12-24', '2024-12-25', '200000.00', 'Dibayar'),
(33, 3, 1, 1, '2024-12-25', '2024-12-26', '200000.00', 'Dibayar'),
(34, 3, 1, 1, '2024-12-25', '2024-12-26', '200000.00', 'Gagal'),
(37, 4, 1, 5, '2024-12-28', '2024-12-29', '1000000.00', 'Dibayar'),
(38, 4, 1, 1, '2024-12-27', '2024-12-28', '200000.00', 'Dibayar'),
(40, 4, 2, 1, '2024-12-27', '2024-12-28', '400000.00', 'Gagal'),
(41, 4, 2, 1, '2024-12-28', '2024-12-31', '1200000.00', 'Gagal'),
(42, 4, 2, 1, '2024-12-27', '2024-12-28', '400000.00', 'Dibayar'),
(43, 4, 2, 1, '2024-12-27', '2024-12-28', '400000.00', 'Dibayar'),
(44, 4, 1, 1, '2024-12-27', '2024-12-28', '200000.00', 'Dibayar'),
(45, 3, 2, 1, '2024-12-27', '2024-12-28', '400000.00', 'Dibayar'),
(46, 4, 2, 1, '2024-12-27', '2024-12-28', '400000.00', 'Gagal'),
(47, 3, 1, 5, '2024-12-29', '2024-12-30', '1000000.00', 'Dibayar'),
(48, 3, 1, 1, '2024-12-29', '2024-12-30', '200000.00', 'Gagal'),
(49, 4, 1, 1, '2024-12-29', '2024-12-31', '400000.00', 'Dibayar'),
(50, 4, 1, 5, '2024-12-30', '2024-12-31', '1000000.00', 'Belum Dibayar'),
(51, 3, 1, 2, '2025-01-10', '2025-01-11', '400000.00', 'Belum Dibayar'),
(52, 3, 1, 1, '2025-01-10', '2025-01-14', '800000.00', 'Dibayar'),
(53, 3, 1, 1, '2025-01-10', '2025-01-15', '1000000.00', 'Pending'),
(54, 3, 2, 1, '2025-01-10', '2025-01-15', '2000000.00', 'Pending'),
(55, 3, 2, 1, '2025-01-10', '2025-01-12', '800000.00', 'Dibayar'),
(56, 4, 3, 1, '2025-01-10', '2025-01-11', '600000.00', 'Gagal'),
(57, 4, 3, 2, '2025-01-10', '2025-01-12', '2400000.00', 'Pending'),
(58, 6, 1, 1, '2025-01-15', '2025-01-16', '200000.00', 'Dibayar'),
(59, 7, 2, 1, '2025-01-16', '2025-01-17', '400000.00', 'Dibayar'),
(60, 4, 1, 5, '2025-01-19', '2025-01-20', '1000000.00', 'Dibayar'),
(61, 8, 2, 1, '2025-02-05', '2025-02-07', '800000.00', 'Dibayar');

--
-- Triggers `reservasi`
--
DELIMITER $$
CREATE TRIGGER `menghitung_lama_inap` BEFORE INSERT ON `reservasi` FOR EACH ROW BEGIN
    DECLARE diff_days INT;
    DECLARE harga_per_malam DECIMAL(10, 2);

    -- Menghitung selisih hari antara checkin dan checkout
    SET diff_days = DATEDIFF(NEW.checkout, NEW.checkin);

    -- Jika lama inap kurang dari 1 hari, set ke 1 hari
    IF diff_days < 1 THEN
        SET diff_days = 1;
    END IF;

    -- Mengambil harga per malam dari tabel kamar berdasarkan id_kamar
    SELECT harga INTO harga_per_malam FROM kamar WHERE id_kamar = NEW.id_kamar LIMIT 1;

    -- Menghitung total biaya berdasarkan lama inap dan harga per malam
    SET NEW.lama_inap = diff_days;
    SET NEW.total_biaya = diff_days * harga_per_malam * NEW.jumlah_kamar;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_lama_inap` BEFORE UPDATE ON `reservasi` FOR EACH ROW BEGIN
    DECLARE diff_days INT;
    -- Menghitung selisih hari antara checkin dan checkout
    SET diff_days = DATEDIFF(NEW.checkout, NEW.checkin);
    
    -- Jika lama inap kurang dari 1 hari, set ke 1 hari
    IF diff_days < 1 THEN
        SET diff_days = 1;
    END IF;
    
    -- Mengupdate kolom lama_inap
    SET NEW.lama_inap = diff_days;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tamu`
--

CREATE TABLE `tbl_tamu` (
  `id_tamu` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_tamu`
--

INSERT INTO `tbl_tamu` (`id_tamu`, `nama`, `jk`, `no_hp`, `alamat`, `email`, `password`) VALUES
(3, 'ahmad', 'L', '0987632', 'cirebon', 'ahmadmaulana242004@gmail.com', '123'),
(4, 'aldi', 'L', '0987632', 'kuningan', 'aldi@gmail.com', '123'),
(5, 'Aviana', 'P', '083824977070', 'cirebon', 'aviana@gmail.com', '123'),
(6, 'dafa', 'L', '0987', 'cirebon', 'dafa@gmail.com', '123'),
(7, 'Ridho', 'L', '088245744532', 'Perumnas', 'ridho@gmail.com', '123'),
(8, 'Ahmad Maulana', 'L', '097899969', 'cirebon', 'ahmadmaulana@gmail.com', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD UNIQUE KEY `nomor_kamar` (`nomor_kamar`);

--
-- Indexes for table `login_tamu`
--
ALTER TABLE `login_tamu`
  ADD PRIMARY KEY (`id_login`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_tamu` (`id_tamu`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_reservasi` (`id_reservasi`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `id_tamu` (`id_tamu`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `tbl_tamu`
--
ALTER TABLE `tbl_tamu`
  ADD PRIMARY KEY (`id_tamu`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `login_tamu`
--
ALTER TABLE `login_tamu`
  MODIFY `id_login` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tbl_tamu`
--
ALTER TABLE `tbl_tamu`
  MODIFY `id_tamu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_tamu`
--
ALTER TABLE `login_tamu`
  ADD CONSTRAINT `login_tamu_ibfk_1` FOREIGN KEY (`id_tamu`) REFERENCES `tbl_tamu` (`id_tamu`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`);

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`id_tamu`) REFERENCES `tbl_tamu` (`id_tamu`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
