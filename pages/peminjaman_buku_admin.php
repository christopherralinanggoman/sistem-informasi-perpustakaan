<?php
// pages/peminjaman_buku.php (renamed or referenced as needed)
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/header.php");
include_once("../includes/config.php");
?>

<h2>Peminjaman Buku (Admin View)</h2>
<p>Menampilkan data peminjaman: siapa yang meminjam, sudah kembali, jatuh tempo, dsb.</p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Anggota</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Due Date</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
    </tr>
    <?php
    $queryPinjam = "
        SELECT t.id_transaksi, a.nama AS nama_anggota, b.judul, 
               t.tanggal_pinjam, t.due_date, t.tanggal_kembali, t.status
        FROM transaksi t
        JOIN anggota a ON t.id_anggota = a.id_anggota
        JOIN buku b ON t.id_buku = b.id_buku
        ORDER BY t.id_transaksi DESC
    ";
    $resultPinjam = mysqli_query($conn, $queryPinjam);

    // Counter for the "No" column
    $no = 1;

    while($rowP = mysqli_fetch_assoc($resultPinjam)){
        echo "<tr>";
        echo "<td>".$no."</td>";  // Use our counter instead of $rowP['id_transaksi']
        echo "<td>".$rowP['nama_anggota']."</td>";
        echo "<td>".$rowP['judul']."</td>";
        echo "<td>".$rowP['tanggal_pinjam']."</td>";
        echo "<td>".$rowP['due_date']."</td>";
        echo "<td>".($rowP['tanggal_kembali'] ? $rowP['tanggal_kembali'] : "-")."</td>";
        echo "<td>".$rowP['status']."</td>";
        echo "</tr>";

        $no++; // increment counter
    }
    ?>
</table>

<?php
include_once("../includes/footer.php");
?>
