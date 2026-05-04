<?php
header('Content-Type: application/json');
include("conexion.php");

$sql = "SELECT * FROM trades WHERE cierre > 0 ORDER BY fecha ASC";
$result = mysqli_query($conexion, $sql);

$data = [];
$balance = 0;

while ($fila = mysqli_fetch_assoc($result)) {

    $entrada = floatval($fila["entrada"]);
    $cierre  = floatval($fila["cierre"]);
    $tipo    = $fila["tipo"];
    $par     = $fila["par"];
    $volumen = floatval($fila["volumen"]);
    $riesgo  = floatval($fila["riesgo"] ?? 1);

    // 🔥 MISMA LÓGICA EXACTA
    if (strpos($par, "XAU") !== false || strpos($par, "GOLD") !== false) {
        $pip = 0.1;
    } elseif (strpos($par, "JPY") !== false) {
        $pip = 0.01;
    } else {
        $pip = 0.0001;
    }

    $pips = ($tipo == "buy")
        ? ($cierre - $entrada) / $pip
        : ($entrada - $cierre) / $pip;

    $valor_pip = ($volumen * $pip) / $entrada;

    $resultado_real = $pips * $valor_pip * $riesgo;

    $balance += $resultado_real;

    $data[] = [
        "fecha" => $fila["fecha"],
        "balance" => round($balance, 2),
        "resultado" => round($resultado_real, 2)
    ];
}

echo json_encode($data);