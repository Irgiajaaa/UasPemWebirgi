<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Awal</title>
    <link rel="stylesheet" type="text/css" href="index.css"> </head>
<body>
    <div class="container">
        <h2>Selamat Datang</h2>
        <p>Silahkan login untuk masuk ke sistem.</p>
        <a href="login.php" class="login-button">Login</a>
    </div>
</body>
</html>
