<?php
// pages/laporan.php
include_once("../includes/header.php");
include_once("../includes/config.php");

// Hitung data ringkasan
$totalBooksQuery = "SELECT COUNT(*) as total FROM buku";
$totalBooksResult = mysqli_query($conn, $totalBooksQuery);
$totalBooks = mysqli_fetch_assoc($totalBooksResult)['total'];

$totalMembersQuery = "SELECT COUNT(*) as total FROM anggota";
$totalMembersResult = mysqli_query($conn, $totalMembersQuery);
$totalMembers = mysqli_fetch_assoc($totalMembersResult)['total'];

$totalTransactionsQuery = "SELECT COUNT(*) as total FROM transaksi";
$totalTransactionsResult = mysqli_query($conn, $totalTransactionsQuery);
$totalTransactions = mysqli_fetch_assoc($totalTransactionsResult)['total'];

$totalOverdueQuery = "SELECT COUNT(*) as total FROM transaksi WHERE status='dipinjam' AND CURDATE() > due_date";
$totalOverdueResult = mysqli_query($conn, $totalOverdueQuery);
$totalOverdue = mysqli_fetch_assoc($totalOverdueResult)['total'];

echo "<h2>Laporan Sistem Perpustakaan</h2>";
echo "<p>Total Buku: $totalBooks</p>";
echo "<p>Total Anggota: $totalMembers</p>";
echo "<p>Total Transaksi: $totalTransactions</p>";
echo "<p>Transaksi Terlambat: $totalOverdue</p>";

// Tampilkan daftar transaksi beserta denda
$query = "SELECT t.id_transaksi, b.judul, a.nama, t.tanggal_pinjam, t.due_date, t.tanggal_kembali, t.status,
          DATEDIFF(CURDATE(), t.due_date) AS days_late
          FROM transaksi t
          LEFT JOIN buku b ON t.id_buku = b.id_buku
          LEFT JOIN anggota a ON t.id_anggota = a.id_anggota
          ORDER BY t.id_transaksi DESC";
$result = mysqli_query($conn, $query);

echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<thead>
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>ID Anggota</th>
            <th>Tanggal Pinjam</th>
            <th>Due Date</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
      </thead>";
echo "<tbody>";

while($row = mysqli_fetch_assoc($result)) {
    $denda = 0;
    if($row['status'] === 'dipinjam' && $row['days_late'] > 0) {
        $denda = $row['days_late'] * 1000;
    }
    echo "<tr>
        <td>{$row['id_transaksi']}</td>
        <td>{$row['judul']}</td>
        <td>{$row['nama']}</td>
        <td>{$row['tanggal_pinjam']}</td>
        <td>{$row['due_date']}</td>
        <td>{$row['tanggal_kembali']}</td>
        <td>{$row['status']}</td>
        <td>Rp. {$denda}</td>
      </tr>";
}
echo "</tbody></table>";

include_once("../includes/footer.php");
?>
