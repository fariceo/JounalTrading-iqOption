<?php
header('Content-Type: application/json');
include("conexion.php");

$inicio = $_GET['inicio'] ?? null;
$fin = $_GET['fin'] ?? null;

// 🔥 filtro dinámico
if ($inicio && $fin) {
    $sql = "SELECT * FROM trades 
            WHERE DATE(fecha) BETWEEN '$inicio' AND '$fin'
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM trades ORDER BY id DESC";
}

$result = mysqli_query($conexion, $sql);

$datos = [];

while ($fila = mysqli_fetch_assoc($result)) {

    $entrada = floatval($fila["entrada"]);
    $cierre  = floatval($fila["cierre"]);
    $tipo    = $fila["tipo"];
    $par     = $fila["par"];

    $volumen = floatval($fila["volumen"]);
    $riesgo  = floatval($fila["riesgo"] ?? 1);

    // pip dinámico
    if (strpos($par, "XAU") !== false) {
        $pip = 0.1;
    } elseif (strpos($par, "JPY") !== false) {
        $pip = 0.01;
    } else {
        $pip = 0.0001;
    }

    // pips
    if ($cierre > 0) {
        $pips = ($tipo == "buy")
            ? ($cierre - $entrada) / $pip
            : ($entrada - $cierre) / $pip;
    } else {
        $pips = 0;
    }

    $pips = round($pips, 2);

    $valor_pip = ($volumen * $pip) / $entrada;
    $resultado_real = round($pips * $valor_pip * $riesgo, 2);

    $estado = ($cierre > 0) ? "cerrado" : "pendiente";

    $datos[] = [
        "id" => intval($fila["id"]),
        "par" => $par,
        "entrada" => $entrada,
        "tp" => floatval($fila["tp"]),
        "sl" => floatval($fila["sl"]),
        "cierre" => $cierre,
        "pips" => $pips,
        "resultado_real" => $resultado_real,
        "estado" => $estado
    ];
}

echo json_encode($datos);
?>