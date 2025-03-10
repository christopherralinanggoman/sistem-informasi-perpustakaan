<?php
// pages/admin_dashboard.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/header.php");
include_once("../includes/config.php");

// Contoh: Ambil data summary dari database (silakan sesuaikan dengan tabel Anda)
$queryTotalBuku     = "SELECT COUNT(*) AS total_buku FROM buku";
$queryTotalAnggota  = "SELECT COUNT(*) AS total_anggota FROM anggota";
$queryTotalKategori = "SELECT COUNT(DISTINCT genre) AS total_kategori FROM buku";
$queryTotalPinjam   = "SELECT COUNT(*) AS total_peminjaman FROM transaksi WHERE status = 'dipinjam'";
$queryTotalKembali  = "SELECT COUNT(*) AS total_pengembalian FROM transaksi WHERE status = 'dikembalikan'";
// Misal: Tabel visitors untuk mencatat pengunjung perpustakaan
$queryTotalVisitors = "SELECT COUNT(*) AS total_pengunjung FROM visitors"; // opsional

$resBuku     = mysqli_query($conn, $queryTotalBuku);
$resAnggota  = mysqli_query($conn, $queryTotalAnggota);
$resKategori = mysqli_query($conn, $queryTotalKategori);
$resPinjam   = mysqli_query($conn, $queryTotalPinjam);
$resKembali  = mysqli_query($conn, $queryTotalKembali);
$resVisitors = mysqli_query($conn, $queryTotalVisitors); // opsional

$totalBuku     = mysqli_fetch_assoc($resBuku)['total_buku'] ?? 0;
$totalAnggota  = mysqli_fetch_assoc($resAnggota)['total_anggota'] ?? 0;
$totalKategori = mysqli_fetch_assoc($resKategori)['total_kategori'] ?? 0;
$totalPinjam   = mysqli_fetch_assoc($resPinjam)['total_peminjaman'] ?? 0;
$totalKembali  = mysqli_fetch_assoc($resKembali)['total_pengembalian'] ?? 0;
$totalVisitors = mysqli_fetch_assoc($resVisitors)['total_pengunjung'] ?? 0; // opsional

// Contoh: Ambil data bulanan (misal untuk chart) - silakan sesuaikan logika
// Misalnya, kita menampilkan data jumlah peminjaman per bulan (6 bulan terakhir)
$chartLabels = [];
$chartData   = [];

for($i=5; $i>=0; $i--){
    // Misal: $month = bulan - i
    $monthNum = date('m', strtotime("-$i month"));
    $yearNum  = date('Y', strtotime("-$i month"));
    $label    = date('M Y', strtotime("-$i month")); // e.g. "Mar 2025"
    
    // Query contoh: jumlah peminjaman di bulan ini
    $qry = "SELECT COUNT(*) AS jml 
            FROM transaksi
            WHERE status = 'dipinjam'
              AND MONTH(tanggal_pinjam) = '$monthNum'
              AND YEAR(tanggal_pinjam)  = '$yearNum'";
    $r = mysqli_query($conn, $qry);
    $count = mysqli_fetch_assoc($r)['jml'] ?? 0;
    
    $chartLabels[] = $label;
    $chartData[]   = $count;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Tambahkan Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0; 
            font-family: Arial, sans-serif;
        }

        /* Centering summary-container */
        .summary-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center; /* Centers the boxes horizontally */
            align-items: center; /* Aligns them vertically */
            margin: 50px auto; /* Centers the container */
            max-width: 800px; /* Prevents it from being too wide */
        }

        /* Summary Box Styling */
        .summary-box {
            background: #1e1e2f;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 200px;
            text-align: center;
        }

        /* Chart Container */
        .chart-container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
        }
    </style>

</head>
<body>

<!-- Sudah ada di header.php:
     - Navbar
     - session checks
-->

<div style="margin: 20px; text-align: center;">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang di Sistem Informasi Perpustakaan, Admin!</p>

    <!-- Contoh: Ringkasan Statistik -->
    <div class="summary-container">
        <div class="summary-box">
            <h3><?php echo $totalBuku; ?></h3>
            <p>Total Buku</p>
        </div>
        <div class="summary-box">
            <h3><?php echo $totalAnggota; ?></h3>
            <p>Total Anggota</p>
        </div>
        <div class="summary-box">
            <h3><?php echo $totalKategori; ?></h3>
            <p>Kategori (Genre)</p>
        </div>
        <div class="summary-box">
            <h3><?php echo $totalPinjam; ?></h3>
            <p>Dipinjam</p>
        </div>
        <div class="summary-box">
            <h3><?php echo $totalKembali; ?></h3>
            <p>Dikembalikan</p>
        </div>
        <!-- Jika ada data pengunjung -->
        <div class="summary-box">
            <h3><?php echo $totalVisitors; ?></h3>
            <p>Pengunjung</p>
        </div>
    </div>
    
    <!-- Contoh: Chart.js -->
    <div class="chart-container">
        <h3>Statistik Peminjaman 6 Bulan Terakhir</h3>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
  // Ambil data dari PHP
  const labels = <?php echo json_encode($chartLabels); ?>; 
  const dataValues = <?php echo json_encode($chartData); ?>;
  
  const data = {
    labels: labels,
    datasets: [{
      label: 'Jumlah Peminjaman',
      data: dataValues,
      backgroundColor: 'rgba(54, 162, 235, 0.6)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  };
  
  const config = {
    type: 'bar', // atau 'line', 'pie', dsb.
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  };

  // Render chart
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>

<?php
include_once("../includes/footer.php");
?>
</body>
</html>
