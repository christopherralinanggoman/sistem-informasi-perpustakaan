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

$msg = "";

// Proses pembayaran denda jika tombol bayar ditekan (menggunakan GET parameter id_transaksi)
if(isset($_GET['action']) && $_GET['action'] === 'bayar' && isset($_GET['id_transaksi'])){
    $id_transaksi = $_GET['id_transaksi'];
    // Tandai transaksi sebagai denda telah dibayar
    $updateQuery = "UPDATE transaksi SET denda_lunas = 1 WHERE id_transaksi = '$id_transaksi' AND id_anggota = '$id_anggota'";
    if(mysqli_query($conn, $updateQuery)){
        $msg = "Denda berhasil dibayar!";
    } else {
        $msg = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bayar Denda</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { background: #333; overflow: hidden; }
        .navbar ul { list-style: none; margin: 0; padding: 0; }
        .navbar li { float: left; }
        .navbar li a { display: block; color: #fff; padding: 14px 20px; text-decoration: none; }
        .navbar li a:hover { background: #555; }
        .navbar li.logout { float: right; }
        .navbar::after { content: ""; display: table; clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #999; padding: 8px; text-align: center; }
        table th { background: #0052d4; color: #fff; }
        .msg { color: green; margin-top: 10px; }
        .pay-btn { background-color: #28a745; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .pay-btn:hover { background-color: #218838; }
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
    <h2>Bayar Denda</h2>
    <?php if(!empty($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <!-- Tampilkan transaksi yang terlambat dan belum lunas denda -->
    <table>
        <tr>
            <th>No</th>
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
        // Query: ambil transaksi yang statusnya 'dikembalikan', terlambat, dan belum lunas denda
        $queryDenda = "SELECT t.id_transaksi, b.judul, b.gambar, t.tanggal_pinjam, t.due_date, t.tanggal_kembali, t.status
                       FROM transaksi t
                       JOIN buku b ON t.id_buku = b.id_buku
                       WHERE t.id_anggota = '$id_anggota'
                         AND t.status = 'dikembalikan'
                         AND t.tanggal_kembali > t.due_date
                         AND t.denda_lunas = 0
                       ORDER BY t.id_transaksi DESC";
        $resultDenda = mysqli_query($conn, $queryDenda);
        $no = 1;
        if(mysqli_num_rows($resultDenda) > 0){
            while($row = mysqli_fetch_assoc($resultDenda)){
                // Hitung keterlambatan dalam jam
                $due = new DateTime($row['due_date']);
                $returned = new DateTime($row['tanggal_kembali']);
                $interval = $due->diff($returned);
                // Konversi keterlambatan ke jam (misal setiap hari 24 jam)
                $hoursLate = ($interval->days * 24) + $interval->h;
                // Tarif denda per jam Rp 5000
                $fine = $hoursLate * 5000;
                
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td><img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'></td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['tanggal_pinjam']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "<td>".$row['tanggal_kembali']."</td>";
                echo "<td>".$hoursLate."</td>";
                echo "<td>".number_format($fine, 0, ',', '.')."</td>";
                echo "<td><button class='pay-btn' onclick=\"location.href='bayar_denda.php?action=bayar&id_transaksi=".$row['id_transaksi']."'\">Bayar</button></td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='9'>Tidak ada denda yang perlu dibayar.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
