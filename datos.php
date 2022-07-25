<?php

require_once('conexion.php');

if(isset($_POST['temp'])){
    $temp = $_POST['temp']; 
}else{
    $temp = '';
}
if(isset($_POST['hum'])){
    $hum = $_POST['hum']; 
}else{
    $hum = '';
}
if(isset($_POST['humF'])){
    $humF = $_POST['humF']; 
}else{
    $humF = '';
}
if(isset($_POST['waterLvl'])){
    $waterLvl = $_POST['waterLvl']; 
}else{
    $waterLvl = '';
}
if(isset($_POST['regado'])){
    $regado = $_POST['regado']; 
}else{
    $regado = '';
}

$conn = new conexion();

$query = "UPDATE datosPlanta SET `id`=1,`temp`='$temp',`hum`='$hum',`humFloor`='$humF',`waterLvl`='$waterLvl' WHERE id=1";
$update = mysqli_query($conn->conectarDb(),$query);

$query = "INSERT INTO historial(`temp`, `hum`, `humF`, `waterLvl`, `regado`) VALUES ('$temp','$hum','$humF','$waterLvl','$regado')";
$insert = mysqli_query($conn->conectarDb(),$query);

echo "{Temperatura: ".$temp.", Humedad: ".$hum.", Humedad de suelo: ".$humF.", Nivel de agua: ".$waterLvl."}";

?>