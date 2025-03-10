<?php
// login.php
session_start();
include_once("includes/config.php");

// Proses login Admin
if(isset($_POST['login_admin'])){
    $username = mysqli_real_escape_string($conn, $_POST['admin_username']);
    $password = mysqli_real_escape_string($conn, $_POST['admin_password']);
    
    // Hash with MD5 (example only; use stronger hashing in production)
    $password_hashed = md5($password);
    
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password_hashed'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
         // Set session for admin
         $_SESSION['username'] = $username;
         $_SESSION['role'] = 'admin';
         
         // Redirect to your admin dashboard
         // Adjust the file path as needed
         header("Location: pages/admin_dashboard.php");
         exit;
    } else {
         $admin_error = "Username atau password admin salah!";
    }
}

// Proses login Member
if(isset($_POST['login_member'])){
    $email = mysqli_real_escape_string($conn, $_POST['member_email']);
    $password = mysqli_real_escape_string($conn, $_POST['member_password']);
    
    $query = "SELECT * FROM anggota WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
         $row = mysqli_fetch_assoc($result);
         // Use password_verify() if member passwords are hashed with password_hash()
         if(password_verify($password, $row['password'])){
             $_SESSION['member_email'] = $email;
             $_SESSION['role'] = 'member';
             
             // Redirect to your member dashboard
             header("Location: pages/member_dashboard.php");
             exit;
         } else {
             $member_error = "Password member salah!";
         }
    } else {
         $member_error = "Email member tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Styling sederhana untuk tab */
        .tab { cursor: pointer; padding: 10px 20px; display: inline-block; background: #ccc; }
        .tab.active { background: #0052d4; color: #fff; }
        .tab-content { display: none; margin-top: 20px; }
        .tab-content.active { display: block; }
    </style>
    <script>
        function showTab(tabName) {
            var tabs = document.getElementsByClassName('tab-content');
            for(var i=0; i<tabs.length; i++){
                tabs[i].classList.remove('active');
            }
            document.getElementById(tabName).classList.add('active');

            var tabButtons = document.getElementsByClassName('tab');
            for(var i=0; i<tabButtons.length; i++){
                tabButtons[i].classList.remove('active');
            }
            document.getElementById(tabName + '-tab').classList.add('active');
        }
        window.onload = function() {
            showTab('admin'); // Tab default: Admin
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login - Perpustakaan Digital</h2>
        <div>
            <span id="admin-tab" class="tab" onclick="showTab('admin')">Admin</span>
            <span id="member-tab" class="tab" onclick="showTab('member')">Member</span>
        </div>
        
        <!-- Tab untuk Login Admin -->
        <div id="admin" class="tab-content">
            <?php if(isset($admin_error)) { echo "<p style='color:red;'>$admin_error</p>"; } ?>
            <form method="post" action="">
                <label for="admin_username">Username:</label><br>
                <input type="text" name="admin_username" id="admin_username" required><br><br>
                <label for="admin_password">Password:</label><br>
                <input type="password" name="admin_password" id="admin_password" required><br><br>
                <input type="submit" name="login_admin" value="Login sebagai Admin">
            </form>
        </div>
        
        <!-- Tab untuk Login Member -->
        <div id="member" class="tab-content">
            <?php if(isset($member_error)) { echo "<p style='color:red;'>$member_error</p>"; } ?>
            <form method="post" action="">
                <label for="member_email">Email:</label><br>
                <input type="email" name="member_email" id="member_email" required><br><br>
                <label for="member_password">Password:</label><br>
                <input type="password" name="member_password" id="member_password" required><br><br>
                <input type="submit" name="login_member" value="Login sebagai Member">
            </form>
            <p>Belum punya akun? <a href="register_member.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>
