<?php
    require "../../koneksi.php";

    date_default_timezone_set('Asia/Jakarta');
    $currentTime = date('H:i:s'); 
    $currentDay = date('l'); 

    $sql = "SELECT r.id_ruangan, r.nama_ruangan, r.lokasi, 
           m.nama_mata_kuliah, m.semester, d.nama AS nama_dosen, 
           k.jadwal_mulai, k.jadwal_selesai, k.nomor_kelas,
           lp.alasan AS alasan_pindah, lp.waktu_pindah AS waktu_pindah, lp.jadwal_selesai AS pindah_jadwal_selesai
    FROM Ruangan r
    LEFT JOIN Kelas k 
        ON r.id_ruangan = k.id_ruangan 
        AND ('$currentTime' BETWEEN k.jadwal_mulai AND k.jadwal_selesai)
        AND ('$currentDay' = k.hari)
    LEFT JOIN Mata_Kuliah m 
        ON k.id_mata_kuliah = m.id_mata_kuliah
    LEFT JOIN Dosen d 
        ON k.id_dosen = d.id_dosen 
    LEFT JOIN Log_Pindah_Ruangan lp
        ON lp.id_ruangan_baru = r.id_ruangan
        AND lp.jadwal_selesai > NOW()
        WHERE r.lokasi = 'RB'
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
                        </div>
                    </div>
                    <?php 
                    // Pastikan query sudah berjalan dengan benar
                    if (mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) { 
                            // Menampilkan data ruangan
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
                            $waktu_pindah = $data['waktu_pindah'];
                            $pindah_jadwal_selesai = $data['pindah_jadwal_selesai'];
                    ?>
                    <div class="ruangan" id="ruangan-<?= $id_ruangan ?>" data-id="<?= $id_ruangan ?>" data-gedung="<?= $lokasi ?>">
                        <!-- Status ruangan berdasarkan jadwal atau alasan pindah -->
                        <p class="note <?= ($nama_mata_kuliah || $alasan_pindah) ? 'ruangan-ada-jadwal' : 'ruangan-kosong' ?>"></p>
                        <h2><?= $nama_ruangan ?></h2>
                        <div class="info">
                            <?php if ($nama_mata_kuliah) { ?>
                                <p> <?= $nama_mata_kuliah ?> - <?= $semester . $nomor_kelas ?></p>
                                <p> <?= $nama_dosen ?></p>
                                <p> <?= date('H:i', strtotime($jadwal_mulai)) . " - " . date('H:i', strtotime($jadwal_selesai)) ?></p>
                            <?php } elseif ($alasan_pindah) { ?>
                                <p><strong>Jadwal Pindahan:</strong> Kelas dari ruangan lain telah dipindahkan ke sini.</p>
                                <p>Alasan: <?= $alasan_pindah ?></p>
                                <p>Waktu Pindah: <?= date('d-m-Y H:i', strtotime($waktu_pindah)) ?></p>
                                <p>Jadwal selesai: <?= date('H:i', strtotime($pindah_jadwal_selesai)) ?></p>
                            <?php } else { ?>
                                <p>Ruangan Kosong</p>
                                <p id="next-schedule-<?= $id_ruangan ?>"><strong>Memuat jadwal berikutnya...</strong></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php 
                        } // End of while
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

// =================================================
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
            console.log("Response data:", data);  

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
// ======================================


    const prototypeadd = document.querySelector(".filter-button");
        prototypeadd.onclick = () => {
        prototypeadd.classList.toggle("active");
    };

    
    </script>
</body>
