<?php

require_once('conexion.php');
$conn = new conexion();
$datos = "SELECT * from datosPlanta";

$resultado = mysqli_query($conn->conectarDb(),$datos);
while($row=mysqli_fetch_assoc($resultado)) {
    ?>
    <h2>Temperatura:</h2><?php echo $row["temp"]?>°C
    <?php
}

?>