<?php
session_start();
$usuarioActivo = isset($_SESSION['usuario']);
$nombreUsuario = $usuarioActivo ? $_SESSION['usuario'] : 'Invitado';
$idUsuario = $_SESSION['id'] ?? null; // ✅ Variable correctamente definida


if (!$usuarioActivo || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

include 'php/conexion_be.php';
$conexion = mysqli_connect("localhost", "root", "", "login_register_db");

date_default_timezone_set('America/Bogota');

// Total apostado
$queryApuestas = "SELECT SUM(monto) AS total_apostado FROM apuestas";
$resultadoApuestas = mysqli_query($conexion, $queryApuestas);
$datosApuestas = mysqli_fetch_assoc($resultadoApuestas);
$totalApostado = $datosApuestas['total_apostado'] ?? 0;

// Total ganado por usuarios
$queryGanadoUsuarios = "SELECT SUM(total_ganado) AS total_ganado FROM totales_usuarios";
$resultadoGanadoUsuarios = mysqli_query($conexion, $queryGanadoUsuarios);
$datosGanadoUsuarios = mysqli_fetch_assoc($resultadoGanadoUsuarios);
$totalGanadoUsuarios = $datosGanadoUsuarios['total_ganado'] ?? 0;

// Total perdido por los usuarios
$queryPerdidas = "SELECT SUM(monto) AS total_perdido FROM apuestas WHERE estado = 'perdio'";
$resultadoPerdidas = mysqli_query($conexion, $queryPerdidas);
$datosPerdidas = mysqli_fetch_assoc($resultadoPerdidas);
$totalPerdido = $datosPerdidas['total_perdido'] ?? 0;

// Comisión del 15%
$comisionPorcentaje = 0.15;
$queryComision = "SELECT SUM((monto * cuota - monto) * $comisionPorcentaje) AS comision_total FROM apuestas WHERE estado = 'gano'";
$resultadoComision = mysqli_query($conexion, $queryComision);
$datosComision = mysqli_fetch_assoc($resultadoComision);
$comisionGanadores = $datosComision['comision_total'] ?? 0;

// Ganancia neta = pérdidas + comisión
$gananciasNetasPlataforma = $totalPerdido + $comisionGanadores;

// Insertar en historial
$fechaActual = date('Y-m-d');
$queryInsertarGanancias = "INSERT INTO ganancias_diarias (fecha, total_apostado, total_ganado, comision, ganancias_netas) 
                           VALUES ('$fechaActual', '$totalApostado', '$totalGanadoUsuarios', '$comisionGanadores', '$gananciasNetasPlataforma')";
mysqli_query($conexion, $queryInsertarGanancias);

// Historial de ganancias
$queryHistorialGanancias = "SELECT * FROM ganancias_diarias ORDER BY fecha DESC";
$resultadoHistorial = mysqli_query($conexion, $queryHistorialGanancias);

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganancias de la Plataforma - ApuestaMax</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/estadistica.css">
</head>
<body>
<header>
    <h1>Ganancias de la Plataforma</h1>
       <nav>
            <ul>
                <li><a href="admin.php">👨‍💼 Panel Administrativo</a></li>
                <li><a href="ver_apuestas.php">👁️ Ver Todas las Apuestas</a></li>
                <li><a href="estadisticas_apuestas.php">💰 Ganancias de la Plataforma</a></li>
                 <li><a href="ver_usuarios.php">👥 Ver Usuarios</a></li>
                <div class="usuario">
                    👤 <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>
                    <a href="logout.php" style="margin-left: 10px; color: red;">Cerrar sesión</a>
                </div>
            </ul>
        </nav>
</header>

<main>
    <section class="ganancias">
        <h2>💰 Resumen de Ganancias de la Plataforma</h2>
        <div class="tabla-container">
            <table class="tabla-apuestas">
                <thead>
                    <tr>
                        <th>Total Apostado</th>
                        <th>Total Ganado por Usuarios</th>
                        <th>Comisión Cobrada</th>
                        <th>Ganancias Netas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$<?php echo number_format($totalApostado, 2); ?></td>
                        <td>$<?php echo number_format($totalGanadoUsuarios, 2); ?></td>
                        <td>$<?php echo number_format($comisionGanadores, 2); ?></td>
                        <td>$<?php echo number_format($gananciasNetasPlataforma, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>
            Las <strong>ganancias netas</strong> incluyen lo que los usuarios perdieron en apuestas más una comisión del <strong>15%</strong> sobre las ganancias netas de los ganadores.
        </p>
    </section>

    <section class="historial">
        <h2>📅 Historial de Ganancias</h2>
        <div class="tabla-container">
            <table class="tabla-historial">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total Apostado</th>
                        <th>Total Ganado</th>
                        <th>Comisión</th>
                        <th>Ganancias Netas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($registro = mysqli_fetch_assoc($resultadoHistorial)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['fecha']); ?></td>
                            <td>$<?php echo number_format($registro['total_apostado'], 2); ?></td>
                            <td>$<?php echo number_format($registro['total_ganado'], 2); ?></td>
                            <td>$<?php echo number_format($registro['comision'], 2); ?></td>
                            <td>$<?php echo number_format($registro['ganancias_netas'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="js/admin.js"></script>
</body>
</html>



