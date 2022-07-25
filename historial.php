<?php
    session_start();
    if($_SESSION['user'] == null || $_SESSION['user'] == '') {
        header("location:login.php");
        die();
    }
    //echo("<meta http-equiv='refresh' content='2'>");
    require_once('conexion.php');

    $conn = new conexion();

    $limit = isset($_POST["limit-records"]) ? $_POST["limit-records"] : 10;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$start = ($page - 1) * $limit;
	$result = mysqli_query($conn->conectarDb(),"SELECT * FROM historial LIMIT $start, $limit");
	$customers = $result->fetch_all(MYSQLI_ASSOC);

	$result1 = mysqli_query($conn->conectarDb(),"SELECT count(id) AS id FROM historial");
	$custCount = $result1->fetch_all(MYSQLI_ASSOC);
	$total = $custCount[0]['id'];
	$pages = ceil( $total / $limit );

    $Previous = $page - 1;
	$Next = $page + 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="index2.css" rel="stylesheet" type="text/css">
    <link href="navbar2.css" rel="stylesheet" type="text/css">

    <script src="https://kit.fontawesome.com/e26021ddcf.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script defer src="navbar2.js"></script>
    
    <title>HISTORIAL</title>
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

    <h1 class="title">HISTORIAL</h1>
    
    <div class="container well">
		<div class="row">
			<div class="col-md-10">
				<nav aria-label="Page navigation">
					<ul class="pagination">
                        <li class="page-item">
                        <a href="?page=<?= $Previous; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Anterior</span>
                        </a>
                        </li>
                        <?php for($i = 1; $i<= $pages; $i++) : ?>
                            <li><a href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                        <?php endfor; ?>
                        <li class="page-item">
                        <a href="?page=<?= $Next; ?>" aria-label="Next">
                            <span aria-hidden="true">Siguiente &raquo;</span>
                        </a>
                        </li>
				  </ul>
				</nav>
			</div>
		</div>
        <div id="tableData" class="tableData" style="height: 600px; overflow-y: auto;">
            <table id="tablita" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TEMPERATURA</th>
                        <th>HUMEDAD</th>
                        <th>HUMEDAD SUELO</th>
                        <th>SE REGÓ</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['id']?></td>
                        <td><?= $customer['temp']?></td>
                        <td><?= $customer['hum']?></td>
                        <td><?= $customer['humF']?></td>
                        <td><?= $customer['regado']?></td>
                        <td><?= $customer['date']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php

        if(isset($_POST['erase-btn'])){
            $query = "TRUNCATE TABLE historial";
            $resultado = mysqli_query($conn->conectarDb(),$query);
        }

        ?>
        <div class="botones">
            <form method="post" id="boton"><input type="submit" name="erase-btn" value="Vaciar historial"></form>
        </div>
    </div>
<div style="position: fixed; bottom: 10px; left: 10px; color: #0055ff;">
        <strong>
            VortexSoft
        </strong>
</div>

<form method="post" id="start-btn"><input id="startBtn" type="submit" name="start-btn" value="Tomar muestras"></form>

<script type="text/javascript">
	$(document).ready(function(){
        document.getElementById("start-btn").style.visibility = "hidden";

		$("#limit-records").change(function(){
			$('form').submit();
		})

        function sendForm()
        {
            $('#startBtn').click();
        }
        setInterval(sendForm, 5000);
        
	});

</script>

    <!-- <script>
        $(document).ready(function() {
            function refresh() {
                $("#temp").load("temp.php");
                $("#hum").load("hum.php");
                $("#humF").load("humF.php");
                $("#waterLvl").load("waterLvl.php");
            }
            setInterval(refresh, 2000);
        });
    </script> -->
</body>
</html>