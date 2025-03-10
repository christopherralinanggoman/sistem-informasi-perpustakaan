<?php
// member_login.php
session_start();
include_once("includes/config.php");

$error = "";

if(isset($_POST['login'])){
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Optional: extra check if fields are empty
    if(empty($email) || empty($password)){
        $error = "Email dan password harus diisi!";
    } else {
        // Check if the email exists
        $query  = "SELECT * FROM anggota WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            // Verify the hashed password
            if(password_verify($password, $row['password'])){
                $_SESSION['member_email'] = $email;
                header("Location: pages/member_dashboard.php");
                exit;
            } else {
                $error = "Password member salah!";
            }
        } else {
            $error = "Email member tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member - Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Member</h2>
        
        <!-- Tampilkan pesan error jika ada -->
        <?php if(!empty($error)) { ?>
            <p style='color:red;'><?php echo $error; ?></p>
        <?php } ?>
        
        <form method="post" action="">
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>
