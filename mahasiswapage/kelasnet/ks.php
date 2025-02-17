<?php
require "../../sesion.php";
require "../../koneksi.php";

if (!isset($_SESSION['id_mahasiswa'])) {
    header("Location: login.php");
    exit;
}

$tanggal = !empty($_GET['tanggal']) ? $_GET['tanggal'] : '';
$waktu = !empty($_GET['waktu']) ? $_GET['waktu'] : '';
$dosen = !empty($_GET['dosen']) ? $_GET['dosen'] : '';
$gedung = !empty($_GET['gedung']) ? $_GET['gedung'] : '';

$hari = $tanggal ? date('l', strtotime($tanggal)) : '';

$isFilterUsed = $tanggal || $waktu || $dosen || $gedung;

if ($isFilterUsed) {
    $sql = "SELECT r.id_ruangan, r.nama_ruangan, r.lokasi, m.nama_mata_kuliah, m.semester, 
                d.nama AS nama_dosen, k.jadwal_mulai, k.jadwal_selesai, k.nomor_kelas, k.hari
            FROM Ruangan r
            LEFT JOIN Kelas k ON r.id_ruangan = k.id_ruangan 
                AND ('$waktu' = '' OR ('$waktu' BETWEEN k.jadwal_mulai AND k.jadwal_selesai))
                AND ('$hari' = '' OR k.hari = '$hari') 
            LEFT JOIN Mata_Kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah
            LEFT JOIN Dosen d ON k.id_dosen = d.id_dosen 
            WHERE ('$dosen' = '' OR d.nama LIKE '%$dosen%')
                AND ('$gedung' = '' OR r.lokasi = '$gedung')
            ORDER BY r.nama_ruangan";

    $query = mysqli_query($con, $sql);

    if (!$query) {
        error_log("Query gagal: " . mysqli_error($con));
        die("Query gagal dijalankan: " . mysqli_error($con));
    }
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
    <link rel="stylesheet" href="../css/ks.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <button class="toggle-btn" id="toggleButton">☰</button>
    <div class="header">
      <div class="header-box">
        <div class="p-name">
          <h2><?= htmlspecialchars($dataM['nama_mahasiswa']); ?></h2>
        </div>
        <div>
          <h1>SIAM</h1>
        </div>
        <div class="logout">
          <a class="logout" href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>logout</span></a>
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
                        <span class="arrow">▼</span>
                    </div>
                </div>
                <div class=" dropdown" id="dropdown">
                    <a href="ks.php">filter</a>
                </div>
            </div>
        </div>

        <div class="content" id="content">
            <aside class="filter-aside">
                <h2>Filter Jadwal Mata Kuliah</h2>
                <form action="#" method="GET">
                    <div class="incon">
                        <div class="inbox">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
                        </div>
                        <div class="inbox">
                            <label for="waktu">Waktu:</label>
                            <select id="waktu" name="waktu">
                                <option value="">Semua Waktu</option>
                                <option value="08:00" <?= $waktu == '08:00' ? 'selected' : '' ?>>08:00</option>
                                <option value="10:00" <?= $waktu == '10:00' ? 'selected' : '' ?>>10:00</option>
                                <option value="12:00" <?= $waktu == '12:00' ? 'selected' : '' ?>>12:00</option>
                                <option value="14:00" <?= $waktu == '14:00' ? 'selected' : '' ?>>14:00</option>
                                <option value="16:00" <?= $waktu == '16:00' ? 'selected' : '' ?>>16:00</option>
                                <option value="22:00" <?= $waktu == '22:00' ? 'selected' : '' ?>>22:00</option>
                            </select>
                        </div>
                        <div class="inbox">
                            <label for="gedung">Gedung:</label>
                            <select id="gedung" name="gedung">
                                <option value="">Semua</option>
                                <option value="RA" <?= $gedung == 'RA' ? 'selected' : '' ?>>RA</option>
                                <option value="RB" <?= $gedung == 'RB' ? 'selected' : '' ?>>RB</option>
                                <option value="GTC" <?= $gedung == 'GTC' ? 'selected' : '' ?>>GTC</option>
                                <option value="GR" <?= $gedung == 'GR' ? 'selected' : '' ?>>GR</option>
                            </select>
                        </div>    
                        <div class="inbox">
                            <label for="dosen">Dosen:</label>
                            <input type="text" id="dosen" name="dosen" placeholder="Nama Dosen" value="<?= htmlspecialchars($dosen) ?>">
                        </div>
                    </div>
                    <button type="submit">Terapkan Filter</button>
                </form>
            </aside>

            <main class="main-content">
                <h1>Status Ruangan</h1>
                <div class="ruangan-status">
                    <table>
                        <thead>
                            <tr class="head-ruangan">
                                <th>Ruangan</th>
                                <th>Matakuliah</th>
                                <th>Nama Dosen</th>
                                <th>Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($isFilterUsed && mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_array($query)) { ?>
                                <tr id="ruangan-<?= $data['id_ruangan'] ?>" data-id="<?= $data['id_ruangan'] ?>">
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?> (<?= htmlspecialchars($data['lokasi']) ?>)</td>
                                    <td>
                                        <?php if ($data['nama_mata_kuliah']) { ?>
                                            <?= htmlspecialchars($data['nama_mata_kuliah']) ?> - <?= htmlspecialchars($data['semester']) . htmlspecialchars($data['nomor_kelas']) ?>
                                        <?php } else { ?>
                                            Ruangan Kosong
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?= $data['nama_mata_kuliah'] ? htmlspecialchars($data['nama_dosen']) : '-' ?>
                                    </td>
                                    <td>
                                        <?php if ($data['nama_mata_kuliah']) { ?>
                                            <?= htmlspecialchars($data['hari']) . ", " . date('H:i:s', strtotime($data['jadwal_mulai'])) . " - " . date('H:i:s', strtotime($data['jadwal_selesai'])) ?>
                                        <?php } else { ?>
                                            -
                                        <?php } ?>
                                    </td>
                                    
                                </tr>
                            <?php }
                            } elseif ($isFilterUsed) { ?>
                                <tr>
                                    <td colspan="5">Tidak ada data yang ditemukan untuk filter yang dipilih.</td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5">Silakan terapkan filter untuk melihat status ruangan.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </main>

        </div>
    </div>
    <script src="../js/main.js"></script>
</body>
</html>
