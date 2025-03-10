<?php
session_start();
include_once("../includes/config.php");

// Optionally, check permissions (e.g. only allow admin or authorized member to delete)
if(isset($_GET['id_buku'])){
    $id_buku = $_GET['id_buku'];

    // Cek apakah buku sedang dipinjam
    $checkQuery = "SELECT * FROM transaksi WHERE id_buku = '$id_buku' AND status = 'dipinjam'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if(mysqli_num_rows($checkResult) > 0){
        $msg = "Tidak bisa menghapus: Buku sedang dipinjam.";
    } else {
        $deleteQuery = "DELETE FROM buku WHERE id_buku = '$id_buku'";
        if(mysqli_query($conn, $deleteQuery)){
            $msg = "Buku berhasil dihapus.";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
} else {
    $msg = "Buku tidak ditemukan.";
}

// Redirect back to the member dashboard with a message
header("Location: member_dashboard.php?msg=" . urlencode($msg));
exit;
?>
