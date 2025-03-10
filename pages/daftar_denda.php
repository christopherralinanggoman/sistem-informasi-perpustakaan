<?php
// pages/daftar_denda.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/config.php");
include_once("../includes/header.php");

// Process marking fine as paid if requested
if(isset($_GET['action']) && $_GET['action'] === 'lunas' && isset($_GET['id_transaksi'])){
    $id_transaksi = mysqli_real_escape_string($conn, $_GET['id_transaksi']);
    $updateQuery = "UPDATE transaksi SET denda_lunas = 1 WHERE id_transaksi = '$id_transaksi'";
    if(mysqli_query($conn, $updateQuery)){
        $msg = "Denda berhasil ditandai lunas!";
    } else {
        $msg = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Denda</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div style="margin: 20px; text-align: center;">
        <h2>Daftar Denda</h2>
        <?php if(isset($msg)) { echo "<p class='msg'>$msg</p>"; } ?>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Gambar Buku</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Due Date</th>
                <th>Tanggal Kembali</th>
                <th>Overdue (Jam)</th>
                <th>Denda (Rp)</th>
                <th>Aksi</th>
            </tr>
            <?php
            $queryDenda = "SELECT t.id_transaksi, a.nama AS nama_anggota, b.judul, b.gambar, t.tanggal_pinjam, t.due_date, t.tanggal_kembali, t.status
                           FROM transaksi t
                           JOIN anggota a ON t.id_anggota = a.id_anggota
                           JOIN buku b ON t.id_buku = b.id_buku
                           WHERE t.tanggal_kembali IS NOT NULL
                             AND t.tanggal_kembali > t.due_date
                             AND t.denda_lunas = 0
                           ORDER BY t.id_transaksi DESC";
            $resultDenda = mysqli_query($conn, $queryDenda);
            $no = 1;
            if(mysqli_num_rows($resultDenda) > 0){
                while($row = mysqli_fetch_assoc($resultDenda)){
                    $due = new DateTime($row['due_date']);
                    $returned = new DateTime($row['tanggal_kembali']);
                    $interval = $due->diff($returned);
                    $hoursLate = ($interval->days * 24) + $interval->h;
                    $fine = $hoursLate * 5000;
                    echo "<tr>";
                    echo "<td>".$no++."</td>";
                    echo "<td>".$row['nama_anggota']."</td>";
                    echo "<td><img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'></td>";
                    echo "<td>".$row['judul']."</td>";
                    echo "<td>".$row['tanggal_pinjam']."</td>";
                    echo "<td>".$row['due_date']."</td>";
                    echo "<td>".$row['tanggal_kembali']."</td>";
                    echo "<td>".$hoursLate."</td>";
                    echo "<td>Rp " . number_format($fine, 0, ',', '.') . "</td>";
                    echo "<td><a href='?action=lunas&id_transaksi=".$row['id_transaksi']."' class='pay-btn'>Tandai Lunas</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>Tidak ada denda yang belum dibayar</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>