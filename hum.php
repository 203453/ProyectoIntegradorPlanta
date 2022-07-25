<?php

require_once('conexion.php');
$conn = new conexion();
$datos = "SELECT * from datosPlanta";

$resultado = mysqli_query($conn->conectarDb(),$datos);
while($row=mysqli_fetch_assoc($resultado)) {
    ?>
    <h2>Humedad:</h2><?php echo $row["hum"]?>%
    <?php
}

?>