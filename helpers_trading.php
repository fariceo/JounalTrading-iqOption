<?php

function calcularTrade($entrada, $cierre, $tipo, $par, $volumen, $riesgo = 1)
{
    if ($cierre <= 0) return 0;

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

    return $pips * $valor_pip * $riesgo;
}