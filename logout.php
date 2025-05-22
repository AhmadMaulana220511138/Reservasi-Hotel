<?php
session_start();

// Hapus semua data sesi terkait pengguna
unset($_SESSION['user_id']);
unset($_SESSION['user_username']);

// Arahkan kembali ke halaman login
header("Location: login.php");
exit();
?>
