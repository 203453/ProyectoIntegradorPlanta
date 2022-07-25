<?php

    require_once('conexion.php');

    $conn = new conexion();

    $datosArray2 = array();

    $result = mysqli_query($conn->conectarDb(),"SELECT `temp` FROM muestraTemp");
	$temps = $result->fetch_all(MYSQLI_ASSOC);


    foreach($temps as $temp):
        array_push($datosArray2, $temp['temp']);
    endforeach;

    asort($datosArray2);

    $UV = 0.1;
    // $UV = 0.1;

    $N2 = count($datosArray2);

    $K = log($N2, 2) + 1;

    $K = round($K,0,PHP_ROUND_HALF_DOWN);
    
    $primerValor = $datosArray2[array_key_first($datosArray2)];

    $ultimoValor = end($datosArray2);

    $R = $ultimoValor - $primerValor;

    $A = ($R / $K) + $UV;

    // MEDIA DE TABLA DE POBLACION (200 datos)

    $aux = 0;
    for ($i = 0; $i < count($datosArray2); $i++) {
        $aux = $aux + $datosArray2[$i];
    }
    $mediaNoA2 = $aux / count($datosArray2);

    // DESVIACION MEDIA PARA AMBAS TABLAS

    $aux = 0;
    for ($i = 0; $i < count($datosArray2); $i++) {
        $aux = $aux + abs($datosArray2[$i] - $mediaNoA2);
    }
    $desvMedia = $aux / count($datosArray2);

    // VARIANZA DE POBLACION

    $aux = 0;
    for ($i = 0; $i < count($datosArray2); $i++) {
        $aux = $aux + pow($datosArray2[$i] - $mediaNoA2,2);
    }
    $varianzaPoblacion = $aux / count($datosArray2);

    // VARIANZA DE MUESTRA

    $varianzaMuestra2 = $aux / (count($datosArray2) - 1);

    // DESVIACION ESTÁNDAR POBLACIÓN

    $desvEstandarPoblacion = pow($varianzaPoblacion,1 / 2);

    // DESVIACION ESTANDAR MUESTRA

    $desvEstandarMuestra2 = pow($varianzaMuestra,1 / 2);
    
?>