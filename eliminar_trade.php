<?php
include("conexion.php");

$id = $_POST['id'] ?? 0;

if($id == 0){
    echo "error id";
    exit;
}

$sql = "DELETE FROM trades WHERE id = $id";

if(mysqli_query($conexion, $sql)){
    echo "ok";
}else{
    echo "error: " . mysqli_error($conexion);
}
?>