<?php
session_start();

// Inicializar el total ganado
$totalGanado = 0;

if (isset($_SESSION["apuestashechas"]) && !empty($_SESSION["apuestashechas"])) {
    foreach ($_SESSION["apuestashechas"] as $apuesta) {
        $comisionPorcentaje = 0.15;
        $montoGanado = 0;

        if (isset($apuesta["resultado_partido"]) && $apuesta["resultado_partido"] !== '—' && isset($apuesta["marcador"])) {
            if ($apuesta["resultado_partido"] == $apuesta["marcador"]) {
                $monto = $apuesta["monto"];
                $ganancia = $monto; // gana el 100% adicional
                $comision = $ganancia * $comisionPorcentaje;
                $gananciaNeta = $ganancia - $comision;
                $montoGanado = $monto + $gananciaNeta; // se le devuelve su apuesta + ganancia neta

                $totalGanado += $montoGanado;
            }
        }
    }
}

echo $totalGanado;
?>


