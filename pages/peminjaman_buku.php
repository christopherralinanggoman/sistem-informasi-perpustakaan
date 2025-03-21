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
    
    // Ambil stok buku dari tabel buku
    $stokQuery = "SELECT stok FROM buku WHERE id_buku = '$id_buku'";
    $stokResult = mysqli_query($conn, $stokQuery);
    $stokRow = mysqli_fetch_assoc($stokResult);
    $stok = $stokRow['stok'];
    
    if($stok <= 0){
        $msg = "Buku ini sudah habis (stok 0).";
    } else {
        // Batas peminjaman 3 hari
        $tanggal_pinjam = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+3 days'));
        
        $insertQuery = "INSERT INTO transaksi (id_buku, id_anggota, tanggal_pinjam, due_date, status)
                        VALUES ('$id_buku', '$id_anggota', '$tanggal_pinjam', '$due_date', 'dipinjam')";
        if(mysqli_query($conn, $insertQuery)){
            // Update stok: kurangi 1
            $updateStok = "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'";
            mysqli_query($conn, $updateStok);
            $msg = "Buku berhasil dipinjam!";
        } else {
            $msg = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
}

// --- Genre Filter & Search ---
$filterGenre = "";
if(isset($_GET['genre']) && $_GET['genre'] != ""){
    $filterGenre = mysqli_real_escape_string($conn, $_GET['genre']);
}

$searchTerm = "";
if(isset($_GET['search']) && $_GET['search'] != ""){
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
}

// Build query with optional filters
$conditions = array();
if($filterGenre != ""){
    $conditions[] = "genre = '$filterGenre'";
}
if($searchTerm != ""){
    $conditions[] = "judul LIKE '%$searchTerm%'";
}

$bookQuery = "SELECT * FROM buku";
if(count($conditions) > 0){
    $bookQuery .= " WHERE " . implode(" AND ", $conditions);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Buku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { background: #333; overflow: hidden; }
        .navbar ul { list-style: none; margin: 0; padding: 0; }
        .navbar li { float: left; }
        .navbar li a { display: block; color: #fff; padding: 14px 20px; text-decoration: none; }
        .navbar li a:hover { background: #555; }
        .navbar li.logout { float: right; }
        .navbar::after { content: ''; display: table; clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #999; padding: 8px; text-align: center; }
        table th { background: #0052d4; color: #fff; }
        h2 { margin-top: 20px; }
        .borrow-btn { background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
        .borrow-btn:hover { background-color: #0056b3; }
        .filter-form { margin-bottom: 15px; }
        .filter-form select, .filter-form input[type="text"] { padding: 5px; font-size: 1em; }
        .filter-form input[type="submit"] { padding: 5px 10px; font-size: 1em; background: #0052d4; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .filter-form input[type="submit"]:hover { background: #003a7e; }
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
    <h2>Peminjaman Buku</h2>
    <?php if(!empty($msg)){ echo "<p style='color: green;'>$msg</p>"; } ?>
    </div>
    <!-- Filter Form by Genre and Search -->
    <form class="filter-form" method="GET" action="peminjaman_buku.php">
    <label for="genre">Filter berdasarkan Genre:</label>
    <select name="genre" id="genre">
        <option value="">Semua Genre</option>
        <option value="Sejarah" <?php if($filterGenre=="Sejarah") echo "selected"; ?>>Sejarah</option>
        <option value="Teknologi" <?php if($filterGenre=="Teknologi") echo "selected"; ?>>Teknologi</option>
        <option value="Kuliner" <?php if($filterGenre=="Kuliner") echo "selected"; ?>>Kuliner</option>
        <option value="Fiksi" <?php if($filterGenre=="Fiksi") echo "selected"; ?>>Fiksi</option>
        <option value="Psikologi" <?php if($filterGenre=="Psikologi") echo "selected"; ?>>Psikologi</option>
        <option value="Ekonomi" <?php if($filterGenre=="Ekonomi") echo "selected"; ?>>Ekonomi</option>
        <!-- Tambahkan genre lain sesuai kebutuhan -->
    </select>

    <br><br> <!-- Line break added here -->

    <label for="search">Cari Judul Buku:</label>
    <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <input type="submit" value="Cari">
    </form>

    <!-- Daftar Buku untuk Dipinjam -->
    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Judul Buku</th>
            <th>Genre</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>ISBN</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $bookResult = mysqli_query($conn, $bookQuery);
        $no = 1;
        if(mysqli_num_rows($bookResult) > 0){
            while($row = mysqli_fetch_assoc($bookResult)){
                $id_buku   = $row['id_buku'];
                $stokBuku  = $row['stok'];
                $gambar    = $row['gambar'];
                $judul     = $row['judul'];
                $genre     = $row['genre'];
                $pengarang = $row['pengarang'];
                $penerbit  = $row['penerbit'];
                $isbn      = $row['isbn'];
                
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td><img src='../assets/images/books/".$gambar."' alt='Gambar Buku' width='80'></td>";
                echo "<td>".$judul."</td>";
                echo "<td>".$genre."</td>";
                echo "<td>".$pengarang."</td>";
                echo "<td>".$penerbit."</td>";
                echo "<td>".$isbn."</td>";
                echo "<td>".$stokBuku."</td>";
                echo "<td>";
                if($stokBuku > 0){
                    echo "<button class='borrow-btn' onclick=\"location.href='peminjaman_buku.php?action=borrow&id_buku=".$id_buku."'\">Pinjam Buku</button>";
                } else {
                    echo "Tidak Tersedia";
                }
                echo "</td>";
                echo "</tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='9'>Tidak ada buku yang tersedia.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
