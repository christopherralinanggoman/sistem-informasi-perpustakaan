<?php
session_start();
if(!isset($_SESSION['member_email'])){
    header("Location: ../member_login.php");
    exit;
}

include_once("../includes/config.php");

// Ambil informasi anggota dari sesi
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
    <title>Daftar Denda</title>
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
        .navbar li.logout {
            float: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #999;
            padding: 8px;
        }
        table th {
            background: #d40000;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Menu Navigasi -->
<div class="navbar">
    <ul>
        <li><a href="member_dashboard.php">Dashboard</a></li>
        <li><a href="riwayat_peminjaman.php">Riwayat Peminjaman</a></li>
        <li><a href="denda.php">Denda</a></li>
        <li class="logout"><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div style="margin: 20px;">
    <h2>Daftar Denda</h2>
    <p>Selamat datang, <?php echo $nama_member; ?>!</p>

    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Jumlah Denda</th>
            <th>Status</th>
        </tr>
        <?php
        // Query untuk mengambil denda yang dikenakan pada anggota ini
        $dendaQuery = "SELECT d.id_denda, b.judul, d.jumlah_denda, d.status
                       FROM denda d
                       JOIN transaksi t ON d.id_transaksi = t.id_transaksi
                       JOIN buku b ON t.id_buku = b.id_buku
                       WHERE d.id_anggota = '$id_anggota'
                       ORDER BY d.id_denda DESC";
        $dendaResult = mysqli_query($conn, $dendaQuery);
        $no = 1;

        if (mysqli_num_rows($dendaResult) > 0) {
            while($row = mysqli_fetch_assoc($dendaResult)){
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td>".htmlspecialchars($row['judul'])."</td>";
                echo "<td>Rp ".number_format($row['jumlah_denda'], 0, ',', '.')."</td>";
                echo "<td style='color: ".($row['status'] == 'belum dibayar' ? 'red' : 'green')."; font-weight: bold;'>".$row['status']."</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='4' style='text-align: center;'>Tidak ada denda</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
