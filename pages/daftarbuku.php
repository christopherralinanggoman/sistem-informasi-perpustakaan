<?php
// pages/daftarbuku.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/header.php");
include_once("../includes/config.php");
?>


<div style="text-align: center; margin: 20px;">
    <h2>Daftar Buku</h2>
</div>


<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Pengarang</th>
        <th>Penerbit</th>
        <th>Tahun Terbit</th>
        <th>ISBN</th>
        <th>Gambar</th>
    </tr>
    <?php
    $query = "SELECT * FROM buku";
    $result = mysqli_query($conn, $query);

    // Counter for the "No" column
    $no = 1;

    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$no."</td>";                 // Our custom counter
        echo "<td>".$row['judul']."</td>";
        echo "<td>".$row['pengarang']."</td>";
        echo "<td>".$row['penerbit']."</td>";
        echo "<td>".$row['tahun_terbit']."</td>";
        echo "<td>".$row['isbn']."</td>";
        echo "<td>";
        if(!empty($row['gambar'])){
            echo "<img src='../assets/images/books/".$row['gambar']."' alt='Gambar Buku' width='80'>";
        } else {
            echo "No Image";
        }
        echo "</td>";
        echo "</tr>";

        $no++; // increment
    }
    ?>
</table>

<?php
include_once("../includes/footer.php");
?>
