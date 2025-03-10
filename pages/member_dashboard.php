<?php
session_start();
if(!isset($_SESSION['member_email'])){
    header("Location: ../member_login.php");
    exit;
}
include_once("../includes/config.php");

// Dapatkan data member dari session
$email = $_SESSION['member_email'];
$queryMember = "SELECT * FROM anggota WHERE email = '$email'";
$resultMember = mysqli_query($conn, $queryMember);
$member = mysqli_fetch_assoc($resultMember);
$nama_member = $member['nama'];
$id_anggota  = $member['id_anggota'];

$msg = "";

// --- Proses Peminjaman Buku (Borrow) ---
if(isset($_GET['action']) && $_GET['action'] === 'borrow' && isset($_GET['id_buku'])){
    $id_buku = $_GET['id_buku'];
    
    // Cek apakah buku sudah dipinjam
    $checkQuery = "SELECT * FROM transaksi WHERE id_buku = '$id_buku' AND status = 'dipinjam'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if(mysqli_num_rows($checkResult) > 0){
        $msg = "Buku ini sedang dipinjam.";
    } else {
        $tanggal_pinjam = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+7 days'));
        $insertQuery = "INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, due_date, status)
                        VALUES ('$id_buku', '$id_anggota', '$tanggal_pinjam', '$due_date', 'dipinjam')";
        if(mysqli_query($conn, $insertQuery)){
            $msg = "Buku berhasil dipinjam!";
        } else {
            $msg = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
}

// --- Proses Pengembalian Buku (Return) ---
if(isset($_GET['action']) && $_GET['action'] === 'return' && isset($_GET['id_transaksi'])){
    $id_transaksi = $_GET['id_transaksi'];
    $updateQuery = "UPDATE transaksi
                    SET status = 'dikembalikan', tanggal_kembali = NOW()
                    WHERE id_transaksi = '$id_transaksi'
                      AND id_anggota = '$id_anggota'";
    if(mysqli_query($conn, $updateQuery)){
        $msg = "Buku berhasil dikembalikan!";
    } else {
        $msg = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard</title>
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
        }
        table th {
            background: #0052d4;
            color: #fff;
        }
        h2, h3 {
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
        <li class="logout"><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<div style="margin: 20px;">
    <h2>Member Dashboard</h2>
    <p>Selamat datang, <?php echo $nama_member; ?>!</p>
    <?php if(!empty($msg)){ echo "<p style='color: green;'>$msg</p>"; } ?>

    <!-- Section 1: Books Currently Borrowed -->
    <h3>Buku yang Sedang Anda Pinjam</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $borrowedQuery = "SELECT t.id_transaksi, t.tanggal_pinjam, t.due_date, t.status, b.judul
                          FROM transaksi t
                          JOIN buku b ON t.id_buku = b.id_buku
                          WHERE t.id_anggota = '$id_anggota'
                            AND t.status = 'dipinjam'
                          ORDER BY t.id_transaksi DESC";
        $borrowedResult = mysqli_query($conn, $borrowedQuery);

        // Counter variable for the "No" column
        $no = 1;

        if(mysqli_num_rows($borrowedResult) > 0){
            while($row = mysqli_fetch_assoc($borrowedResult)){
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['tanggal_pinjam']."</td>";
                echo "<td>".$row['due_date']."</td>";
                echo "<td>".$row['status']."</td>";
                echo "<td><a href='member_dashboard.php?action=return&id_transaksi=".$row['id_transaksi']."'>Kembalikan</a></td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6'>Anda belum meminjam buku apa pun.</td></tr>";
        }
        ?>
    </table>

    <!-- Section 2: Available Books to Borrow -->
    <h3>Daftar Buku untuk Dipinjam</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Pinjam/Kembalikan</th>
        </tr>
        <?php
        // List available books: those not currently borrowed
        $queryAvailable = "SELECT * FROM buku 
                           WHERE id_buku NOT IN (SELECT id_buku FROM transaksi WHERE status = 'dipinjam')";
        $resultAvailable = mysqli_query($conn, $queryAvailable);
        
        $noAvail = 1;
        if(mysqli_num_rows($resultAvailable) > 0){
            while($row = mysqli_fetch_assoc($resultAvailable)){
                echo "<tr>";
                echo "<td>".$noAvail."</td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['pengarang']."</td>";
                echo "<td>".$row['penerbit']."</td>";
                echo "<td><a href='member_dashboard.php?action=borrow&id_buku=".$row['id_buku']."'>Pinjam</a></td>";
                echo "</tr>";
                $noAvail++;
            }
        } else {
            echo "<tr><td colspan='5'>Semua buku telah dipinjam.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
