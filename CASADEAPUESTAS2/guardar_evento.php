<?php
session_start();

// Verificar que sea un admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo "No autorizado.";
    exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "login_register_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir los datos del POST
$id_torneo = $_POST["id_torneo"] ?? null;
$equipo_local = $_POST["equipo_local"] ?? null;
$equipo_visitante = $_POST["equipo_visitante"] ?? null;
$premio_1 = $_POST["premio_1"] ?? null;
$premio_2 = $_POST["premio_2"] ?? null;
$premio_3 = $_POST["premio_3"] ?? null;

// Validar que todos los datos existan
if (!$id_torneo || !$equipo_local || !$equipo_visitante || !$premio_1 || !$premio_2 || !$premio_3) {
    echo "Faltan datos para guardar el evento.";
    exit();
}

// Insertar el evento
$sql = "INSERT INTO eventos (id_torneo, equipo_local, equipo_visitante, premio_1, premio_2, premio_3) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmt->bind_param("isssss", $id_torneo, $equipo_local, $equipo_visitante, $premio_1, $premio_2, $premio_3);

if ($stmt->execute()) {
    echo "Evento guardado correctamente.";
} else {
    echo "Error al guardar el evento: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
