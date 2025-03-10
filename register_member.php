<?php
// register_member.php
session_start();
include_once("includes/config.php");

$success = "";
$error   = "";

if(isset($_POST['register'])){
    // Sanitize and trim user input
    $nama     = mysqli_real_escape_string($conn, strip_tags(trim($_POST['nama'])));
    $email    = mysqli_real_escape_string($conn, strip_tags(trim($_POST['email'])));
    $password = mysqli_real_escape_string($conn, strip_tags(trim($_POST['password'])));
    // Use password_hash for security
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $alamat   = mysqli_real_escape_string($conn, strip_tags(trim($_POST['alamat'])));
    $no_telp  = mysqli_real_escape_string($conn, strip_tags(trim($_POST['no_telp'])));
    
    $query = "INSERT INTO anggota (nama, email, password, alamat, no_telp) 
              VALUES ('$nama', '$email', '$password_hashed', '$alamat', '$no_telp')";
    if(mysqli_query($conn, $query)) {
        // Show success message on the same page
        $success = "Registrasi berhasil! Silakan <a href='login.php'>login di sini</a>.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Member - Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Registrasi Member</h2>
        <!-- Display success or error messages safely -->
        <?php if(!empty($error)) { ?>
            <p style='color:red;'><?php echo htmlspecialchars($error); ?></p>
        <?php } elseif(!empty($success)) { ?>
            <p style='color:green;'><?php echo $success; ?></p>
        <?php } ?>
        
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

            <input type="submit" name="register" value="Daftar">
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
