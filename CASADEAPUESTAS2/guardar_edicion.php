<?php
session_start();
date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'], $_POST['nuevo_monto'], $_POST['nuevo_marcador'])) {
    
    $index = $_POST['index'];
    $nuevo_monto = intval($_POST['nuevo_monto']);
    $nuevo_marcador = trim($_POST['nuevo_marcador']);

    $montos_permitidos = [10000, 20000, 30000, 40000, 50000, 100000, 200000];

    if (!in_array($nuevo_monto, $montos_permitidos)) {
        echo "<p>Error: Selecciona un monto válido.</p>";
        exit();
    }

    if (!isset($_SESSION['apuestashechas'][$index])) {
        echo "<p>Error: Apuesta no encontrada.</p>";
        exit();
    }

    // Validación simple del marcador (formato x-y)
    if (!preg_match('/^\d{1,2}-\d{1,2}$/', $nuevo_marcador)) {
        echo "<p>Error: Formato de marcador no válido. Usa el formato x-y (ej: 2-1).</p>";
        exit();
    }

    // Obtener los datos actuales de la apuesta
    $apuesta = $_SESSION['apuestashechas'][$index];
    $id_apuesta = intval($apuesta['id']);

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    // 1. Actualizar la sesión
    $_SESSION['apuestashechas'][$index]['monto'] = $nuevo_monto;
    $_SESSION['apuestashechas'][$index]['marcador'] = $nuevo_marcador;
    $_SESSION['apuestashechas'][$index]['fecha'] = $fecha;
    $_SESSION['apuestashechas'][$index]['hora'] = $hora;

    // 2. Actualizar en la base de datos
    $conexion = new mysqli("localhost", "root", "", "login_register_db");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $sql = "UPDATE apuestas SET monto = ?, marcador = ?, fecha = ?, hora = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        // Enlazar parámetros
        $stmt->bind_param("isssi", $nuevo_monto, $nuevo_marcador, $fecha, $hora, $id_apuesta);

        if ($stmt->execute()) {
            header("Location: apuestashechas.php?editado=1");
            exit();
        } else {
            echo "<p>Error al actualizar la base de datos: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error en la preparación de la consulta: " . $conexion->error . "</p>";
    }

    $conexion->close();
} else {
    echo "<p>Error: Acceso no permitido.</p>";
    exit();
}
?>
