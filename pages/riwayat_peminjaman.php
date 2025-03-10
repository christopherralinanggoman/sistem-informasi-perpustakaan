<?php
session_start();
if(!isset($_SESSION['member_email'])){
    header("Location: ../member_login.php");
    exit;
}
include_once("../includes/config.php");

// Data member
$email = $_SESSION['member_email'];
$queryMember = "SELECT * FROM anggota WHERE email = '$email'";
$resultMember = mysqli_query($conn, $queryMember);
$member = mysqli_fetch_assoc($resultMember);
$nama_member = $member['nama'];
$id_anggota  = $member['id_anggota'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: #333;
            overflow: hidden;
        }
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .navbar li {
            float: left;
        }
        .navbar li a {
            display: block;
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar li a:hover {
            background: #555;
        }
        .navbar li.logout {
            float: right;
        }
        .navbar::after {
            content: "";
            display: table;
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }
        table th {
            background: #0052d4;
            color: #fff;
        }
        h2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Menu bar untuk Member -->
<div class="navbar">
    <ul>
        <li><a href="member_dashboard.php">Beranda</a></li>
        <li><a href="peminjaman_buku.php">Peminjaman Buku</a></li>
        <li><a href="riwayat_peminjaman.php">Riwayat Peminjaman</a></li>
        <li><a href="bayar_denda.php">Bayar Denda</a></li>
        <li class="logout"><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div style="margin: 20px; text-align: center;">
    <h2>Riwayat Peminjaman</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
        <?php
        // Menampilkan semua transaksi milik user (dipinjam atau dikembalikan)
        $historyQuery = "SELECT t.id_transaksi, t.tanggal_pinjam, t.due_date, t.tanggal_kembali, t.status, b.judul, b.gambar
                         FROM transaksi t
                         JOIN buku b ON t.id_buku = b.id_buku
                         WHERE t.id_anggota = '$id_anggota'
                         ORDER BY t.id_transaksi DESC";
        $historyResult = mysqli_query($conn, $historyQuery);

        // Counter for sequential numbering
        $no = 1;
        if(mysqli_num_rows($historyResult) > 0){
            while($row = mysqli_fetch_assoc($historyResult)){
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td><img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'></td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['tanggal_pinjam']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "<td>".($row['tanggal_kembali'] ? $row['tanggal_kembali'] : "-")."</td>";
                echo "<td>".$row['status']."</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7'>Belum ada riwayat peminjaman.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
