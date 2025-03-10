<?php
session_start();
if (!isset($_SESSION['member_email'])) {
    header("Location: ../member_login.php");
    exit;
}
include_once("../includes/config.php");

// Ambil data member berdasarkan email dari session
$email = $_SESSION['member_email'];
$queryMember = "SELECT * FROM anggota WHERE email = '$email'";
$resultMember = mysqli_query($conn, $queryMember);
$member = mysqli_fetch_assoc($resultMember);
$id_anggota = $member['id_anggota'];
$nama_member = $member['nama'];

$msg = "";

// Proses peminjaman buku ketika member memilih buku dari daftar
if (isset($_GET['action']) && $_GET['action'] === 'borrow' && isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];

    // Cek apakah buku sudah dipinjam (status 'dipinjam')
    $checkQuery = "SELECT * FROM transaksi WHERE id_buku = '$id_buku' AND status = 'dipinjam'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $msg = "Buku ini sedang dipinjam.";
    } else {
        $tanggal_pinjam = date('Y-m-d');
        // Misalnya jatuh tempo 7 hari kemudian
        $due_date = date('Y-m-d', strtotime('+7 days'));
        $insertQuery = "INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, due_date, status)
                        VALUES ('$id_buku', '$id_anggota', '$tanggal_pinjam', '$due_date', 'dipinjam')";
        if (mysqli_query($conn, $insertQuery)) {
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
    <title>Pilih Buku untuk Dipinjam</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Contoh styling sederhana untuk daftar buku */
        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .book-item {
            border: 1px solid #ddd;
            padding: 10px;
            width: 200px;
            text-align: center;
            border-radius: 4px;
            background: #fff;
        }
        .book-item img {
            max-width: 100%;
            height: auto;
        }
        .borrow-btn,
        .delete-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .borrow-btn {
            background-color: #007bff;
        }
        .delete-btn {
            background-color: #dc3545;
            margin-left: 5px;
        }
    </style>
</head>
<body>
<div style="margin: 20px;">
    <h2>Pilih Buku untuk Dipinjam</h2>
    <p>Selamat datang, <?php echo $nama_member; ?>!</p>
    <?php if (!empty($msg)) { echo "<p style='color: green;'>$msg</p>"; } ?>

    <!-- Daftar Buku dengan Gambar dan Tombol Pinjam serta Delete -->
    <div class="book-list">
        <?php
        $bookQuery = "SELECT * FROM buku";
        $bookResult = mysqli_query($conn, $bookQuery);
        while ($row = mysqli_fetch_assoc($bookResult)) {
            $id_buku = $row['id_buku'];
            // Cek ketersediaan buku
            $borrowCheck = "SELECT * FROM transaksi WHERE id_buku = '$id_buku' AND status = 'dipinjam'";
            $borrowResult = mysqli_query($conn, $borrowCheck);
            $borrowed = mysqli_num_rows($borrowResult) > 0;
            ?>
            <div class="book-item">
                <p><strong><?php echo $row['judul']; ?></strong></p>
                <!-- Gambar buku diambil dari folder /assets/images/books/ -->
                <img src="../assets/images/books/<?php echo $row['gambar']; ?>" alt="Gambar <?php echo $row['judul']; ?>">
                <p><?php echo $row['pengarang']; ?></p>
                <p><?php echo $row['penerbit']; ?></p>
                <?php if ($borrowed) { ?>
                    <p style="color: red;">Tidak Tersedia</p>
                <?php } else { ?>
                    <a class="borrow-btn" href="tambah_buku.php?action=borrow&id_buku=<?php echo $id_buku; ?>">Pinjam</a>
                    <!-- Delete button -->
                    <a class="delete-btn" href="delete_buku.php?id_buku=<?php echo $id_buku; ?>" onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?');">Kembalikan</a>
                <?php } ?>
            </div>
            <?php
        }
        ?>
    </div>
    <br>
    <p><a href="member_dashboard.php">Kembali ke Dashboard</a></p>
</div>
</body>
</html>
