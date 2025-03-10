<?php
// pages/transaksi.php
include_once("../includes/header.php");
include_once("../includes/config.php");

echo "<h2>Peminjaman/Pengembalian</h2>";

// Query data including a calculation of how many days overdue (if any)
$query = "
    SELECT 
        id_transaksi, 
        id_buku, 
        id_anggota, 
        tanggal_pinjam, 
        due_date, 
        tanggal_kembali, 
        status,
        DATEDIFF(CURDATE(), due_date) AS days_late
    FROM transaksi
    ORDER BY id_transaksi DESC
";
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

while($row = mysqli_fetch_assoc($result)){
    // Calculate fine
    $days_late = $row['days_late'];
    $denda = 0;
    
    // Only apply a fine if the book is still 'dipinjam' and it's past due
    if($row['status'] === 'dipinjam' && $days_late > 0) {
        // Example: 1000 per day
        $denda = $days_late * 1000;
    }

    echo "<tr>
            <td>{$row['id_transaksi']}</td>
            <td>{$row['id_buku']}</td>
            <td>{$row['id_anggota']}</td>
            <td>{$row['tanggal_pinjam']}</td>
            <td>{$row['due_date']}</td>
            <td>{$row['tanggal_kembali']}</td>
            <td>{$row['status']}</td>
            <td>Rp. {$denda}</td>
          </tr>";
}
echo "</tbody>";
echo "</table>";

include_once("../includes/footer.php");
?>
