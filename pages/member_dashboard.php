<?php
session_start();
if(!isset($_SESSION['member_email'])){
    header("Location: ../member_login.php");
    exit;
}
include_once("../includes/config.php");

// Get member data from session
$email = $_SESSION['member_email'];
$queryMember = "SELECT * FROM anggota WHERE email = '$email'";
$resultMember = mysqli_query($conn, $queryMember);
$member = mysqli_fetch_assoc($resultMember);
$nama_member = $member['nama'];
$id_anggota  = $member['id_anggota'];

$msg = "";

// --- Process Book Return ---
if(isset($_GET['action']) && $_GET['action'] === 'return' && isset($_GET['id_transaksi'])){
    $id_transaksi = $_GET['id_transaksi'];
    // Get id_buku from the transaction
    $transQuery = "SELECT id_buku FROM transaksi WHERE id_transaksi = '$id_transaksi' AND id_anggota = '$id_anggota'";
    $transRes = mysqli_query($conn, $transQuery);
    if($transRow = mysqli_fetch_assoc($transRes)){
        $id_buku = $transRow['id_buku'];
        // Update the transaction: mark as returned and set the return date
        $updateQuery = "UPDATE transaksi
                        SET status = 'dikembalikan', tanggal_kembali = NOW()
                        WHERE id_transaksi = '$id_transaksi' AND id_anggota = '$id_anggota'";
        if(mysqli_query($conn, $updateQuery)){
            // Increase the book stock by 1
            $updateStok = "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'";
            mysqli_query($conn, $updateStok);
            $msg = "Buku berhasil dikembalikan!";
        } else {
            $msg = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    } else {
        $msg = "Transaksi tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>
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
        h2, h3 { margin-top: 20px; }
        .msg { color: green; }
        .borrow-btn { background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .borrow-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<!-- Member Menu Bar (including "Bayar Denda") -->
<div class="navbar">
    <ul>
        <li><a href="member_dashboard.php">Beranda</a></li>
        <li><a href="peminjaman_buku.php">Peminjaman Buku</a></li>
        <li><a href="riwayat_peminjaman.php">Riwayat Peminjaman</a></li>
        <li><a href="bayar_denda.php">Bayar Denda</a></li>
        <li class="logout"><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div style="margin: 20px;">
    <h2>Member Dashboard</h2>
    <p>Selamat datang, <?php echo $nama_member; ?>!</p>
    <?php if(!empty($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <!-- Section: Books Currently Borrowed (with images) -->
    <h3>Buku yang Sedang Anda Pinjam</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $borrowedQuery = "SELECT t.id_transaksi, t.tanggal_pinjam, t.due_date, t.status, b.judul, b.gambar
                          FROM transaksi t
                          JOIN buku b ON t.id_buku = b.id_buku
                          WHERE t.id_anggota = '$id_anggota' AND t.status = 'dipinjam'
                          ORDER BY t.id_transaksi DESC";
        $borrowedResult = mysqli_query($conn, $borrowedQuery);

        $no = 1;
        if(mysqli_num_rows($borrowedResult) > 0){
            while($row = mysqli_fetch_assoc($borrowedResult)){
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td><img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'></td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['tanggal_pinjam']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "<td>".$row['status']."</td>";
                echo "<td><a href='member_dashboard.php?action=return&id_transaksi=".$row['id_transaksi']."'>Kembalikan</a></td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7'>Anda belum meminjam buku apa pun.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
