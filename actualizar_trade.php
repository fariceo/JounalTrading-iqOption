<?php
header('Content-Type: application/json');
include("conexion.php");

$id = $_POST['id'] ?? null;

if(!$id){
    echo json_encode(["error" => "ID requerido"]);
    exit;
}

$entrada = $_POST['entrada'] ?? null;
$cierre = $_POST['cierre'] ?? null;
$tp = $_POST['tp'] ?? null;
$sl = $_POST['sl'] ?? null;
$estado = $_POST['estado'] ?? null;

// Construir query dinámico (solo actualiza lo que envías)
$campos = [];

if($entrada !== null) $campos[] = "entrada = " . floatval($entrada);
if($cierre !== null) $campos[] = "cierre = " . floatval($cierre);
if($tp !== null) $campos[] = "tp = " . floatval($tp);
if($sl !== null) $campos[] = "sl = " . floatval($sl);
if($estado !== null) $campos[] = "estado = '" . mysqli_real_escape_string($conexion, $estado) . "'";

if(empty($campos)){
    echo json_encode(["error" => "No hay datos para actualizar"]);
    exit;
}

$sql = "UPDATE trades SET " . implode(", ", $campos) . " WHERE id = " . intval($id);

if(mysqli_query($conexion, $sql)){
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => mysqli_error($conexion)]);
}


if($cierre !== null){
    $cierre_val = floatval($cierre);

    $campos[] = "cierre = $cierre_val";

    // 🔥 estado automático
    if($cierre_val > 0){
        $campos[] = "estado = 'cerrado'";
    } else {
        $campos[] = "estado = 'pendiente'";
    }
}
?>