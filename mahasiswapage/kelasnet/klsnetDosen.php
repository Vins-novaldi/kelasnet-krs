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
    <button class="toggle-btn" id="toggleButton">☰</button>
    <div class="header">
        <div class="header-box">
            <div class="p-name">
                <h2><?= htmlspecialchars($dataD['nama']); ?></h2>
            </div>
            <div>
                <h1>SIAM DOSEN</h1>
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
                <div class="ruangan-status-box">
                <h1>Jadwal  kelas</h1>
                <div class="ruangan-status">
                    <table>
                    <thead>
                        <tr>
                        <th>Ruangan</th>
                        <th>Matakuliah</th>
                        <th>Nama Dosen</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['nama_ruangan']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_mata_kuliah']); ?> - <?= htmlspecialchars($data['semester']); ?><?= htmlspecialchars($data['nomor_kelas']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_dosen']); ?></td>
                                    <td><?= htmlspecialchars($data['hari']); ?></td>
                                    <td><?= date('H:i', strtotime($data['jadwal_mulai'])); ?> - <?= date('H:i', strtotime($data['jadwal_selesai'])); ?></td>
                                    <td><a href="klsnetDosenEdit.php?d=<?= $data['id_kelas']; ?>" class="note2"><i class="fa-solid fa-pen"></i></a></td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada data untuk dosen ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                </div>
                </div>

                <!-- ============================================================================================================================================== -->
                <div class="ruangan-status-box" style="margin-top: 100px;">
               <h1><span><i class="fa-solid fa-triangle-exclamation"></i></span> Jadwal kelas yang pindah ruangan sementara <span><i class="fa-solid fa-triangle-exclamation"></i></span></h1>
                <div class="ruangan-status">
                    <table>
                    <thead>
                        <tr>
                        <th>Ruangan</th>
                        <th>Matakuliah</th>
                        <th>Nama Dosen</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        ORDER BY r.nama_ruangan;";

                        $queryRuanganBaru = mysqli_query($con, $sqlRuanganBaru);

                        if (mysqli_num_rows($queryRuanganBaru) > 0) {
                            while ($dataRuanganBaru = mysqli_fetch_array($queryRuanganBaru)) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($dataRuanganBaru['nama_ruangan']); ?></td>
                                    <td><?= htmlspecialchars($dataRuanganBaru['nama_mata_kuliah']); ?> - <?= htmlspecialchars($dataRuanganBaru['semester']); ?><?= htmlspecialchars($dataRuanganBaru['nomor_kelas']); ?></td>
                                    <td><?= htmlspecialchars($dataRuanganBaru['nama_dosen']); ?></td>
                                    <td><?= htmlspecialchars($dataRuanganBaru['hari_baru']); ?></td>
                                    <td><?=  date('H:i', strtotime($dataRuanganBaru['jadwal_mulai_baru'])); ?> - <?=  date('H:i', strtotime($dataRuanganBaru['jadwal_selesai_baru'])); ?></td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data untuk dosen ini</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="../js/main.js"></script>
</body>
</html>
