<?php
    require "../../sesion.php";
    require "../../koneksi.php";

    $id_dosen = $_SESSION['id_dosen'];

// Ambil ID dosen dari parameter GET
$id = mysqli_real_escape_string($con, $_GET['d']);

// Query database
$sql = mysqli_query($con, "SELECT 
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
    WHERE k.id_kelas = '$id'
    ORDER BY r.nama_ruangan");

// Cek apakah query berhasil
if (!$sql) {
    die("Query Error: " . mysqli_error($con));
}

// Ambil hasil query
$query = mysqli_fetch_array($sql, MYSQLI_ASSOC);

$queryRuangan = mysqli_query($con, "SELECT * FROM ruangan WHERE id_ruangan!='$query[id_ruangan]'");
$queryHari = mysqli_query($con, "SELECT * FROM kelas WHERE hari!='$query[id_ruangan]'" );


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
    <link rel="stylesheet" href="../css/knDosenEdit.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        </div>

        <div class="content" id="content">
            <main class="main-content">
                <div class="ruangan-status-box">
                <h1>Formulir Pemindahan Ruangan</h1>
                    <div class="ruangan-status">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($query['id_kelas']); ?>">
                            <input type="hidden" name="jadwal_mulai" value="<?= htmlspecialchars($query['jadwal_mulai']); ?>">
                            <input type="hidden" name="jadwal_selesai" value="<?= htmlspecialchars($query['jadwal_selesai']); ?>">
                            <input type="hidden" name="id_ruangan_asal" value="<?= htmlspecialchars($query['id_ruangan']); ?>">
                            <input type="hidden" name="hari_baru" value="<?= htmlspecialchars($query['hari']); ?>">
                            
                            <table>
                                <thead>
                                <tr>
                                    <th>Matakuliah</th>
                                    <th>Nama Dosen</th>
                                    <th>Jadwal</th>
                                    <th>Hari</th>
                                    <th>Ruangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= htmlspecialchars($query['nama_mata_kuliah']); ?> - <?= htmlspecialchars($query['semester']); ?><?= htmlspecialchars($query['nomor_kelas']); ?></td>
                                    <td><?= htmlspecialchars($query['nama_dosen']); ?></td>
                                    <td><input type="time" name="jadwal_mulai_baru" value="<?= date('H:i', strtotime($query['jadwal_mulai'])); ?>"> - <input type="time" name="jadwal_selesai_baru" value="<?= date('H:i', strtotime($query['jadwal_selesai'])); ?>"></td>
                                    <td>
                                    <select name="hari" id="hari">
                                        <option value="<?= $query['hari']; ?>"><?= $query['hari']; ?></option>
                                        <?php while($dataHari = mysqli_fetch_array($queryHari)) { ?>
                                        <option value="<?= $dataHari['hari']; ?>"><?= $dataHari['hari']; ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
                                    <td>
                                    <select name="ruang" id="ruang">
                                        <option value="<?= $query['id_ruangan']; ?>"><?= $query['nama_ruangan']; ?></option>
                                        <?php while($dataRuangan = mysqli_fetch_array($queryRuangan)) { ?>
                                        <option value="<?= $dataRuangan['id_ruangan']; ?>"><?= $dataRuangan['nama_ruangan']; ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="simpanBtn">
                                <button type="submit" name="simpanBtn">Simpan</button>
                            </div>
                            <?php
                                require "../../koneksi.php";

                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpanBtn'])) {
                                    $id_kelas = $_POST['id_kelas'];
                                    $id_ruangan_baru = $_POST['ruang'];
                                    $id_ruangan_asal = $_POST['id_ruangan_asal'];
                                    $hari_baru = $_POST['hari'];
                                    $jadwal_mulai_baru = $_POST['jadwal_mulai_baru']; // Ambil waktu mulai baru
                                    $jadwal_selesai_baru = $_POST['jadwal_selesai_baru']; // Ambil waktu selesai baru

                                    if (empty($id_ruangan_baru)) {
                                        die("Ruangan baru belum dipilih.");
                                    }

                                    // Validasi apakah ruangan baru tersedia dan hari baru tidak bentrok
                                    $queryValidasi = $con->prepare("SELECT * FROM Kelas
                                        WHERE id_ruangan = ? 
                                        AND jadwal_mulai < ? 
                                        AND jadwal_selesai > ? 
                                        AND hari = ? 
                                        AND id_kelas != ?");
                                    $queryValidasi->bind_param('isssi', $id_ruangan_baru, $jadwal_selesai_baru, $jadwal_mulai_baru, $hari_baru, $id_kelas);

                                    $queryValidasi->execute();
                                    $resultValidasi = $queryValidasi->get_result();

                                    if ($resultValidasi->num_rows > 0) {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Ruangan Tidak Tersedia',
                                                    text: 'Ruangan yang Anda pilih sudah terisi pada jadwal ini. Silakan pilih ruangan lain.',
                                                    showConfirmButton: true
                                                }).then(() => {
                                                    window.history.back(); 
                                                });
                                            </script>";
                                        exit;
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Ruangan Tersedia',
                                                    text: 'Ruangan tersedia pada jadwal ini.',
                                                    showConfirmButton: true
                                                });
                                            </script>";
                                    }

                                    // Catat log pemindahan ruangan dan hari
                                    $insertLog = $con->prepare("INSERT INTO Log_Pindah_Ruangan 
                                        (id_kelas, id_ruangan_awal, id_ruangan_baru, waktu_pindah, jadwal_mulai_baru, jadwal_selesai_baru, alasan, hari_baru)
                                        VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)");

                                    $alasan = "Pemindahan ruangan dan hari";

                                    // Binding parameter dengan tipe yang benar
                                    $insertLog->bind_param('iiissss', $id_kelas, $id_ruangan_asal, $id_ruangan_baru, $jadwal_mulai_baru, $jadwal_selesai_baru, $alasan, $hari_baru);

                                    if ($insertLog->execute()) {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: 'Pemindahan ruangan dan hari berhasil dicatat',
                                                    timer: 1500,
                                                    showConfirmButton: false
                                                }).then(() => {
                                                    window.location.href = 'klsnetDosen.php';
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal!',
                                                    text: 'Gagal mencatat pemindahan ruangan: " . $insertLog->error . "',
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                }).then(() => {
                                                    window.location.href = 'klsnetDosenEdit.php';
                                                });
                                            </script>";
                                    }
                                }

                            ?>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="../js/main.js"></script>
</body>
</html>
