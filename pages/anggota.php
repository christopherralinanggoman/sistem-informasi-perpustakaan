<?php
// pages/anggota.php
include_once("../includes/header.php");
include_once("../includes/config.php");
?>

<h2>Data Anggota</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID Anggota</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>No. Telepon</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM anggota";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_anggota']}</td>
                    <td>{$row['nama']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['alamat']}</td>
                    <td>{$row['no_telp']}</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<?php
include_once("../includes/footer.php");
?>
