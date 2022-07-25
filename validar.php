<?php
include('conexion.php');

if(isset($_POST['user'])){
  $user = $_POST['user']; 
}else{
  $user = '';
}
if(isset($_POST['pass'])){
  $pass = $_POST['pass']; 
}else{
  $pass = '';
}

$conn = new conexion();


$consulta="SELECT*FROM usuarios where user='$user' and pass='$pass'";
$resultado = mysqli_query($conn->conectarDb(),$consulta);

$filas=mysqli_num_rows($resultado);

if($filas){
    include("login.php");
    ?>
    <h4 class="ok">BIENVENIDO</h4>
    <?php
    session_start();
    $_SESSION['user'] = $user;
    echo '<script>setTimeout(function(){window.location.href = 
      "index.php"}, 2 * 1000);</script>';

}else{
    session_start();
    session_destroy();
    include("login.php");
    ?>
    <h4 class="bad">ERROR DE AUTENTIFICACION</h4>
    <?php
    echo '<script>setTimeout(function(){window.location.href = 
      "login.php"}, 2 * 1000);</script>';
}
mysqli_free_result($resultado);
mysqli_close($resultado);
?>