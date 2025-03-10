<?php
include '../config/koneksi.php'; // Pastikan file koneksi database ada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $isbn = $_POST['isbn'];
    $stok = $_POST['stok'];
    $genre = $_POST['genre'];

    // Cek apakah file gambar diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../uploads/"; // Folder penyimpanan gambar
        $file_name = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file gambar
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            die("Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.");
        }

        // Simpan gambar ke folder uploads/
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $file_name;
        } else {
            die("Gagal mengunggah gambar.");
        }
    } else {
        die("Gambar tidak diunggah.");
    }

    // Simpan data ke database
    $query = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, gambar, stok, genre) 
              VALUES ('$judul', '$pengarang', '$penerbit', '$tahun_terbit', '$isbn', '$gambar', '$stok', '$genre')";

    if (mysqli_query($conn, $query)) {
        echo "Buku berhasil ditambahkan!";
        header("Location: daftarbuku.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
