<?php
// qr-code-generator.php
include_once("includes/header.php");

if(isset($_POST['submit'])){
    require_once("assets/phpqrcode/qrlib.php"); // Pastikan path sudah benar
    $text = $_POST['text'];
    $file = "assets/images/qrcode.png";
    // Generate QR Code dengan tingkat error 'L', ukuran 4, margin 2
    QRcode::png($text, $file, 'L', 4, 2);
    echo "<p>QR Code berhasil dibuat:</p>";
    echo "<img src='assets/images/qrcode.png' alt='QR Code'>";
}
?>

<h2>Generator QR Code</h2>
<form method="post" action="">
    <label for="text">Masukkan Teks/Link:</label><br>
    <input type="text" id="text" name="text" required><br><br>
    <input type="submit" name="submit" value="Generate QR Code">
</form>

<?php
include_once("includes/footer.php");
?>
