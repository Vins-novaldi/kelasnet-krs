<?php
require "../koneksi.php";
require "../sesion.php";
$id_mahasiswa = $_SESSION['id_mahasiswa'];


$dataResult = mysqli_query($con, "SELECT nama_mahasiswa, nim, jurusan FROM mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'");
$data = mysqli_fetch_array($dataResult);


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/das.css" />
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
          <a class="logout" href="logout.php">LOGOUT</a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="sidebar" id="sidebar">
        <div class="item">
          <i class="fa-solid fa-house"></i>
          <a href="dashboard.php">Dashboard</a>
        </div>
        <div class="item">
          <i class="fa-solid fa-graduation-cap"></i>
          <a href="krs/krs.php">Rencana Studi (KRS)</a>
        </div>
        <div class="item">
          <i class="fa-solid fa-table-list"></i>
          <a href="krs/jadwal-kelas.php">Jadwal Kuliah</a>
        </div>
        <div class="item-4">
          <div class="menu-item" onclick="toggleDropdown()">
            <div class="t-sub">
              <img src="../image/kn.png" alt="" />
              <a href="kelasnet/informasi.php">KelasNet</a>
            </div>
            <div class="panah-sub">
              <span class="arrow">▼</span>
            </div>
          </div>
          <div class=" dropdown" id="dropdown">
            <a href="kelasnet/ks.php">filter</a>
          </div>
        </div>
      </div>
      <div class="content" id="content">
        <div class="profil">
          <?php
          if ($id_mahasiswa == 1) { ?>
                <h1>KELOMPOK 9</h1>
                <div class="team-container">
                  <div class="team-member">
                    <img src="../image/pijay2.jpg" alt="Team Member 1" />
                    <h2>Vijjay Novaldi</h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                  <div class="team-member">
                    <img src="../image/raihan.jpg" alt="Team Member 1" />
                    <h2>Rayhan Arrafi <br>Al Hariri</h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                  <div class="team-member">
                    <img src="../image/kabo.jpg" alt="Team Member 1" />
                    <h2>Khawalid Kasasi Tanjung</h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                </div>
          <?php } elseif ($id_mahasiswa == 2) { ?>
                <h1>KELOMPOK 7</h1>
                <div class="team-container">
                  <div class="team-member">
                    <img src="../image/rey.jpg" alt="Team Member 2" />
                    <h2>Andre Putra Melky.p</h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                  <div class="team-member">
                    <img src="../image/monic.jpg" alt="Team Member 2" />
                    <h2>Monica Alya Ramadhani </h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                  <div class="team-member">
                    <img src="../image/bayu.jpg" alt="Team Member 2" />
                    <h2>Muhammad Bayu</h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                  <div class="team-member">
                    <img src="../image/sofwan.jpg" alt="Team Member 2" />
                    <h2>Sofwan Alfajar </h2>
                    <div class="social-icons">
                      <a href="#"><i class="fa-brands fa-instagram"></i></a>
                      <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                      <a href="#"><i class="fa-brands fa-twitter"></i></a>
                      <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                    </div>
                  </div>
                </div>
                <form method="POST" action="">
                  <label for="tanggal_mulai">Tanggal Mulai:</label>
                  <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                  
                  <label for="tanggal_selesai">Tanggal Selesai:</label>
                  <input type="date" id="tanggal_selesai" name="tanggal_selesai" required>
                  
                  <button type="submit">Simpan</button>
              </form>
              <?php
              require "../koneksi.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];



    if ($con->connect_error) {
        die("Koneksi gagal: " . $con->connect_error);
    }

    $sql = "UPDATE pengaturan_krs SET tanggal_mulai = '$tanggal_mulai', tanggal_selesai = '$tanggal_selesai' WHERE id = 1";

    if ($con->query($sql) === TRUE) {

        echo "<script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Berhasil!',
                              text: 'Pengaturan berhasil diperbarui.',
                              timer: 1500,
                              showConfirmButton: false
                          }).then(() => {
                              window.location.href = 'dashboard.php';
                          });
                      </script>";
    } else {
        echo "Error: " . $con->error;
    }

    $con->close();
}
?>


          <?php }  ?>
          
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