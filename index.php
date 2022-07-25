<?php
    session_start();
    if($_SESSION['user'] == null || $_SESSION['user'] == '') {
        header("location:login.php");
        die();
    }
    //echo("<meta http-equiv='refresh' content='2'>");
    require_once('conexion.php');
    $conn = new conexion();
    $datos = "SELECT * from datosPlanta";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="index2.css" rel="stylesheet" type="text/css">
    <link href="navbar2.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/e26021ddcf.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script defer src="navbar2.js"></script>
    <title>Dashboard</title>
</head>
<body>
    <header class="header">
        <nav class="barra">
            <a class="logo barra-link">INVERNADERO</a>
            <button class="nav-toggle" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>
            <ul class="nav-menun">
                <li class="nav-menu-seccion"><a href="index.php" class="nav-menu-liga barra-link">Dashboard</a></li>
                <li class="nav-menu-seccion"><a href="muestras.php" class="nav-menu-liga barra-link">Estadística</a></li>
                <li class="nav-menu-seccion"><a href="probabilidad.php" class="nav-menu-liga barra-link">Calculos</a></li>
                <li class="nav-menu-seccion"><a href="historial.php" class="nav-menu-liga barra-link">Historial</a></li>
                <li class="nav-menu-seccion"><a class="nav-menu-liga barra-link"><?php echo $_SESSION['user']; ?></a></li>
                <li class="nav-menu-seccion"><a href="logout.php" class="nav-menu-liga barra-link">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <h1 class="title">DASHBOARD</h1>
    <?php
        $resultado = mysqli_query($conn->conectarDb(),$datos);
        while($row=mysqli_fetch_assoc($resultado)) {
    ?>
    <div id="main-div">
        <div id="temp"><h2>Temperatura:</h2><?php echo $row["temp"]?>°C</div>
        <div id="hum"><h2>Humedad:</h2><?php echo $row["hum"]?>%</div>
        <div id="humF"><h2>Humedad suelo:</h2><?php echo $row["humFloor"]?>%</div>
        <div id="waterLvl"><h2>Nivel de agua:</h2><?php echo $row["waterLvl"]?>%</div>
    </div>
    <?php } ?>

    <script>
        $(document).ready(function() {
            function refreshData() {
                $("#temp").load("temp.php");
                $("#hum").load("hum.php");
                $("#humF").load("humF.php");
                $("#waterLvl").load("waterLvl.php");
            }
            setInterval(refreshData, 2000);
        });
    </script>
</body>
</html>