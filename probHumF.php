<?php
    session_start();
    if($_SESSION['user'] == null || $_SESSION['user'] == '') {
        header("location:login.php");
        die();
    }
    //echo("<meta http-equiv='refresh' content='2'>");
    require_once('conexion.php');
    $conn = new conexion();

    $limpiar = "TRUNCATE TABLE muestraTemp";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    $limpiar = "TRUNCATE TABLE muestraHum";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    $limpiar = "TRUNCATE TABLE muestraHumF";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    $limpiar = "TRUNCATE TABLE clasesTemp";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    $limpiar = "TRUNCATE TABLE clasesHum";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    $limpiar = "TRUNCATE TABLE clasesHumF";
    $resultClean = mysqli_query($conn->conectarDb(), $limpiar);
    mysqli_free_result($resultClean);
    
    $getQuery = "SELECT @row AS row, T.* FROM `muestras` AS T JOIN (SELECT @row:= 0) R WHERE (@row:= @row+ 1) HAVING (row % 5) = 0";
    if ($resultado = mysqli_query($conn->conectarDb(),$getQuery)) {
        while ($row = mysqli_fetch_assoc($resultado)) {

            $temp = $row['temp'];
            $hum = $row['hum'];
            $humF = $row['humF'];

            $query = "INSERT INTO muestraTemp(`temp`) VALUES ('$temp')";
            $insert = mysqli_query($conn->conectarDb(),$query);
            mysqli_free_result($insert);
            $query = "INSERT INTO muestraHum(`hum`) VALUES ('$hum')";
            $insert = mysqli_query($conn->conectarDb(),$query);
            mysqli_free_result($insert);
            $query = "INSERT INTO muestraHumF(`humF`) VALUES ('$humF')";
            $insert = mysqli_query($conn->conectarDb(),$query);
            mysqli_free_result($insert);

        }
    }
    mysqli_free_result($resultado);

    require_once('calculoHumF.php');

	$result = mysqli_query($conn->conectarDb(),"SELECT * FROM muestras");
	$customers = $result->fetch_all(MYSQLI_ASSOC);
    mysqli_free_result($result);
    $result = mysqli_query($conn->conectarDb(),"SELECT * FROM muestraHumF");
	$customers2 = $result->fetch_all(MYSQLI_ASSOC);
    mysqli_free_result($result);
    $result = mysqli_query($conn->conectarDb(),"SELECT * FROM clasesHumF");
	$customers3 = $result->fetch_all(MYSQLI_ASSOC);
    mysqli_free_result($result);

    require_once('dispersionHumF.php');

    // MEDIA POBLACION MENOR O MAYOR A LA DE LA MUESTRA (HIPOTESIS ALTERNATIVA)

    if ($mediaNoA == $mediaNoA2) {
        $auxH = 'igual';
        $auxHS = '=';
    }

    if ($mediaNoA != $mediaNoA2) {
        $auxH = 'diferente';
        $auxHS = '≠';
    }

    if ($zCalculada > -1.96 || $zCalculada < 1.96) {
        $auxZ = 'Ho';
        $auxR = 'nula';
        $auxB = 'cierto';
        $auxC = 'La humedad de suelo promedio es de '.$mediaNoA;
    } else {
        $auxZ = 'H1';
        $auxR = 'alternativa';
        $auxB = 'falso';
        $auxC = 'La humedad de suelo promedio no es de '.$mediaNoA;
    }

    $auxZoperacion = $desvEstandarPoblacion / pow($N2, 1 / 2);

    $zCalculada = ($mediaNoA2 - $mediaNoA) / $auxZoperacion;

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
    <title>Calculos</title>
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
    <h1 class="title">CALCULOS</h1>

    <center><h5 class="datosTitle">HUMEDAD SUELO</h5></center>

    <div class="container well">

        <div id="tableData" class="tableData" style="height: 600px; overflow-y: auto;">
            <table id="tablita" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>TEMPERATURA</th>
                        <th>HUMEDAD</th>
                        <th>HUMEDAD SUELO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['temp']?></td>
                        <td><?= $customer['hum']?></td>
                        <td><?= $customer['humF']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

        <div id="tableData" class="tableData" style="height: 600px; overflow-y: auto; margin-top: 100px;">
            <table id="tablita" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>CLASES</th>
                        <th>LIM INFERIOR</th>
                        <th>LIM SUPERIOR</th>
                        <th>FRECUENCIA</th>
                        <th>FREC RELATIVA</th>
                        <th>FREC ACUMULADA</th>
                        <th>MARCA CLASE</th>
                        <th>FREC COMPLEMENTARIA</th>
                        <th>LINF INF EXACTO</th>
                        <th>LIM SUP EXACTO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers3 as $customer3): ?>
                    <tr>
                        <td><?= $customer3['class']?></td>
                        <td><?= $customer3['limInf']?></td>
                        <td><?= $customer3['limSup']?></td>
                        <td><?= $customer3['frec']?></td>
                        <td><?= $customer3['frecRelat']?></td>
                        <td><?= $customer3['frecAcum']?></td>
                        <td><?= $customer3['marcaClase']?></td>
                        <td><?= $customer3['frecComp']?></td>
                        <td><?= $customer3['limInfExact']?></td>
                        <td><?= $customer3['limSupExact']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        
        <div class="titulosDatosPoblacion">
            <h5 class="datosTitle">DATOS</h5>
            <h5 class="datosTitle">MEDIDAS DE DISPERSIÓN</h5>
        </div>

        <div class="medidasPoblacion">
            <div class="datosPoblacion">
                <div><h7>ORDENADOS =</h7> <?php foreach($datosArray as $dato) { echo $dato.', '; }?></div>
                <div><h7>N =</h7>  <?php echo $N?></div>
                <div><h7>K =</h7> <?php echo $K?></div>
                <div><h7>A =</h7> <?php echo $A?></div>
                <div><h7>R =</h7> <?php echo $R?></div>
            </div>

            <div class="datosPoblacion">
                <div><h7>MEDIA =</h7> <?php echo $mediaNoA?></div>
                <div><h7>DESVIACIÓN MEDIA =</h7> <?php echo $desvMedia?></div>
                <div><h7>VARIANZA =</h7> <?php echo $varianzaPoblacion?></div>
                <div><h7>DESVIACIÓN ESTÁNDAR =</h7> <?php echo $desvEstandarPoblacion?></div>
            </div>
        </div>

        <div class="caja">
            
            <div id="tableData" class="tableData" style="height: 600px; width: 200px; overflow-y: auto;">
                <table id="tablita" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>HUMEDAD SUELO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers2 as $customer2): ?>
                        <tr>
                            <td><?= $customer2['humF']?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

            <div class="datosMuestra" style="height: 600px; width: 350px; overflow-y: auto; text-align: center;">
                <div class="texto">
                    <h5 class="datosTitle">DATOS MUESTRA HUMEDAD SUELO</h5>
                    <br>
                    <h6>N =  <?php echo $N2?></h6>
                    <h6>MEDIA =  <?php echo $mediaNoA2?></h6>
                    <h6>VARIANZA =  <?php echo $varianzaMuestra2?></h6>
                    <h6>DESVIACIÓN ESTÁNDAR =  <?php echo $desvEstandarMuestra2?></h6>
                    <br>
                    <h5 class="datosTitle">PRUEBA DE HIPOTESIS</h5>
                    <br>
                    <h6>PLANTEAMIENTO:<br><br> Un agricultor afirma que, al monitorear y recabar información sobre la humedad de suelo de una planta, está es en promedio de <?php echo $mediaNoA; ?>. 
                    Suponga que se toma una muestra aleatoria de 40 datos para probar si la humedad de suelo coincide con lo anterior.</h6>
                    <h6>La humedad de suelo en promedio de la muestra fue de <?php echo $mediaNoA2 ?> con una desviación estándar de <?php echo $desvEstandarMuestra2; ?>.</h6>
                    <h6>Pruebe una hipótesis nula de que la humedad de suelo promedio es de <?php echo $mediaNoA; ?>, contra la hipótesis alternativa de que el promedio no es de <?php echo $mediaNoA; ?>. Utilice un nivel de significancia de 0.5 (5%).</h6>
                    <h6>Hipótesis nula(Ho): La humedad de suelo promedio es de <?php echo $mediaNoA; ?></h6>
                    <h6>Hipótesis alternativa(H1): La media de la muestra nos dice que la humedad de suelo promedio es <?php echo $auxH; ?> a lo mencionado antes.</h6>
                    <h6>Ho: µ = <?php echo $mediaNoA; ?></h6>
                    <h6>H1: µ <?php echo $auxHS; ?> <?php echo $mediaNoA; ?></h6>
                    <h6>Nivel de significancia = α -> 0.05</h6>
                    <h6>Nivel de confianza = 1 - α -> 0.95</h6>
                    <img src="src/grafica_1.png" alt="gráfica" style="margin-bottom: 10px; height: 300px; width: 500px;">
                    <img src="src/grafica_2.png" alt="gráfica2" style="margin-bottom: 10px; height: 300px; width: 500px;">
                    <h6>Zc = <?php echo $zCalculada ?></h6>
                    <h6>Con el valor de <?php echo $zCalculada; ?> localizado dentro de la zona de <?php echo $auxZ; ?> es decir la hipótesis <?php echo $auxR; ?> podemos concluir que lo que menciona el agricultor es <?php echo $auxB; ?>.</h6>
                    <br>
                    <h6>Conclusion:</h6>
                    <h6><?php echo $auxC; ?></h6>
                    <br>
                </div>
            </div>

        </div>

        
        <div class="botones">
            <button type="button" onclick="location.href = 'probabilidad.php';" id="probTemp-btn">Temperatura</button>
            <button type="button" onclick="location.href = 'probHum.php';" id="probHum-btn">Humedad</button>
            <button type="button" onclick="location.href = 'probHumF.php';" id="probHumF-btn">Humedad Suelo</button>
        </div>
    </div>
</body>