<?php
header('Content-Type: application/json');
include("conexion.php");

$sql = "SELECT * FROM trades WHERE cierre > 0";
$result = mysqli_query($conexion, $sql);

$total = 0;
$wins = 0;
$profit = 0;

while ($fila = mysqli_fetch_assoc($result)) {

    $entrada = floatval($fila["entrada"]);
    $cierre  = floatval($fila["cierre"]);
    $tipo    = $fila["tipo"];
    $par     = $fila["par"];
    $volumen = floatval($fila["volumen"]);
    $riesgo  = floatval($fila["riesgo"] ?? 1);

    // 🔥 MISMA LÓGICA EXACTA QUE obtener_trades.php
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

    $profit += $resultado_real;

    if ($resultado_real > 0) $wins++;
    $total++;
}

echo json_encode([
    "total" => $total,
    "wins" => $wins,
    "profit" => round($profit, 2)
]);