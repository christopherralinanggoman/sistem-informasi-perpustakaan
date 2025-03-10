<?php
// index.php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
} else {
    header("Location: pages/admin_dashboard.php");
    exit;
}
?>
