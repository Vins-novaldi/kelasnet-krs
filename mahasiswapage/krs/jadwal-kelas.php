<?php
require "../../koneksi.php";
require "../../sesion.php";

$id_mahasiswa = $_SESSION['id_mahasiswa'];

$dataResult = mysqli_query($con, "SELECT nama_mahasiswa, nim, jurusan FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'");
$data = mysqli_fetch_array($dataResult);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KRS</title>
    <link rel="stylesheet" href="../css/jadwal-kelas.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="header">
      <div class="header-box">
        <div class="p-name">
          <h2><?= htmlspecialchars($data['nama_mahasiswa']); ?></h2>
        </div>
        <h1>SIAM</h1>
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
          <a href="krs.php">Rencana Studi (KRS)</a>
        </div>
        <div class="item">
          <i class="fa-solid fa-table-list"></i>
          <a href="jadwal-kelas.php">Jadwal Kuliah</a>
        </div>
        <div class="item-4">
          <div class="menu-item" onclick="toggleDropdown()">
            <div class="t-sub">
              <img src="../../image/kn.png" alt="" />
              <a href="../kelasnet/informasi.php">KelasNet</a>
            </div>
            <div class="panah-sub">
              <span class="arrow">▼</span>
            </div>
          </div>
          <div class=" dropdown" id="dropdown">
            <a href="../kelasnet/ks.php">filter</a>
          </div>
        </div>
      </div>
      <div class="content" id="content">
        <div class="breadcrumb"><a href="#">Home</a> / <a href="#">Jadwal Kuliah</a></div>
        <h1>Jadwal Kuliah</h1>
        <div class="card">
          <div
            style="
              display: flex;
              justify-content: space-between;
              align-items: center;
            "
          >
            <select>
              <option>3. 20241 (2024 GANJIL)</option>
              <option>2. 20232 (2023 GENAP)</option>
              <option>1. 20231 (2023 GANJIL)</option>
            </select>
          </div>
        </div>
        <div class="tabel">
          <div class="student-info">
            <table>
              <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nama_mahasiswa']); ?></td>
                <td>Dosen PA</td>
                <td>:</td>
                <td>Dr. Baidarus, M.M., M.Ag</td>
              </tr>
              <tr>
                <td>NIM</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nim']); ?></td>
                <td>Program Studi</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['jurusan']); ?></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="krs-info">
          <table border="1" cellpadding="10" cellspacing="0">
            <thead>
              <tr>
                <th>hari</th>
                <th>waktu</th>
                <th>matakuliah</th>
                <th>SKS</th>
                <th>ruang</th>
                <th>Dosen</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $query = "SELECT 
                  krs.id_krs,
                  krs.status_krs,
                  mk.kode_mata_kuliah,
                  mk.nama_mata_kuliah,
                  mk.sks,
                  mk.semester,
                  k.nomor_kelas,
                  k.hari,
                  k.jadwal_mulai,
                  k.jadwal_selesai,
                  r.nama_ruangan,
                  d.nama AS nama_dosen
              FROM krs
              JOIN kelas k ON krs.id_kelas = k.id_kelas
              JOIN mata_kuliah mk ON k.id_mata_kuliah = mk.id_mata_kuliah
              JOIN dosen d ON k.id_dosen = d.id_dosen
              JOIN ruangan r ON k.id_ruangan = r.id_ruangan
              WHERE krs.id_mahasiswa = '$id_mahasiswa'
              ORDER BY k.hari, k.jadwal_mulai";
              
              $result = mysqli_query($con, $query);

              while ($row = mysqli_fetch_array($result)) : ?>
              <tr>
                <td><?= htmlspecialchars($row['hari']); ?></td>
                <td>
                  <?= htmlspecialchars(date('H:i', strtotime($row['jadwal_mulai']))) . " - " . htmlspecialchars(date('H:i', strtotime($row['jadwal_selesai']))); ?>
                </td>
                <td>
                  <?= htmlspecialchars($row['kode_mata_kuliah']) . ' ~ ' . htmlspecialchars($row['nama_mata_kuliah']); ?><br />
                  <span class="badge gray"
                    ><?= htmlspecialchars($row['semester']); ?><?= htmlspecialchars($row['nomor_kelas']); ?></span
                  >
                </td>

                <td><?= htmlspecialchars($row['sks']); ?></td>
                <td><?= htmlspecialchars($row['nama_ruangan']); ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']); ?></td>
                </form>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div class="ruangan-status" style="margin-top: 100px;">
                    <h1><span><i class="fa-solid fa-triangle-exclamation"></i></span> Jadwal  kelas yang pindah ruangan sementara <span><i class="fa-solid fa-triangle-exclamation"></i></span></h1>
                    <div class="head-ruangan">
                        <h2>Pindah ke Ruangan</h2>
                        <div class="head-info">
                            <p>Matakuliah</p>
                            <p>Nama Dosen</p>
                            <p>hari</p>
                            <p>jam</p>
                        </div>        
                    </div>
                    <?php
                    require "../../koneksi.php";
                    
                    $sqlRuanganBaru = "SELECT 
                        lpr.hari_baru,
                        lpr.jadwal_mulai_baru,
                        lpr.jadwal_selesai_baru,
                        r.id_ruangan,
                        r.nama_ruangan,
                        r.lokasi,
                        m.nama_mata_kuliah,
                        m.semester,
                        mh.nama_mahasiswa,
                        k.id_kelas,
                        k.jadwal_mulai,
                        k.jadwal_selesai,
                        k.nomor_kelas,
                        k.hari,
                        d.nama AS nama_dosen
                    FROM krs 
                    LEFT JOIN Log_Pindah_Ruangan lpr ON krs.id_kelas = lpr.id_kelas
                    LEFT JOIN Ruangan r ON lpr.id_ruangan_baru = r.id_ruangan
                    LEFT JOIN Kelas k ON krs.id_kelas = k.id_kelas
                    LEFT JOIN Mata_Kuliah m ON k.id_mata_kuliah = m.id_mata_kuliah
                    LEFT JOIN Mahasiswa mh ON krs.id_mahasiswa = mh.id_mahasiswa
                    LEFT JOIN Dosen d ON k.id_dosen = d.id_dosen
                    WHERE lpr.status = 'valid' AND krs.id_mahasiswa = '$id_mahasiswa'
                    ORDER BY r.nama_ruangan;";


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
                        echo "Tidak ada jadwal pindah ruangan";
                    }
                    ?>            
                </div>
      </div>
    </div>
    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        sidebar.classList.toggle("hidden");
        content.classList.toggle("expanded");
      }

      function toggleDropdown() {
        const dropdown = document.getElementById("dropdown");
        if (dropdown.style.display === "block") {
          dropdown.style.display = "none";
        } else {
          dropdown.style.display = "block";
        }
      }
    </script>
  </body>
</html>
