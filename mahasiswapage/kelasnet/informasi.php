<?php
    require "../../sesion.php";
    require "../../koneksi.php";

$customTimestamp = strtotime('2025-01-14 15:00:00'); // Waktu spesifik
$customTime = date('H:i:s', $customTimestamp); // Formatkan waktu
$customDay = date('l', $customTimestamp); // Formatkan hari



    $sql = "SELECT r.id_ruangan, r.nama_ruangan, r.lokasi, 
           m.nama_mata_kuliah, m.semester, d.nama AS nama_dosen, 
           k.jadwal_mulai, k.jadwal_selesai, k.nomor_kelas,
           lp.alasan AS alasan_pindah, lp.waktu_pindah AS waktu_pindah, lp.jadwal_selesai_baru
    FROM Ruangan r
    LEFT JOIN Kelas k 
        ON r.id_ruangan = k.id_ruangan 
        AND ('$customTime' BETWEEN k.jadwal_mulai AND k.jadwal_selesai)
        AND (' $currentDay' = k.hari)
    LEFT JOIN Mata_Kuliah m 
        ON k.id_mata_kuliah = m.id_mata_kuliah
    LEFT JOIN Dosen d 
        ON k.id_dosen = d.id_dosen 
    LEFT JOIN Log_Pindah_Ruangan lp
        ON lp.id_ruangan_baru = r.id_ruangan
        AND lp.jadwal_selesai_baru > '$customTimestamp'
    ORDER BY r.nama_ruangan";


    $query = mysqli_query($con, $sql);

    if (!$query) {
        echo json_encode([
            'success' => false,
            'message' => "Query gagal dijalankan: " . mysqli_error($con)
        ]);
        exit; 
    }

    $id_mahasiswa = $_SESSION['id_mahasiswa'];
$dataResult = mysqli_query($con, "SELECT nama_mahasiswa, nim, jurusan FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'");

if (!$dataResult) {
    error_log("Query mahasiswa gagal: " . mysqli_error($con));
    die("Error mendapatkan data mahasiswa.");
}

$dataM = mysqli_fetch_array($dataResult);
mysqli_close($con);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Ruangan</title>
    <link rel="stylesheet" href="../css/informasi.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="header">
      <div class="header-box">
        <div class="p-name">
          <h2><?= htmlspecialchars($dataM['nama_mahasiswa']); ?></h2>
        </div>
        <div>
          <h1>SIAM</h1>
        </div>
        <div class="logout">
          <i class="fa-solid fa-right-from-bracket"></i>
          <a class="logout" href="../logout.php">LOGOUT</a>
        </div>
      </div>
    </div>

    <div class="container">
        <div class="sidebar" id="sidebar">
            <div class="item">
                <i class="fa-solid fa-house"></i>
                <a href="../dashboard.php">Dashboard</a>
            </div>
            <div class="item">
                <i class="fa-solid fa-graduation-cap"></i>
                <a href="../krs/krs.php">Rencana Studi (KRS)</a>
            </div>
            <div class="item">
                <i class="fa-solid fa-table-list"></i>
                <a href="../krs/jadwal-kelas.php">Jadwal Kuliah</a>
            </div>
            <div class="item-4">
                <div class="menu-item" onclick="toggleDropdown()">
                    <div class="t-sub">
                        <img src="../../image/kn.png" alt="" />
                        <a href="informasi.php">KelasNet</a>
                    </div>
                    <div class="panah-sub">
                        <span class="arrow"><i class="fa-solid fa-caret-down"></i></span>
                    </div>
                </div>
                <div class=" dropdown" id="dropdown">
                    <a href="ks.php">filter</a>
                </div>
            </div>
        </div>

    
        <div class="content" id="content">
            <div class="filter-container">
                <h2>pilih gedung</h2>
                <a class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'semua') || !isset($_GET['page']) ? 'active' : ''; ?>" href="?page=semua">semua</a>
                <a class="<?php echo isset($_GET['page']) && $_GET['page'] === 'ra' ? 'active' : ''; ?>" href="?page=ra">RA</a>
                <a class="<?php echo isset($_GET['page']) && $_GET['page'] === 'rb' ? 'active' : ''; ?>" href="?page=rb">RB</a>
                <a class="<?php echo isset($_GET['page']) && $_GET['page'] === 'gr' ? 'active' : ''; ?>" href="?page=gr">GR</a>
                <a class="<?php echo isset($_GET['page']) && $_GET['page'] === 'gtc' ? 'active' : ''; ?>" href="?page=gtc">GTC</a>
            </div>
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'semua';
            switch ($page) {
                case 'semua':
                    require "informasi-semua.php";
                    break;
                case 'ra':
                    require "informasi-ra.php";
                    break;
                case 'rb':
                    require "informasi-rb.php";
                    break;
                case 'gr':
                    require "informasi-gr.php";
                    break;
                case 'gtc':
                    require "informasi-gtc.php";
                    break;
                default:
                    echo "<p>Halaman tidak ditemukan.</p>";
                    break;
            }
            ?>
        </div>
    </div>

    <script>
    const prototypeadd = document.querySelector(".filter-button");
        prototypeadd.onclick = () => {
        prototypeadd.classList.toggle("active");
    };
    </script>
</body>
</html>
