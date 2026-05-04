<?php
header('Content-Type: application/json');
include("conexion.php");

$sql = "SELECT * FROM trades ORDER BY id DESC";
$result = mysqli_query($conexion, $sql);

$datos = [];

while ($fila = mysqli_fetch_assoc($result)) {

    $entrada = floatval($fila["entrada"]);
    $cierre  = floatval($fila["cierre"]);
    $tipo    = $fila["tipo"];
    $par     = $fila["par"];

    $volumen = floatval($fila["volumen"]);
    $riesgo  = floatval($fila["riesgo"] ?? 1);
    $apalancamiento = intval($fila["apalancamiento"]);

    // 📌 pip size por activo
    if (strpos($par, "XAU") !== false || strpos($par, "GOLD") !== false) {
        $pip = 0.1; // GOLD
    } elseif (strpos($par, "JPY") !== false) {
        $pip = 0.01;
    } else {
        $pip = 0.0001;
    }

    // 🧠 PIPS
    if ($cierre > 0) {
        $pips = ($tipo == "buy")
            ? ($cierre - $entrada) / $pip
            : ($entrada - $cierre) / $pip;
    } else {
        $pips = 0;
    }

    // 🔥 redondeo tipo broker (clave para coincidir IQ Option)
    $pips = round($pips, 2);

    // 💰 valor pip (modelo estable tipo calculadora)
    $valor_pip = round(($volumen * $pip) / $entrada, 6);

    // 💰 resultado final replicador
    $resultado_real = round($pips * $valor_pip * $riesgo, 2);

    // 📊 estado automático
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

        "volumen" => $volumen,
        "riesgo" => $riesgo,
        "apalancamiento" => $apalancamiento,

        "estado" => $estado
    ];
}

echo json_encode($datos);
?>