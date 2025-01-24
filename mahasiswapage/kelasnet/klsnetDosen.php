<?php
    require "../../sesion.php";
    require "../../koneksi.php";


    $id_dosen = $_SESSION['id_dosen'];
    $sql = "SELECT 
            r.id_ruangan,
            r.nama_ruangan,
            r.lokasi,
            m.nama_mata_kuliah,
            m.semester,
            d.nama AS nama_dosen,
            k.id_kelas,
            k.jadwal_mulai,
            k.jadwal_selesai,
            k.nomor_kelas,
            k.hari
        FROM Ruangan r
        LEFT JOIN Kelas k ON r.id_ruangan = k.id_ruangan
        LEFT JOIN Mata_Kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah
        LEFT JOIN Dosen d ON k.id_dosen = d.id_dosen
        WHERE k.id_dosen = '$id_dosen'
        ORDER BY r.nama_ruangan;
    ";

    $query = mysqli_query($con, $sql);

    if (!$query) {
        error_log("Query gagal: " . mysqli_error($con));
        die("Query gagal dijalankan: " . mysqli_error($con));
    }


    // =====================================================

    $dataResult = mysqli_query($con, "SELECT nama FROM dosen WHERE id_dosen = '$id_dosen'");

    if (!$dataResult) {
        error_log("Query mahasiswa gagal: " . mysqli_error($con));
        die("Error mendapatkan data mahasiswa.");
    }

    $dataD = mysqli_fetch_array($dataResult);
    mysqli_close($con);

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard</title>
        <link rel="stylesheet" href="../css/knDosen.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    </head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="header">
        <div class="header-box">
            <div class="p-name">
                <h2><?= htmlspecialchars($dataD['nama']); ?></h2>
            </div>
            <div>
                <h1>SIAM DOSEN</h1>
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
                <a href="#">Dashboard</a>
            </div>
            <div class="item">
                <i class="fa-solid fa-table-list"></i>
                <a href="#">Jadwal Kuliah</a>
            </div>
            <div class="item">
                <img src="../../image/kn.png" alt="" />
                <a href="klsnetDosen.php">KelasNet</a>   
            </div>
            <div class="item">
                <img src="../../image/kn.png" alt="" />
                <a href="informasi.php">KelasNet mahasiswa</a>   
            </div>
        </div>

        <div class="content" id="content">
            <main class="main-content">
                <h1>Jadwal  kelas</h1>
                <div class="ruangan-status">
                    <div class="head-ruangan">
                        <h2>Ruangan</h2>
                        <div class="head-info">
                            <p>Matakuliah</p>
                            <p>Nama Dosen</p>
                            <p>hari</p>
                            <p>Jam</p>
                        </div>
                        <p class="note2">edit</p>
                    </div>

                    <?php 
                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) { ?>
                            <div class="ruangan" id="ruangan-<?= $data['id_ruangan']; ?>">
                                <h2><?= htmlspecialchars($data['nama_ruangan']); ?></h2>
                                <div class="info">
                                    <p><?= htmlspecialchars($data['nama_mata_kuliah']); ?> - <?= htmlspecialchars($data['semester']); ?><?= htmlspecialchars($data['nomor_kelas']); ?></p>
                                    <p><?= htmlspecialchars($data['nama_dosen']); ?></p>
                                    <p><?= htmlspecialchars($data['hari']); ?></p>
                                    <p><?=  date('H:i', strtotime($data['jadwal_mulai'])); ?> - <?=  date('H:i', strtotime($data['jadwal_selesai'])); ?></p>
                                </div>
                                <a href="klsnetDosenEdit.php?d=<?php echo $data['id_kelas']?>" class="note2"><i class="fa-solid fa-pen"></i></a>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Tidak ada data untuk dosen ini.</p>";
                    }
                    ?>
                    
                </div>
                <!-- ============================================================================================================================================== -->
                <div class="ruangan-status" style="margin-top: 100px;">
                    <h1>Jadwal  kelas yang pindah ruangan sementara</h1>
                    <div class="head-ruangan">
                        <h2>Ruangan</h2>
                        <div class="head-info">
                            <p>Matakuliah</p>
                            <p>Nama Dosen</p>
                            <p>Hari</p>
                            <p>jam</p>
                        </div>        
                    </div>
                    <?php
                    require "../../koneksi.php";
                    $id_dosen = $_SESSION['id_dosen'];
                    
                    $sqlRuanganBaru = "SELECT 
                            lpr.hari_baru,
                            lpr.jadwal_mulai_baru,
                            lpr.jadwal_selesai_baru,
                            r.id_ruangan,
                            r.nama_ruangan,
                            r.lokasi,
                            m.nama_mata_kuliah,
                            m.semester,
                            d.nama AS nama_dosen,
                            k.id_kelas,
                            k.jadwal_mulai,
                            k.jadwal_selesai,
                            k.nomor_kelas,
                            k.hari
                        FROM Ruangan r
                        LEFT JOIN Log_Pindah_Ruangan lpr ON r.id_ruangan = lpr.id_ruangan_baru
                        LEFT JOIN Kelas k ON k.id_kelas = lpr.id_kelas
                        LEFT JOIN Mata_Kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah
                        LEFT JOIN Dosen d ON k.id_dosen = d.id_dosen
                        WHERE k.id_dosen = '$id_dosen' AND lpr.status = 'valid'
                        ORDER BY r.nama_ruangan;
                    ";
                    $queryRuanganBaru = mysqli_query($con, $sqlRuanganBaru);

                    if (mysqli_num_rows($queryRuanganBaru) > 0) {
                        while ($dataRuanganBaru = mysqli_fetch_array($queryRuanganBaru)) { ?>
                            <div class="ruangan" id="ruangan-<?= $dataRuanganBaru['id_ruangan']; ?>">
                                <h2><?= htmlspecialchars($dataRuanganBaru['nama_ruangan']); ?></h2>
                                <div class="info">
                                    <p><?= htmlspecialchars($dataRuanganBaru['nama_mata_kuliah']); ?> - <?= htmlspecialchars($dataRuanganBaru['semester']); ?><?= htmlspecialchars($dataRuanganBaru['nomor_kelas']); ?></p>
                                    <p><?= htmlspecialchars($dataRuanganBaru['nama_dosen']); ?></p>
                                    <p><?= htmlspecialchars($dataRuanganBaru['hari_baru']); ?></p>
                                   <p><?=  date('H:i', strtotime($dataRuanganBaru['jadwal_mulai_baru'])); ?> - <?=  date('H:i', strtotime($dataRuanganBaru['jadwal_selesai_baru'])); ?></p>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        echo "Tidak ada data untuk dosen ini";
                    }
                    ?>            
                </div>
            </main>
        </div>
    </div>
    
    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById("dropdown");
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "block";
        }
    }
    
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        sidebar.classList.toggle("hidden");
        content.classList.toggle("expanded");
    }
    </script>
</body>
</html>
