<?php
session_start();
date_default_timezone_set('America/Bogota');
$usuarioActivo = isset($_SESSION['usuario']);
$nombreUsuario = $usuarioActivo ? $_SESSION['usuario'] : 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apuestas Hechas - ApuestaMax</title>
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="css/apuestas.css">
</head>

<body>
    <header>
        <div class="logo">🔥 ApuestaMax</div>
        <nav>
            <ul>
                <li><a href="inicio.php">🎰 Inicio</a></li>
                <li><a href="#">🏅 🇨🇴 Liga Colombiana</a></li>
                <li><a href="#">🏆 UEFA Champions League</a></li>
                <li><a href="#">🌍 Copa Mundial de la FIFA</a></li>
                <li><a href="apuestashechas.php">💸 Apuestas Hechas</a></li>
                <li><a href="historial_ganancias.php">📈 Historial de Ganancias</a></li>
            </ul>
        </nav>

        <div class="usuario">
            👤 <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>
            <a href="logout.php" style="margin-left: 10px; color: red;">Cerrar sesión</a>
        </div>
    </header>

    <main>
        <h2 class="titulo-seccion">💸 APUESTAS REALIZADAS</h2>

        <div class="mensaje-aviso" id="mensajeAviso">
            <span class="cerrar" onclick="cerrarMensaje()">×</span>
            ⚠️ <strong>Señor usuario:</strong> recuerde editar o eliminar sus apuestas <strong>antes de cerrar sesión</strong>.
        </div>

        <?php
        $totalGanado = 0;

        if (isset($_SESSION["apuestashechas"]) && !empty($_SESSION["apuestashechas"])) {
            $apuestasPorEvento = [];

            foreach ($_SESSION["apuestashechas"] as $index => $apuesta) {
                $evento = $apuesta["evento"];
                if (!isset($apuestasPorEvento[$evento])) {
                    $apuestasPorEvento[$evento] = [];
                }
                $apuestasPorEvento[$evento][$index] = $apuesta;
            }

            foreach ($apuestasPorEvento as $evento => $apuestashechas) {
                switch ($evento) {
                    case 'Liga Colombiana':
                        $eventoTitulo = '🇨🇴 Liga Colombiana';
                        break;
                    case 'UEFA Champions League':
                        $eventoTitulo = '🏆 UEFA Champions League';
                        break;
                    case 'Copa Mundial de la FIFA':
                        $eventoTitulo = '🌍 Copa Mundial de la FIFA';
                        break;
                    default:
                        $eventoTitulo = " $evento";
                        break;
                }

                echo "<div class='tabla-container'>";
                echo "<h3 class='titulo-evento'>$eventoTitulo</h3>";
                echo "<table class='tabla-apuestas'>";
                echo "<thead>
                        <tr>
                            <th>Monto Apostado</th>
                            <th>Cuota</th>
                            <th>Marcador</th>
                            <th>Resultado</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Monto Ganado</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                      </thead>
                      <tbody>";

                foreach ($apuestashechas as $index => $apuesta) {
                    $hora_original = $apuesta["hora"];
                    $hora_objeto = DateTime::createFromFormat('H:i:s', $hora_original);
                    $hora_formateada = $hora_objeto ? $hora_objeto->format('h:i A') : "Hora no válida";

                    $fechaHoraApuesta = strtotime($apuesta["fecha"] . " " . $apuesta["hora"]);
                    $inicioSesion = $_SESSION['inicio_sesion'] ?? 0;

                    $comisionPorcentaje = 0.15;
                    $montoGanado = 0;
                    $estadoApuesta = '';
                    $textoEstado = '—';

                    if (isset($apuesta["resultado_partido"]) && $apuesta["resultado_partido"] !== '—') {
                        if ($apuesta["resultado_partido"] == $apuesta["marcador"]) {
                            // Ganó la apuesta
                            $gananciaBruta = $apuesta["monto"] * 2;
                            $comision = $gananciaBruta * $comisionPorcentaje;
                            $montoGanado = $gananciaBruta - $comision;

                            $totalGanado += $montoGanado;
                            $estadoApuesta = 'ganada';
                            $textoEstado = 'Ganó';
                        } else {
                            // Perdió la apuesta
                            $estadoApuesta = 'perdida';
                            $textoEstado = 'Perdió';
                        }

                        // Guardar el estado de la apuesta en la base de datos
                        include 'php/conexion_be.php';
                        $conexion = mysqli_connect("localhost", "root", "", "login_register_db");

                        $stmtUpdate = $conexion->prepare("UPDATE apuestas SET estado = ? WHERE id = ?");
                        $stmtUpdate->bind_param("si", $estadoApuesta, $apuesta["id"]);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                        $conexion->close();
                    }

                    echo "<tr>";
                    echo "<td>$" . number_format($apuesta["monto"], 2) . "</td>";
                    echo "<td>x2.00</td>";

                    $marcador = isset($apuesta["marcador"]) ? htmlspecialchars($apuesta["marcador"]) : '—';
                    echo "<td>$marcador</td>";

                    $resultado = isset($apuesta["resultado_partido"]) ? htmlspecialchars($apuesta["resultado_partido"]) : '—';
                    echo "<td>$resultado</td>";

                    echo "<td>" . htmlspecialchars($apuesta["fecha"]) . "</td>";
                    echo "<td>" . htmlspecialchars($hora_formateada) . "</td>";
                    echo "<td>$" . number_format($montoGanado, 2) . "</td>";
                    echo "<td>$textoEstado</td>";

                    if ($fechaHoraApuesta >= $inicioSesion) {
                        echo "<td>
                            <form action='editar_apuesta.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='index' value='$index'>
                                <button type='submit' class='btn-editar'>✏️ Editar</button>
                            </form>
                            <form action='eliminar_apuesta.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='index' value='$index'>
                                <button type='submit' class='btn-eliminar'>🗑️ Eliminar</button>
                            </form>
                        </td>";
                    } else {
                        echo "<td><span style='color: gray;'>🔒 No editable</span></td>";
                    }

                    echo "</tr>";
                }

                echo "</tbody></table>";
                echo "</div>";
            }

            // Mostrar y guardar el total ganado
            echo "<div class='tabla-container'>";
            echo "<h3 class='titulo-evento'>💰 Total Ganado</h3>";
            echo "<div class='total-y-boton'>";
            echo "<table class='tabla-apuestas'>";
            echo "<tr><td><strong>Total Ganado:</strong></td><td>$" . number_format($totalGanado, 2) . "</td></tr>";
            echo "</table></div></div>";

            // GUARDAR EN BASE DE DATOS EL TOTAL GANADO
            if ($usuarioActivo && isset($_SESSION['id_usuario'])) {
                include 'php/conexion_be.php';
                $conexion = mysqli_connect("localhost", "root", "", "login_register_db");

                $idUsuario = $_SESSION['id_usuario'];

                $stmt = $conexion->prepare("SELECT id FROM totales_usuarios WHERE id_usuario = ?");
                $stmt->bind_param("i", $idUsuario);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($resultado->num_rows > 0) {
                    $stmtUpdate = $conexion->prepare("UPDATE totales_usuarios SET total_ganado = ? WHERE id_usuario = ?");
                    $stmtUpdate->bind_param("di", $totalGanado, $idUsuario);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                } else {
                    $stmtInsert = $conexion->prepare("INSERT INTO totales_usuarios (id_usuario, total_ganado) VALUES (?, ?)");
                    $stmtInsert->bind_param("id", $idUsuario, $totalGanado);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                }

                $stmt->close();
                $conexion->close();
            }

        } else {
            echo "<p class='mensaje-vacio'>No hay apuestas registradas.</p>";
        }
        ?>
    </main>
</body>
</html>
