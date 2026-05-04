<?php
include("conexion.php");

// 📥 DATOS
$par = $_POST['par'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$entrada = floatval($_POST['entrada'] ?? 0);
$tp = floatval($_POST['tp'] ?? 0);
$sl = floatval($_POST['sl'] ?? 0);
$cierre = floatval($_POST['cierre'] ?? 0);

$volumen = floatval($_POST['volumen'] ?? 0);
$apalancamiento = floatval($_POST['apalancamiento'] ?? 0);

$pips_tp = floatval($_POST['pips_tp'] ?? 0);
$pips_sl = floatval($_POST['pips_sl'] ?? 0);

$ganancia_estimada = floatval($_POST['ganancia_estimada'] ?? 0);

$riesgo = floatval($_POST['riesgo'] ?? 0);
$estado = $_POST['estado'] ?? 'pendiente';

// 🚨 VALIDACIÓN
if($par == "" || $entrada == 0){
    echo "ERROR: datos vacíos";
    exit;
}

// 🔥 CALCULAR RESULTADO REAL AUTOMÁTICO
$resultado_real = 0;

if($estado == "ejecutado" && $cierre > 0){

    // 📊 determinar tamaño pip
    $pip = (strpos($par, "JPY") !== false) ? 0.01 : 0.0001;

    // 📊 valor del pip
    $valorPip = ($volumen * $pip) / $entrada;

    // 📊 calcular pips reales según tipo
    if($tipo == "buy"){
        $pips = ($cierre - $entrada) / $pip;
    } else {
        $pips = ($entrada - $cierre) / $pip;
    }

    // 💰 resultado final
    $resultado_real = $pips * $valorPip;
}

// 🧠 SQL
$sql = "INSERT INTO trades (
    par, tipo, entrada, tp, sl, cierre,
    volumen, apalancamiento,
    pips_tp, pips_sl,
    ganancia_estimada, resultado_real,
    riesgo, estado, fecha
) VALUES (
    '$par', '$tipo', $entrada, $tp, $sl, $cierre,
    $volumen, $apalancamiento,
    $pips_tp, $pips_sl,
    $ganancia_estimada, $resultado_real,
    $riesgo, '$estado', NOW()
)";

// 🚀 EJECUTAR
if(mysqli_query($conexion, $sql)){
    echo "ok";
}else{
    echo "Error SQL: " . mysqli_error($conexion);
}
?>