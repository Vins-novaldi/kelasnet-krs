<?php
require "../../koneksi.php";

date_default_timezone_set('Asia/Jakarta');
$currentDateTime = date('Y-m-d H:i:s'); // Waktu sekarang dengan format lengkap

$sql = "SELECT 
        r.id_ruangan, 
        r.nama_ruangan, 
        r.lokasi, 
        COALESCE(lp.id_ruangan_baru, k_asli.id_ruangan) AS ruangan_aktif,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN m.nama_mata_kuliah
            ELSE m_asli.nama_mata_kuliah
        END AS nama_mata_kuliah,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN m.semester
            ELSE m_asli.semester
        END AS semester,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN k.nomor_kelas
            ELSE k_asli.nomor_kelas
        END AS nomor_kelas,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN d.nama
            ELSE d_asli.nama
        END AS nama_dosen,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN lp.jadwal_mulai_baru
            ELSE k_asli.jadwal_mulai
        END AS jadwal_mulai,
        CASE 
            WHEN lp.id_ruangan_baru IS NOT NULL THEN lp.jadwal_selesai_baru
            ELSE k_asli.jadwal_selesai
        END AS jadwal_selesai,
        lp.alasan AS alasan_pindah
    FROM ruangan r
    LEFT JOIN kelas k_asli 
        ON r.id_ruangan = k_asli.id_ruangan 
        AND ('$currentDateTime' BETWEEN k_asli.jadwal_mulai AND k_asli.jadwal_selesai)
    LEFT JOIN mata_kuliah m_asli 
        ON k_asli.id_mata_kuliah = m_asli.id_mata_kuliah
    LEFT JOIN dosen d_asli 
        ON k_asli.id_dosen = d_asli.id_dosen
    LEFT JOIN log_pindah_ruangan lp
        ON lp.id_ruangan_baru = r.id_ruangan 
        AND lp.status = 'valid'
        AND lp.jadwal_selesai_baru > '$currentDateTime'
    LEFT JOIN kelas k 
        ON lp.id_kelas = k.id_kelas
    LEFT JOIN mata_kuliah m 
        ON k.id_mata_kuliah = m.id_mata_kuliah
    LEFT JOIN dosen d 
        ON k.id_dosen = d.id_dosen
    WHERE 
        (lp.id_ruangan_awal IS NULL OR lp.id_ruangan_awal != r.id_ruangan)
        AND r.lokasi = 'GR'
    ORDER BY r.nama_ruangan;
";

$query = mysqli_query($con, $sql);

if (!$query) {
    echo json_encode([
        'success' => false,
        'message' => "Query gagal dijalankan: " . mysqli_error($con)
    ]);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Ruangan</title>
    <link rel="stylesheet" href="../css/informasi.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <main class="main-content">
        <h1>Status Ruangan</h1>
        <div class="ruangan-status">
            <table>
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Matakuliah</th>
                        <th>Nama Dosen</th>
                        <th>Jadwal</th>
                        <th>Status Ruangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) { 
                            $id_ruangan = $data['id_ruangan'];
                            $nama_ruangan = htmlspecialchars($data['nama_ruangan']);
                            $lokasi = htmlspecialchars($data['lokasi']);
                            $nama_mata_kuliah = htmlspecialchars($data['nama_mata_kuliah']);
                            $semester = htmlspecialchars($data['semester']);
                            $nomor_kelas = htmlspecialchars($data['nomor_kelas']);
                            $nama_dosen = htmlspecialchars($data['nama_dosen']);
                            $jadwal_mulai = $data['jadwal_mulai'];
                            $jadwal_selesai = $data['jadwal_selesai'];
                            $alasan_pindah = htmlspecialchars($data['alasan_pindah']);

                            $status_ruangan = ($nama_mata_kuliah || $alasan_pindah) ? "Ada Jadwal" : "Ruangan Kosong";
                    ?>
                    <tr class="ruangan" id="ruangan-<?= $id_ruangan ?>" data-id="<?= $id_ruangan ?>" data-gedung="<?= $lokasi ?>">
                        <td>
                            <p class="note <?= ($nama_mata_kuliah || $alasan_pindah) ? 'ruangan-kosong' : 'ruangan-ada-jadwal' ?>"></p>
                            <h2><?= $nama_ruangan ?></h2>
                        </td>
                        <td>
                            <?php if ($nama_mata_kuliah) { ?>
                                <?= $nama_mata_kuliah ?> - <?= $semester . $nomor_kelas ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?= $nama_dosen ?>
                        </td>
                        <td>
                            <?php if ($nama_mata_kuliah) { ?>
                                <?= date('H:i', strtotime($jadwal_mulai)) . " - " . date('H:i', strtotime($jadwal_selesai)) ?>
                            <?php } else { ?>
                                <p id="next-schedule-<?= $id_ruangan ?>">Memuat jadwal berikutnya...</p>
                            <?php } ?>
                        </td>
                        <td class="note2 <?= ($nama_mata_kuliah || $alasan_pindah) ? 'ruangan-kosong' : 'ruangan-ada-jadwal' ?>">
                        <p ><?= $status_ruangan ?></p>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="5">Tidak ada data yang ditemukan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setInterval(() => {
                document.querySelectorAll('.ruangan').forEach(ruangan => {
                    const id_ruangan = ruangan.dataset.id; 
                    fetchNextSchedule(id_ruangan); 
                });
            }, 2000);
        });

        function fetchNextSchedule(id_ruangan) {
            fetch(`get_next_schedule.php?id_ruangan=${id_ruangan}&random=${Math.random()}`)
            .then(response => response.json())
            .then(data => {
                const nextScheduleInfo = document.getElementById(`next-schedule-${id_ruangan}`);
                const ruanganElement = document.getElementById(`ruangan-${id_ruangan}`);

                if (data.success) {
                    nextScheduleInfo.innerHTML = `<strong>Kelas berikutnya:</strong> ${data.hours} jam ${data.minutes} menit ${data.seconds} detik.`;
                    ruanganElement.classList.add("ruangan-ada-jadwal");
                    ruanganElement.classList.remove("ruangan-kosong");
                } else {
                    nextScheduleInfo.innerHTML = `<strong>${data.message}</strong>`;
                    ruanganElement.classList.add("ruangan-kosong");
                    ruanganElement.classList.remove("ruangan-ada-jadwal");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    </script>
</body>
</html>
