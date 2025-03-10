<?php
// pages/admin_dashboard.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/header.php");
?>

<h2>Dashboard Admin</h2>
<p>Selamat datang di Sistem Informasi Perpustakaan, Admin!</p>

<?php
include_once("../includes/footer.php");
?>
