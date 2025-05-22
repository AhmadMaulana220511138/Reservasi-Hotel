<?php
session_start();

// Hapus hanya session admin
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Jangan hancurkan seluruh sesi
// session_destroy(); -> Jangan gunakan ini!

header("Location: index.php");
exit();
?>
