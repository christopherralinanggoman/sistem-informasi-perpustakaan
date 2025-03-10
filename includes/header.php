<?php
// includes/header.php

// Only start a session if one doesn't already exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Basic styling for the navbar */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: #333;
            overflow: hidden;
        }
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .navbar li {
            float: left;
        }
        .navbar li a {
            display: block;
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar li a:hover {
            background: #555;
        }
        .navbar li.logout {
            float: right;
        }
        .navbar::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
<div class="navbar">
    <ul>
        <!-- Admin Menu Bar -->
        <li><a href="../pages/admin_dashboard.php">Statistik</a></li> <!-- Tambahan Menu -->
        <li><a href="../pages/daftarbuku.php">Daftar Buku</a></li>
        <li><a href="../pages/pengguna.php">Pengguna</a></li>
        <li><a href="../pages/peminjaman_buku_admin.php">Peminjaman Buku</a></li>
        <li><a href="../pages/tambah_buku_admin.php">Tambah Buku</a></li> <!-- Tambahan Menu -->
        <li class="logout"><a href="../logout.php">Logout</a></li>
    </ul>
</div>
