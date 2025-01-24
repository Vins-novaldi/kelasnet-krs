<?php
session_start();
require "../koneksi.php";

?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="css/log.css">
  </head>
  <body>
    <div class="container">
        <form action="#" method="post">
        <div class="login-container">
            <h1 class="login-header">LOGIN SIAM</h1>
            <input type="text"  placeholder="email/username" id="username" name="username">
            <input type="password"  placeholder="Password" id="password" name="password">
            <button type="submit" name="loginbtn"> Login</button>
                    <?php
                    if (isset($_POST['loginbtn'])) {
                        $username = htmlspecialchars($_POST['username']);
                        $password = htmlspecialchars($_POST['password']);

                        $query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
                        $countdata = mysqli_num_rows($query);
                        $data = mysqli_fetch_array($query);

                        if ($countdata > 0) {
                            if (password_verify($password, $data['password'])) {
                                $_SESSION['username'] = $data['username'];
                                $_SESSION['login'] = true;
                                $_SESSION['role'] = $data['role']; 

                                if ($data['role'] === 'mahasiswa') {
                                    $_SESSION['id_mahasiswa'] = $data['id_mahasiswa'];
                                    header('location: dashboard.php');
                                }   
                                elseif ($data['role'] === 'dosen') {
                                    $_SESSION['id_dosen'] = $data['id_dosen'];
                                header('location: kelasnet/klsnetDosen.php');
                                }
                            } 
                            else {
                                ?>
                                <div class="alert warning">Password Salah</div>
                                <?php
                            }
                        } 
                        else {
                            ?>
                            <div class="alert warning">Akun Tidak Tersedia</div>
                            <?php
                        }
                    }
                    ?>
            
    </div>
    </form>
  </body>
</html>
