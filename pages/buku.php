<?php
// pages/buku.php
include_once("../includes/header.php");
include_once("../includes/config.php");
?>

<h2>Data Buku</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID Buku</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun Terbit</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM buku";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_buku']}</td>
                    <td>{$row['judul']}</td>
                    <td>{$row['pengarang']}</td>
                    <td>{$row['penerbit']}</td>
                    <td>{$row['tahun_terbit']}</td>
                    <td>{$row['isbn']}</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<?php
include_once("../includes/footer.php");
?>
