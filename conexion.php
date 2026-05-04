<?php
$conexion = mysqli_connect("localhost","root","clave","trading_opciones");

if(!$conexion){
    die("Error de conexión: " . mysqli_connect_error());
}
?>