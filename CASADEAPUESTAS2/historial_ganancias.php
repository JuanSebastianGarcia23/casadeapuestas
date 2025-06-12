<?php
session_start();
date_default_timezone_set('America/Bogota');

include 'php/conexion_be.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];
$nombreUsuario = $_SESSION['usuario'] ?? 'Invitado';

// Obtener total_ganado actual del usuario desde la tabla totales_usuarios
$query = "SELECT total_ganado FROM totales_usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$datosUsuario = $resultado->fetch_assoc();
$totalGanado = $datosUsuario['total_ganado'] ?? 0.00;

// Insertar registro en historial_ganancias (sin hora)
$fecha = date('Y-m-d');
$insertar = $conexion->prepare("INSERT INTO historial_ganancias (id_usuario, fecha, total_ganado) VALUES (?, ?, ?)");
$insertar->bind_param("isd", $idUsuario, $fecha, $totalGanado);
$insertar->execute();

// Obtener historial del usuario
$consultaHistorial = "SELECT fecha, total_ganado FROM historial_ganancias WHERE id_usuario = ? ORDER BY fecha DESC";
$stmtHistorial = $conexion->prepare($consultaHistorial);
$stmtHistorial->bind_param("i", $idUsuario);
$stmtHistorial->execute();
$resultadoHistorial = $stmtHistorial->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ganancias - ApuestaMax</title>
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="css/historial.css">
</head>
<body>
    <header>
        <div class="logo">🔥 ApuestaMax</div>
        <nav>
            <ul>
                <li><a href="inicio.php">🎰 Inicio</a></li>
                <li><a href="apuestashechas.php">💸 Apuestas Hechas</a></li>
                <li><a href="historial_ganancias.php">📈 Historial de Ganancias</a></li>
            </ul>
        </nav>
        <div class="usuario">
            👤 <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>
            <a href="logout.php" style="margin-left: 10px; color: red;">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenedor">
        <section class="historial">
            <h2>📅 Historial de Ganancias</h2>
            <div class="tabla-container">
                <table class="tabla-historial">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total Ganado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($registro = $resultadoHistorial->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['fecha']); ?></td>
                                <td>$<?php echo number_format($registro['total_ganado'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
