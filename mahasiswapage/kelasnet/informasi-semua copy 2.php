<?php
    require "../../koneksi.php";

    date_default_timezone_set('Asia/Jakarta');
    $currentTime = date('H:i:s'); 
    $currentDay = date('l'); 

$sql = "SELECT 
    r.id_ruangan, 
    r.nama_ruangan, 
    r.lokasi, 
    COALESCE(lp.id_ruangan_baru, k_asli.id_ruangan) AS ruangan_aktif,
    IF(lp.id_ruangan_baru IS NOT NULL, m.nama_mata_kuliah, m_asli.nama_mata_kuliah) AS nama_mata_kuliah,
    IF(lp.id_ruangan_baru IS NOT NULL, m.semester, m_asli.semester) AS semester,
    IF(lp.id_ruangan_baru IS NOT NULL, k.nomor_kelas, k_asli.nomor_kelas) AS nomor_kelas,
    IF(lp.id_ruangan_baru IS NOT NULL, d.nama, d_asli.nama) AS nama_dosen,
    IF(lp.id_ruangan_baru IS NOT NULL, lp.waktu_pindah, k_asli.jadwal_mulai) AS jadwal_mulai,
    IF(lp.id_ruangan_baru IS NOT NULL, lp.jadwal_selesai, k_asli.jadwal_selesai) AS jadwal_selesai,
    lp.alasan AS alasan_pindah
FROM ruangan r
LEFT JOIN kelas k_asli 
    ON r.id_ruangan = k_asli.id_ruangan 
    AND ('$currentTime' BETWEEN k_asli.jadwal_mulai AND k_asli.jadwal_selesai)
    AND ('$currentDay' = k_asli.hari)
LEFT JOIN mata_kuliah m_asli 
    ON k_asli.id_mata_kuliah = m_asli.id_mata_kuliah
LEFT JOIN dosen d_asli 
    ON k_asli.id_dosen = d_asli.id_dosen
LEFT JOIN log_pindah_ruangan lp
    ON lp.id_ruangan_baru = r.id_ruangan 
    AND lp.status = 'valid'
    AND lp.jadwal_selesai > NOW()
LEFT JOIN kelas k 
    ON lp.id_kelas = k.id_kelas
LEFT JOIN mata_kuliah m 
    ON k.id_mata_kuliah = m.id_mata_kuliah
LEFT JOIN dosen d 
    ON k.id_dosen = d.id_dosen
WHERE (lp.id_ruangan_awal IS NULL OR lp.id_ruangan_awal != r.id_ruangan)
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

    $id_mahasiswa = $_SESSION['id_mahasiswa'];
$dataResult = mysqli_query($con, "SELECT nama_mahasiswa, nim, jurusan FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'");

if (!$dataResult) {
    error_log("Query mahasiswa gagal: " . mysqli_error($con));
    die("Error mendapatkan data mahasiswa.");
}

$dataM = mysqli_fetch_array($dataResult);
mysqli_close($con);
?>




<head>
    <link rel="stylesheet" href="../css/informasi.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.css" />
</head>
<body>
            <main class="main-content">
                <h1>Status Ruangan</h1>
                <div class="ruangan-status">
                    <div class="head-ruangan">
                        <h2>Ruangan</h2>
                        <div class="head-info">
                            <p>Matakuliah</p>
                            <p>Nama Dosen</p>
                            <p>Jadwal</p>
                            <p>status ruangan</p>
                        </div>
                    </div>

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
?>
<div class="ruangan" id="ruangan-<?= $id_ruangan ?>" data-id="<?= $id_ruangan ?>" data-gedung="<?= $lokasi ?>">
    <p class="note <?= ($nama_mata_kuliah || $alasan_pindah) ? 'ruangan-ada-jadwal' : 'ruangan-kosong' ?>"></p>
    <h2><?= $nama_ruangan ?></h2>
    <div class="info">
        <?php if ($nama_mata_kuliah) { ?>
            <p><?= $nama_mata_kuliah ?> - <?= $semester . $nomor_kelas ?></p>
            <p><?= $nama_dosen ?></p>
            <p><?= date('H:i', strtotime($jadwal_mulai)) . " - " . date('H:i', strtotime($jadwal_selesai)) ?></p>
            <p>ada jadwal</p>
            <?php if ($alasan_pindah) { ?>
                
            <?php } ?>
        <?php } else { ?>
            <p></p>
            <p></p>
            <p id="next-schedule-<?= $id_ruangan ?>"><strong>Memuat jadwal berikutnya...</strong></p>
            <p>Ruangan Kosong</p>
        <?php } ?>
    </div>
</div>
<?php 
    }
} else {
    echo '<p>Tidak ada data yang ditemukan.</p>';
}
?>

                </div>
            </main>
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


    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', (event) => {
                const gedung = event.target.getAttribute('data-gedung'); 
                filterByGedung(gedung); 
            });
        });

        function filterByGedung(gedung) {
            const ruanganElements = document.querySelectorAll('.ruangan');
        
            if (gedung === 'all') {
                ruanganElements.forEach(ruangan => {
                    ruangan.style.display = 'content'; 
                });
            } else {
                ruanganElements.forEach(ruangan => {
                    const ruanganGedung = ruangan.getAttribute('data-gedung');
                
                    if (ruanganGedung === gedung) {
                        ruangan.style.display = 'content';
                    } else {
                        ruangan.style.display = 'none';
                    }
                });
            }
        }
    });


 document.addEventListener("DOMContentLoaded", () => {
    const refreshInterval = 2000; // Refresh setiap 10 detik

    // Perbarui jadwal ruangan secara berkala
    setInterval(() => {
        document.querySelectorAll('.ruangan').forEach(ruangan => {
            const id_ruangan = ruangan.dataset.id; 
            fetchNextSchedule(id_ruangan); 
        });
    }, refreshInterval);

    function fetchNextSchedule(id_ruangan) {
        fetch(`get_next_schedule.php?id_ruangan=${id_ruangan}&random=${Math.random()}`)
            .then(response => response.json())
            .then(data => {
                const nextScheduleInfo = document.getElementById(`next-schedule-${id_ruangan}`);
                const ruanganElement = document.getElementById(`ruangan-${id_ruangan}`);

                if (data.success) {
                    nextScheduleInfo.innerHTML = `<strong>Kelas berikutnya:</strong> ${data.hours} jam ${data.minutes} menit`;
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
});



    
    </script>
</body>
