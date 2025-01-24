<?php
require "../../sesion.php";
require "../../koneksi.php";
$id_mahasiswa = $_SESSION['id_mahasiswa'];


function hariInggrisKeIndonesia($hariInggris) {
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    return $hari[$hariInggris] ?? "Hari tidak ditemukan";
}

$hariInggris = date('l');
$hariIndonesia = hariInggrisKeIndonesia($hariInggris);


$query = "SELECT 
    k.id_kelas,
    k.hari, 
    k.jadwal_mulai, 
    k.jadwal_selesai, 
    m.kode_mata_kuliah, 
    m.nama_mata_kuliah, 
    m.sks, 
    m.semester, 
    k.nomor_kelas,
    r.nama_ruangan, 
    d.nama AS nama_dosen, 
    k.kapasitas_kelas, 
    k.peserta
FROM kelas k
JOIN mata_kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah
JOIN dosen d ON k.id_dosen = d.id_dosen
JOIN ruangan r ON k.id_ruangan = r.id_ruangan
LEFT JOIN krs kr ON k.id_kelas = kr.id_kelas AND kr.id_mahasiswa = '$id_mahasiswa'
WHERE kr.id_kelas IS NULL
ORDER BY k.hari, k.jadwal_mulai";

$result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/inkrs.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body>
<div class="container">
    <form action="" method="post">
        <h1>Mata Kuliah Ditawarkan</h1>
        <table>
            <thead>
                <tr>    
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Ruang</th>
                    <th>Dosen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['hari']); ?></td>
                <td><?php echo date('H:i', strtotime($row['jadwal_mulai'])) . " - " . date('H:i', strtotime($row['jadwal_selesai'])); ?></td>
                <td>
                    <?= htmlspecialchars($row['kode_mata_kuliah']) . ' ~ ' . htmlspecialchars($row['nama_mata_kuliah']); ?><br>
                    <span class="badge gray"><?= htmlspecialchars($row['semester']); ?><?= htmlspecialchars($row['nomor_kelas']); ?></span>
                    <span class="badge gray">Semester: <?= htmlspecialchars($row['semester']); ?></span>
                    <span class="badge gray">Kuota: <?= htmlspecialchars($row['kapasitas_kelas']); ?></span>
                    <span class="badge green">Peserta: <?= htmlspecialchars($row['peserta']); ?></span>
                </td>
                <td><?= htmlspecialchars($row['sks']); ?></td>
                <td><?= htmlspecialchars($row['nama_ruangan']); ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']); ?></td>
                <td>
                    <button class="action-button" type="submit" name="pilih" value="<?= $row['id_kelas']; ?>">
                        <i class="fas fa-cog"></i> Pilih
                    </button>
                </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
