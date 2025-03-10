<?php
// pages/pengguna.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/header.php");
include_once("../includes/config.php");
?>

<!-- Tabel Admin -->
<h3><br>Daftar Admin</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>ID Admin</th>
        <th>Username</th>
    </tr>
    <?php
    $queryAdmin = "SELECT * FROM admin";
    $resultAdmin = mysqli_query($conn, $queryAdmin);

    // Counter for admin rows
    $noAdmin = 1;

    while($rowA = mysqli_fetch_assoc($resultAdmin)){
        echo "<tr>";
        echo "<td>".$noAdmin."</td>";     // Our custom counter
        echo "<td>".$rowA['id_admin']."</td>";
        echo "<td>".$rowA['username']."</td>";
        echo "</tr>";
        $noAdmin++;
    }
    ?>
</table>

<!-- Tabel Member -->
<h3>Daftar Member</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>ID Anggota</th>
        <th>Nama</th>
        <th>Email</th>
    </tr>
    <?php
    $queryMember = "SELECT * FROM anggota";
    $resultMember = mysqli_query($conn, $queryMember);

    // Counter for member rows
    $noMember = 1;

    while($rowM = mysqli_fetch_assoc($resultMember)){
        echo "<tr>";
        echo "<td>".$noMember."</td>";     // Our custom counter
        echo "<td>".$rowM['id_anggota']."</td>";
        echo "<td>".$rowM['nama']."</td>";
        echo "<td>".$rowM['email']."</td>";
        echo "</tr>";
        $noMember++;
    }
    ?>
</table>

<?php
include_once("../includes/footer.php");
?>
