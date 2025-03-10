<?php
// pages/tambah_anggota.php
include_once("../includes/header.php");
include_once("../includes/config.php");

if(isset($_POST['submit'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    // Gunakan password_hash untuk keamanan
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    $query = "INSERT INTO anggota (nama, email, password, alamat, no_telp) 
              VALUES ('$nama', '$email', '$password_hashed', '$alamat', '$no_telp')";
    if(mysqli_query($conn, $query)) {
        echo "<p>Anggota berhasil ditambahkan!</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<h2>Tambah Anggota</h2>
<form method="post" action="">
    <label for="nama">Nama:</label><br>
    <input type="text" name="nama" id="nama" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <label for="alamat">Alamat:</label><br>
    <textarea name="alamat" id="alamat" rows="4" required></textarea><br><br>

    <label for="no_telp">No. Telepon:</label><br>
    <input type="text" name="no_telp" id="no_telp" required><br><br>

    <input type="submit" name="submit" value="Tambah Anggota">
</form>

<?php
include_once("../includes/footer.php");
?>