<?php
    require "../../koneksi.php";
    $id_mahasiswa = $_SESSION['id_mahasiswa'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['pilih'])) {
            $id_kelas = $_POST['pilih'];
        } else {
            echo "Error: Anda belum memilih kelas.";
            exit;
        }

        // Validasi jika ID Mahasiswa kosong atau tidak valid
        if (!isset($id_mahasiswa) || empty($id_mahasiswa)) {
            echo "ID Mahasiswa tidak valid.";
            exit;
        }

        // Validasi apakah mahasiswa terdaftar
        $check_mahasiswa = mysqli_query($con, "SELECT * FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'");
        if (mysqli_num_rows($check_mahasiswa) === 0) {
            echo "ID Mahasiswa tidak valid. Pastikan ID Mahasiswa terdaftar.";
            exit;
        }

        // Ambil informasi kelas yang dipilih
        $infoQuery = "SELECT k.* 
                      FROM kelas k 
                      JOIN mata_kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah 
                      WHERE k.id_kelas = '$id_kelas'";
        $infoResult = mysqli_query($con, $infoQuery);
        $selectedClass = mysqli_fetch_assoc($infoResult);

        if (!$selectedClass) {
            echo "Error: ID Kelas tidak ditemukan di database.";
            exit;
        }

        // Tentukan kelas teori dan praktik
        $kelas_teori = null;
        $kelas_praktik = null;

        // Periksa apakah kelas yang dipilih adalah kelas teori atau praktik
        if (strtolower($selectedClass['tipe_kelas']) === 'teori') {
            $kelas_teori = $selectedClass;

            // Cari pasangan id_mata_kuliah praktik berdasarkan tabel relasi
            $id_mata_kuliah = $selectedClass['id_mata_kuliah'];
            $relasiQuery = "SELECT id_praktik FROM relasi_mata_kuliah WHERE id_teori = '$id_mata_kuliah'";
            $relasiResult = mysqli_query($con, $relasiQuery);
            $relasiRow = mysqli_fetch_assoc($relasiResult);

            // Jika ada pasangan kelas praktik
            if ($relasiRow) {
                $id_mata_kuliah_praktik = $relasiRow['id_praktik'];
                $nomor_kelas = $selectedClass['nomor_kelas'];
                $id_dosen = $selectedClass['id_dosen'];

                // Cari kelas praktik yang sesuai
                $pasanganQuery = "SELECT * FROM kelas 
                                  WHERE id_mata_kuliah = '$id_mata_kuliah_praktik' 
                                  AND nomor_kelas = '$nomor_kelas' 
                                  AND id_dosen = '$id_dosen' 
                                  AND tipe_kelas = 'praktik'";
                $pasanganResult = mysqli_query($con, $pasanganQuery);
                $kelas_praktik = mysqli_fetch_assoc($pasanganResult);
            }
        } elseif (strtolower($selectedClass['tipe_kelas']) === 'praktik') {
            $kelas_praktik = $selectedClass;

            // Cari pasangan id_mata_kuliah teori berdasarkan tabel relasi
            $id_mata_kuliah = $selectedClass['id_mata_kuliah'];
            $relasiQuery = "SELECT id_teori FROM relasi_mata_kuliah WHERE id_praktik = '$id_mata_kuliah'";
            $relasiResult = mysqli_query($con, $relasiQuery);
            $relasiRow = mysqli_fetch_assoc($relasiResult);

            // Jika ada pasangan kelas teori
            if ($relasiRow) {
                $id_mata_kuliah_teori = $relasiRow['id_teori'];
                $nomor_kelas = $selectedClass['nomor_kelas'];
                $id_dosen = $selectedClass['id_dosen'];

                // Cari kelas teori yang sesuai
                $pasanganQuery = "SELECT * FROM kelas 
                                  WHERE id_mata_kuliah = '$id_mata_kuliah_teori' 
                                  AND nomor_kelas = '$nomor_kelas' 
                                  AND id_dosen = '$id_dosen' 
                                  AND tipe_kelas = 'teori'";
                $pasanganResult = mysqli_query($con, $pasanganQuery);
                $kelas_teori = mysqli_fetch_assoc($pasanganResult);
            }
        }

        // Validasi pengambilan kelas teori dan praktik
        if (!$kelas_teori && !$kelas_praktik) {
            echo "Error: Tidak ditemukan kelas teori atau praktik untuk nomor_kelas dan id_dosen ini.";
            exit;
        }

        // Bentrok Jadwal
        function cek_bentrok_jadwal($con, $id_mahasiswa, $hari, $waktu_mulai, $waktu_selesai) {
            $bentrokQuery = "SELECT * FROM krs k
                            JOIN kelas c ON k.id_kelas = c.id_kelas
                            WHERE k.id_mahasiswa = '$id_mahasiswa' 
                            AND c.hari = '$hari'
                            AND (('$waktu_mulai' BETWEEN c.jadwal_mulai AND c.jadwal_selesai)
                                OR ('$waktu_selesai' BETWEEN c.jadwal_mulai AND c.jadwal_selesai)
                                OR (c.jadwal_mulai BETWEEN '$waktu_mulai' AND '$waktu_selesai'))";
            $bentrokResult = mysqli_query($con, $bentrokQuery);
            return mysqli_num_rows($bentrokResult) > 0;
        }

        // Cek bentrok jadwal untuk kelas teori
        $bentrok_teori = false;
        if ($kelas_teori) {
            $bentrok_teori = cek_bentrok_jadwal($con, $id_mahasiswa, $kelas_teori['hari'], $kelas_teori['jadwal_mulai'], $kelas_teori['jadwal_selesai']);
        }

        // Cek bentrok jadwal untuk kelas praktik
        $bentrok_praktik = false;
        if ($kelas_praktik) {
            $bentrok_praktik = cek_bentrok_jadwal($con, $id_mahasiswa, $kelas_praktik['hari'], $kelas_praktik['jadwal_mulai'], $kelas_praktik['jadwal_selesai']);
        }

        // Jika tidak ada bentrok, lakukan insert
        if (!$bentrok_teori && !$bentrok_praktik) {
            // Insert kelas teori (jika ada)
            if ($kelas_teori) {
                $id_kelas_teori = $kelas_teori['id_kelas'];
                $insertQueryTeori = "INSERT INTO krs (id_mahasiswa, id_kelas, status_krs, waktu_ditambahkan) 
                                    VALUES ('$id_mahasiswa', '$id_kelas_teori', 'Belum_disetujui', NOW())";
                if (mysqli_query($con, $insertQueryTeori)) {
                    $update_query_teori = "UPDATE kelas SET peserta = peserta + 1 WHERE id_kelas = '$id_kelas_teori'";
                    mysqli_query($con, $update_query_teori);
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            }

            // Insert kelas praktik (jika ada)
            if ($kelas_praktik) {
                $id_kelas_praktik = $kelas_praktik['id_kelas'];
                $insertQueryPraktik = "INSERT INTO krs (id_mahasiswa, id_kelas, status_krs, waktu_ditambahkan) 
                                    VALUES ('$id_mahasiswa', '$id_kelas_praktik', 'Belum_disetujui', NOW())";
                if (mysqli_query($con, $insertQueryPraktik)) {
                    $update_query_praktik = "UPDATE kelas SET peserta = peserta + 1 WHERE id_kelas = '$id_kelas_praktik'";
                    mysqli_query($con, $update_query_praktik);
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            }

            // SweetAlert2 untuk pesan sukses 
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kelas berhasil ditambahkan ke KRS.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'krs.php';
                });
            </script>";
        } else {
            // SweetAlert2 untuk pesan gagal
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terdapat bentrok jadwal atau kelas sudah dipilih.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'krs.php';
                });
            </script>";
        }
    }
?>


</div>
  </body>
</html>