<?php
    session_start();
    if($_SESSION['user'] != null || $_SESSION[['user'] != '']) {
        header("location:index.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="login2.css" rel="stylesheet" type="text/css">
    <title>Login</title>
</head>
<body>
    <form action="validar.php" method="post">
    <h1>Login</h1>
    <input type="text" placeholder="Usuario" name="user">
    <input type="password" placeholder="Contraseña" name="pass">
    <input type="submit" value="Ingresar" id="btn">
    </form>
</body>
</html>