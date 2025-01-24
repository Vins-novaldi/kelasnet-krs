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
    <link rel="stylesheet" href="../css/krss.css" />
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
          <a href="../krs/krs.php">Rencana Studi (KRS)</a>
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
          <div class="dropdown" id="dropdown">
            <a href="../kelasnet/ks.php">filter</a>
          </div>
        </div>
      </div>
      <div class="content" id="content">
        <div class="breadcrumb"><a href="#">Home</a> / <a href="#">KRS</a></div>
        <h1>Halaman Rencana Studi</h1>
        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <select>
              <option>3. 20241 (2024 GANJIL)</option>
              <option>2. 20232 (2023 GENAP)</option>
              <option>1. 20231 (2023 GANJIL)</option>
            </select>
            <?php
              require "../../koneksi.php";
              if ($con->connect_error) {
                  die("Koneksi gagal: " . $con->connect_error);
              }

              $sql = "SELECT tanggal_mulai, tanggal_selesai FROM pengaturan_krs WHERE id = 1";
              $result = $con->query($sql);

              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $tanggal_mulai = $row['tanggal_mulai'];
                  $tanggal_selesai = $row['tanggal_selesai'];
              } else {
                  die("Pengaturan tanggal tidak ditemukan.");
              }

              $tanggal_sekarang = date('Y-m-d');
              if ($tanggal_sekarang >= $tanggal_mulai && $tanggal_sekarang <= $tanggal_selesai) {
                  echo '<a href="input_krs.php">Pilih KRS</a>';
              } else {
                  echo '<a>Pendaftaran KRS sudah ditutup.</a>';
              }
            ?>
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
              <tr>
                <td>Semester</td>
                <td>:</td>
                <td>Semester 3</td>
                <td>Status MBKM</td>
                <td>:</td>
                <td>Non-Aktif</td>
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
                <th>keterangan</th>
                <th>aksi</th>
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
                <td><?= htmlspecialchars($row['status_krs']);?></td>
                <form method="POST" action="">
                  <td>
                    <button class="hapus-btn" type="submit" name="hapus" value="<?= $row['id_krs']; ?>">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </form>
                <?php
                  require "../../koneksi.php";

                  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
                      $id_krs = $_POST['hapus']; 
                      $id_mahasiswa = $_SESSION['id_mahasiswa']; 

                      if (!isset($id_mahasiswa) || empty($id_mahasiswa)) {
                          echo "ID Mahasiswa tidak valid.";
                          exit;
                      }

                      $checkKrsQuery = "SELECT * FROM krs WHERE id_krs = '$id_krs' AND id_mahasiswa = '$id_mahasiswa'";
                      $checkKrsResult = mysqli_query($con, $checkKrsQuery);
                      if (mysqli_num_rows($checkKrsResult) === 0) {
                          echo "Error: KRS tidak ditemukan atau Anda tidak memiliki akses untuk menghapus KRS ini.";
                          exit;
                      }
                      $krsData = mysqli_fetch_assoc($checkKrsResult);
                      $id_kelas = $krsData['id_kelas'];

                      $kelasQuery = "SELECT * FROM kelas WHERE id_kelas = '$id_kelas'";
                      $kelasResult = mysqli_query($con, $kelasQuery);
                      $kelasData = mysqli_fetch_assoc($kelasResult);

                      if (!$kelasData) {
                          echo "Error: Kelas tidak ditemukan.";
                          exit;
                      }

                      $kelas_teori = null;
                      $kelas_praktik = null;

                      if (strtolower($kelasData['tipe_kelas']) === 'teori') {
                          $kelas_teori = $kelasData;
                          $id_mata_kuliah = $kelasData['id_mata_kuliah'];
                          $relasiQuery = "SELECT id_praktik FROM relasi_mata_kuliah WHERE id_teori = '$id_mata_kuliah'";
                          $relasiResult = mysqli_query($con, $relasiQuery);
                          $relasiRow = mysqli_fetch_assoc($relasiResult);
                          $id_mata_kuliah_praktik = $relasiRow['id_praktik'];

                          $nomor_kelas = $kelasData['nomor_kelas'];
                          $id_dosen = $kelasData['id_dosen'];
                          $pasanganQuery = "SELECT * FROM kelas 
                                            WHERE id_mata_kuliah = '$id_mata_kuliah_praktik' 
                                            AND nomor_kelas = '$nomor_kelas' 
                                            AND id_dosen = '$id_dosen' 
                                            AND tipe_kelas = 'praktik'";
                          $pasanganResult = mysqli_query($con, $pasanganQuery);
                          $kelas_praktik = mysqli_fetch_assoc($pasanganResult);
                      } elseif (strtolower($kelasData['tipe_kelas']) === 'praktik') {
                          $kelas_praktik = $kelasData;
                          $id_mata_kuliah = $kelasData['id_mata_kuliah'];
                          $relasiQuery = "SELECT id_teori FROM relasi_mata_kuliah WHERE id_praktik = '$id_mata_kuliah'";
                          $relasiResult = mysqli_query($con, $relasiQuery);
                          $relasiRow = mysqli_fetch_assoc($relasiResult);
                          $id_mata_kuliah_teori = $relasiRow['id_teori'];

                          $nomor_kelas = $kelasData['nomor_kelas'];
                          $id_dosen = $kelasData['id_dosen'];
                          $pasanganQuery = "SELECT * FROM kelas 
                                            WHERE id_mata_kuliah = '$id_mata_kuliah_teori' 
                                            AND nomor_kelas = '$nomor_kelas' 
                                            AND id_dosen = '$id_dosen' 
                                            AND tipe_kelas = 'teori'";
                          $pasanganResult = mysqli_query($con, $pasanganQuery);
                          $kelas_teori = mysqli_fetch_assoc($pasanganResult);
                      }
                      if ($kelas_teori) {
                          $deleteTeoriQuery = "DELETE FROM krs WHERE id_kelas = '{$kelas_teori['id_kelas']}' AND id_mahasiswa = '$id_mahasiswa'";
                          mysqli_query($con, $deleteTeoriQuery);

                          $updateQueryTeori = "UPDATE kelas SET peserta = peserta - 1 WHERE id_kelas = '{$kelas_teori['id_kelas']}'";
                          mysqli_query($con, $updateQueryTeori);
                      }

                      if ($kelas_praktik) {
                          $deletePraktikQuery = "DELETE FROM krs WHERE id_kelas = '{$kelas_praktik['id_kelas']}' AND id_mahasiswa = '$id_mahasiswa'";
                          mysqli_query($con, $deletePraktikQuery);

                          $updateQueryPraktik = "UPDATE kelas SET peserta = peserta - 1 WHERE id_kelas = '{$kelas_praktik['id_kelas']}'";
                          mysqli_query($con, $updateQueryPraktik);
                      }
                      echo "<script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Berhasil!',
                              text: 'Kelas berhasil dihapus dari KRS.',
                              timer: 1500,
                              showConfirmButton: false
                          }).then(() => {
                              window.location.href = 'krs.php';
                          });
                      </script>";
                  }
                ?>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <button class="btn">Cetak Kartu Rencana Studi</button>
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
