<?php
session_start();
date_default_timezone_set('America/Bogota');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include 'conexion_be.php';
    $conexion = mysqli_connect("localhost", "root", "", "login_register_db");

    $evento = $_POST['evento'] ?? '';
    $monto = floatval($_POST['monto'] ?? 0);
    $cuota = floatval($_POST['cuota'] ?? 1.0);
    $marcador = $_POST['marcador'] ?? '';
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    if (empty($evento) || $monto <= 0 || $cuota < 1.0) {
        die("⚠️ Evento vacío, monto inválido o cuota inválida.");
    }

    $id_usuario = $_SESSION['id_usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $estado = '—';  // ✅ Estado inicial explícito

    // Insertar en la base de datos
    $stmt = mysqli_prepare($conexion, "INSERT INTO apuestas (id_usuario, nombre_usuario, evento, monto, cuota, marcador, fecha, hora, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issddssss", $id_usuario, $nombre_usuario, $evento, $monto, $cuota, $marcador, $fecha, $hora, $estado);

    if (mysqli_stmt_execute($stmt)) {
        $id_apuesta = mysqli_insert_id($conexion);

        // Guardar también en la sesión
        $apuesta = [
            'id' => $id_apuesta,
            'evento' => $evento,
            'monto' => $monto,
            'cuota' => $cuota,
            'marcador' => $marcador,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => $estado,
            'resultado_partido' => '—'
        ];

        if (!isset($_SESSION['apuestashechas'])) {
            $_SESSION['apuestashechas'] = [];
        }

        $_SESSION['apuestashechas'][] = $apuesta;

        echo "✅ Apuesta guardada con éxito. ID: $id_apuesta";
    } else {
        echo "❌ Error al insertar: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>
