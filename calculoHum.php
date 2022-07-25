<?php
    require_once('conexion.php');

    $conn = new conexion();

    $datosArray = array();

    $arrayClases = array();
    $limInfArray = array();
    $limSupArray = array();
    $limSupExactArray = array();
    $limInfExactArray = array();
    $marcaClaseArray = array();
    $frecuenciasArray = array();
    $frecuenciaRelativaArray = array();
    $frecuenciaAcumArray = array();
    $frecuenciaCompArray = array();


	$result = mysqli_query($conn->conectarDb(),"SELECT `hum` FROM muestras");
	$hums = $result->fetch_all(MYSQLI_ASSOC);


    foreach($hums as $hum):
        array_push($datosArray, $hum['hum']);
    endforeach;

    foreach( range('A', 'Z') as $letra ) {
        array_push($arrayClases, $letra);
    }

    asort($datosArray);

    $UV = 1;
    // $UV = 0.1;

    $N = count($datosArray);

    $K = log($N, 2) + 1;

    $K = round($K,0,PHP_ROUND_HALF_DOWN);
    
    $primerValor = $datosArray[array_key_first($datosArray)];

    $ultimoValor = end($datosArray);

    $R = $ultimoValor - $primerValor;

    $A = ($R / $K) + $UV;

    array_push($limInfArray, $primerValor);

    $aux = $primerValor;

    for ($i = 0; $i < $K-1; $i++) {
        $aux = $aux + $A;
        array_push($limInfArray, $aux);
    }

    for ($i = 0; $i < $K-1; $i++) {
        $aux = $limInfArray[$i + 1] - $UV;
        array_push($limSupArray, $aux);
    }

    $ultimoValorLimInf = end($limInfArray);

    array_push($limSupArray, $ultimoValorLimInf + $A - $UV);
    
    for ($i = 0; $i < $K; $i++) {
        $aux = $limInfArray[$i] - ($UV / 2);
        array_push($limInfExactArray, $aux);
    }

    for ($i = 0; $i < $K; $i++) {
        $aux = $limSupArray[$i] + ($UV / 2);
        array_push($limSupExactArray, $aux);
    }

    for ($i = 0; $i < count($limInfArray); $i++) {
        $aux = ($limInfArray[$i] + $limSupArray[$i]) / 2;
        array_push($marcaClaseArray, $aux);
    }

    $aux = 0;

    for ($i = 0; $i < count($limInfArray); $i++) {
        for ($j = 0; $j < count($datosArray); $j++) {
            if (($datosArray[$j] >= $limInfArray[$i]) && ($datosArray[$j] <= $limSupArray[$i])) {
                $aux++;
            }
        }
        array_push($frecuenciasArray, $aux);
        $aux = 0;
    }

    $aux2 = 0;

    for ($i = 0; $i < count($frecuenciasArray); $i++) {
        $aux = $frecuenciasArray[$i] / count($datosArray);
        array_push($frecuenciaRelativaArray, $aux);
        $aux2 = $aux2 + $aux;
    }
    array_push($frecuenciaRelativaArray, $aux2);

    array_push($frecuenciaAcumArray, reset($frecuenciasArray));
    for ($i = 1; $i < count($frecuenciasArray); $i++) {
        $aux = $frecuenciaAcumArray[$i - 1] + $frecuenciasArray[$i];
        array_push($frecuenciaAcumArray, $aux);
    }

    $aux = end($frecuenciaAcumArray);
    for ($i = 0; $i < count($frecuenciaAcumArray); $i++) {
        $aux = $aux - $frecuenciasArray[$i];
        array_push($frecuenciaCompArray, $aux);
    }

    // MEDIA DE TABLA DE POBLACION (200 datos)

    $aux = 0;
    for ($i = 0; $i < count($datosArray); $i++) {
        $aux = $aux + $datosArray[$i];
    }
    $mediaNoA = $aux / count($datosArray);

    // DESVIACION MEDIA PARA AMBAS TABLAS

    $aux = 0;
    for ($i = 0; $i < count($datosArray); $i++) {
        $aux = $aux + abs($datosArray[$i] - $mediaNoA);
    }
    $desvMedia = $aux / count($datosArray);

    // VARIANZA DE POBLACION

    $aux = 0;
    for ($i = 0; $i < count($datosArray); $i++) {
        $aux = $aux + pow($datosArray[$i] - $mediaNoA,2);
    }
    $varianzaPoblacion = $aux / count($datosArray);

    // VARIANZA DE MUESTRA

    $varianzaMuestra = $aux / (count($datosArray) - 1);

    // DESVIACION ESTÁNDAR POBLACIÓN

    $desvEstandarPoblacion = pow($varianzaPoblacion,1 / 2);

    // DESVIACION ESTANDAR MUESTRA

    $desvEstandarMuestra = pow($varianzaMuestra,1 / 2);

    // INSERTAR DATOS EN TABLA

    for ($i = 0; $i < $K; $i++) {

        $class = $arrayClases[$i];
        $limInf = $limInfArray[$i];
        $limSup = $limSupArray[$i];
        $frec = $frecuenciasArray[$i];
        $frecRelat = $frecuenciaRelativaArray[$i];
        $frecAcum = $frecuenciaAcumArray[$i];
        $marcaClase = $marcaClaseArray[$i];
        $frecComp = $frecuenciaCompArray[$i];
        $limInfExact = $limInfExactArray[$i];
        $limSupExact = $limSupExactArray[$i];

        $result = "INSERT INTO `clasesHum`(`class`, `limInf`, `limSup`, `frec`, `frecRelat`, `frecAcum`, `marcaClase`, `frecComp`, `limInfExact`, `limSupExact`) VALUES ('$class','$limInf','$limSup','$frec','$frecRelat','$frecAcum','$marcaClase','$frecComp','$limInfExact','$limSupExact')";
        $insert = mysqli_query($conn->conectarDb(),$result);
    }
   

?>