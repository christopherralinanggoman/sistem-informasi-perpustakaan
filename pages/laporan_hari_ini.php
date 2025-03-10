<?php
// pages/laporan_hari_ini.php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}
include_once("../includes/config.php");
include_once("../includes/header.php");

// Today's date
$tgl = date('Y-m-d');

// Query for new members (assumes 'tanggal_daftar' exists in anggota)
$queryNewMembers = "SELECT COUNT(*) AS new_members FROM anggota WHERE DATE(tanggal_daftar) = '$tgl'";
$resultNewMembers = mysqli_query($conn, $queryNewMembers);
$newMembers = mysqli_fetch_assoc($resultNewMembers)['new_members'] ?? 0;

// Query for borrowings today
$queryBorrowings = "SELECT COUNT(*) AS borrowings FROM transaksi WHERE DATE(tanggal_pinjam) = '$tgl'";
$resultBorrowings = mysqli_query($conn, $queryBorrowings);
$borrowings = mysqli_fetch_assoc($resultBorrowings)['borrowings'] ?? 0;

// Query for returns today
$queryReturns = "SELECT COUNT(*) AS returns FROM transaksi WHERE DATE(tanggal_kembali) = '$tgl' AND status = 'dikembalikan'";
$resultReturns = mysqli_query($conn, $queryReturns);
$returns = mysqli_fetch_assoc($resultReturns)['returns'] ?? 0;

// Query for due today (borrowings that are due today and still 'dipinjam')
$queryDueToday = "SELECT COUNT(*) AS due_today FROM transaksi WHERE DATE(due_date) = '$tgl' AND status = 'dipinjam'";
$resultDueToday = mysqli_query($conn, $queryDueToday);
$dueToday = mysqli_fetch_assoc($resultDueToday)['due_today'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hari Ini</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .navbar { background: #333; overflow: hidden; }
        .navbar ul { list-style: none; margin: 0; padding: 0; }
        .navbar li { float: left; }
        .navbar li a { display: block; color: #fff; padding: 14px 20px; text-decoration: none; }
        .navbar li a:hover { background: #555; }
        .navbar li.logout { float: right; }
        .navbar::after { content: ""; display: table; clear: both; }

        /* Centering Laporan Hari Ini */
        .report-container { 
            width: 80%; 
            max-width: 800px; 
            margin: 50px auto; /* Centering the box */
            background: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centering text inside */
        }

        /* Chart Container */
        .chart-container { 
            width: 100%; 
            max-width: 800px; 
            margin: 20px auto; 
        }
    </style>

</head>
<body>
    <div class="report-container">
        <h2>Laporan Hari Ini (<?php echo $tgl; ?>)</h2>
        <div class="chart-container">
            <canvas id="statChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('statChart').getContext('2d');
        const statChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Anggota Baru', 'Peminjaman', 'Pengembalian', 'Jatuh Tempo'],
                datasets: [{
                    label: 'Statistik Hari Ini',
                    data: [<?php echo $newMembers; ?>, <?php echo $borrowings; ?>, <?php echo $returns; ?>, <?php echo $dueToday; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    </script>
    
<?php include_once("../includes/footer.php"); ?>
</body>
</html>
