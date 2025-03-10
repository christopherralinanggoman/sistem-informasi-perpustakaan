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

// Proses peminjaman buku
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Buku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
        h2 {
            margin-top: 20px;
        }
        /* Styling for the borrow button */
        .borrow-btn {
            background-color: #007bff;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .borrow-btn:hover {
            background-color: #0056b3;
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
    <h2>Peminjaman Buku</h2>
    <p>Selamat datang, <?php echo $nama_member; ?>!</p>
    <?php if(!empty($msg)){ echo "<p style='color: green;'>$msg</p>"; } ?>

    <!-- Daftar Buku untuk Dipinjam -->
    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>ISBN</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Tampilkan semua buku
        $bookQuery = "SELECT * FROM buku";
        $bookResult = mysqli_query($conn, $bookQuery);
        // Initialize counter
        $no = 1;
        if(mysqli_num_rows($bookResult) > 0){
            while($row = mysqli_fetch_assoc($bookResult)){
                $id_buku = $row['id_buku'];
                // Cek apakah buku sedang dipinjam
                $borrowCheck = "SELECT * FROM transaksi WHERE id_buku = '$id_buku' AND status = 'dipinjam'";
                $borrowResult = mysqli_query($conn, $borrowCheck);
                $borrowed = mysqli_num_rows($borrowResult) > 0;

                echo "<tr>";
                echo "<td>".$no."</td>";  // Using our counter for sequential numbering
                echo "<td><img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'></td>";
                echo "<td>".$row['judul']."</td>";
                echo "<td>".$row['pengarang']."</td>";
                echo "<td>".$row['penerbit']."</td>";
                echo "<td>".$row['isbn']."</td>";
                echo "<td>";
                if($borrowed){
                    echo "Tidak Tersedia";
                } else {
                    // Use a button instead of a link for borrowing a book
                    echo "<button class='borrow-btn' onclick=\"location.href='peminjaman_buku.php?action=borrow&id_buku=".$id_buku."'\">Pinjam Buku</button>";
                }
                echo "</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada buku yang tersedia.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
