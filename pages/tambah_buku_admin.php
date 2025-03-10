<?php
// pages/tambah_buku_admin.php

session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

include_once("../includes/config.php");

// Initialize message variable
$msg = "";

if(isset($_POST['submit'])){
    // Sanitize input data
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $pengarang   = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $penerbit    = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit= mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $isbn        = mysqli_real_escape_string($conn, $_POST['isbn']);
    $stok        = mysqli_real_escape_string($conn, $_POST['stok']);
    $genre       = mysqli_real_escape_string($conn, $_POST['genre']);

    // Check if a file was uploaded without errors
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/"; // Folder where the image will be stored
        $file_name = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if(!in_array($imageFileType, $allowed_types)){
            $msg = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        } else {
            // Try to move the uploaded file to the target directory
            if(move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)){
                // File upload success: $file_name will be stored in database
                $gambar = $file_name;
                
                // Insert new book into database
                $insertQuery = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, gambar, stok, genre)
                                VALUES ('$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$isbn', '$gambar', '$stok', '$genre')";
                if(mysqli_query($conn, $insertQuery)){
                    $msg = "Buku berhasil ditambahkan!";
                } else {
                    $msg = "Terjadi kesalahan: " . mysqli_error($conn);
                }
            } else {
                $msg = "Gagal mengunggah gambar.";
            }
        }
    } else {
        $msg = "Gambar tidak diunggah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h2 {
            margin-top: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        form {
            max-width: 500px;
            width: 100%;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            margin: 0 auto;
        }
        form label {
            display: block;
            margin-top: 10px;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"],
        form select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            margin-top: 15px;
            padding: 10px 15px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        form input[type="submit"]:hover {
            background: #218838;
        }
        .msg {
            margin-top: 15px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include_once("../includes/header.php"); ?>

<div class="container">
    <h2>Tambah Buku</h2>
    <?php if($msg != "") { echo "<div class='msg'>$msg</div>"; } ?>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="judul">Judul Buku:</label>
        <input type="text" name="judul" id="judul" required>
        
        <label for="pengarang">Pengarang:</label>
        <input type="text" name="pengarang" id="pengarang" required>
        
        <label for="penerbit">Penerbit:</label>
        <input type="text" name="penerbit" id="penerbit" required>
        
        <label for="tahun_terbit">Tahun Terbit:</label>
        <input type="number" name="tahun_terbit" id="tahun_terbit" required>
        
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" required>
        
        <label for="stok">Stok:</label>
        <input type="number" name="stok" id="stok" value="200" required>
        
        <label for="genre">Genre:</label>
        <select name="genre" id="genre" required>
            <option value="">Pilih Genre</option>
            <option value="Edukasi">Edukasi</option>
            <option value="Sejarah">Sejarah</option>
            <option value="Teknologi">Teknologi</option>
            <option value="Kuliner">Kuliner</option>
            <option value="Fiksi">Fiksi</option>
            <option value="Psikologi">Psikologi</option>
            <option value="Ekonomi">Ekonomi</option>
        </select>
        
        <label for="gambar">Upload Gambar:</label>
        <input type="file" name="gambar" id="gambar" accept="image/*" required>

        <input type="submit" name="submit" value="Tambah Buku">
    </form>
</div>

</body>
</html>
