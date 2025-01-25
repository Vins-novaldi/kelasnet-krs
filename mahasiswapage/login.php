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
  </head>
  <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    }

    .container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    }
    .login-container {
    background-color: #ffffff;
    border: 1px solid #cccccc;
    width: auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    border-radius: 0px 20px 0px 20px;
    }
    .login-header {
    margin: 0px 0px 50px 0px;
    background-color: #007dff;
    color: #ffffff;
    padding: 10px;
    text-align: left;
    font-weight: bold;
    display: flex;
    align-items: center;
    border-radius: 0px 20px 0px 20px;
    }
    .login-header::before {
    content: "";
    display: inline-block;
    width: 5px;
    height: 20px;
    background-color: #000060;
    margin-right: 10px;
    }
    .login-container input {
    width: 230px;
    padding: 10px;
    margin: 0px 10px 20px 40px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    background-color: #e6f0ff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    .login-container input:focus {
    border: 0 0 10px  #00e9ff;
    }

    .login-container button {
    width: 230px;
    padding: 10px;
    margin: 40px 40px 20px 40px;
    background-color: #000060;
    border: none;
    border-radius: 4px;
    color: #ffffff;
    font-weight: bold;
    cursor: pointer;
    }
@media (max-width: 768px) {
    .login-header {
        text-align: center;
        font-size: 1.2rem;
    }
    .login-header{
       margin: 0px 0px 10px 0px;
    }
    .login-container input,
    .login-container button {
        width: calc(100% - 50px);
        margin: 10px auto;
    }
}
  </style>
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
