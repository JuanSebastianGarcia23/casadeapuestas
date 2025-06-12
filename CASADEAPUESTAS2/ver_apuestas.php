<?php
session_start();
$usuarioActivo = isset($_SESSION['usuario']);
$nombreUsuario = $usuarioActivo ? $_SESSION['usuario'] : 'Invitado';
$idUsuario = $_SESSION['id'] ?? null; // ✅ Variable correctamente definida

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo de eliminación por POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $sqlEliminar = "DELETE FROM apuestas WHERE id = ?";
    $stmt = $conn->prepare($sqlEliminar);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Apuesta eliminada exitosamente'); window.location.href='ver_apuestas.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la apuesta');</script>";
    }
    $stmt->close();
}

// Manejo de actualización del resultado del partido (solo si está vacío)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar_resultado'])) {
    $id = $_POST['actualizar_resultado'];
    $nuevoResultado = $_POST['nuevo_resultado'];

    $checkSql = "SELECT resultado_partido FROM apuestas WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();

    if (empty($row['resultado_partido'])) {
        $sqlActualizar = "UPDATE apuestas SET resultado_partido = ? WHERE id = ?";
        $stmt = $conn->prepare($sqlActualizar);
        $stmt->bind_param("si", $nuevoResultado, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Resultado ingresado correctamente'); window.location.href='ver_apuestas.php';</script>";
        } else {
            echo "<script>alert('Error al ingresar el resultado');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('El resultado ya fue ingresado y no se puede modificar');</script>";
    }

    $checkStmt->close();
}

// Obtener todas las apuestas
$sql = "SELECT * FROM apuestas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Apuestas</title>
    <link rel="stylesheet" href="css/verapuesta.css?v=1.1">
</head>
<body>
    <header>
        <h1>Panel Administrativo</h1>
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
        <section class="apuestas">
            <div class="tabla-container">
                <h2>💸 TODAS LAS APUESTAS</h2>
                <table class="tabla-apuestas" border="1" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Monto</th>
                            <th>Evento</th>
                            <th>Marcador</th>
                            <th>Resultado</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id"] . "</td>
                                        <td>" . htmlspecialchars($row["monto"]) . "</td>
                                        <td>" . htmlspecialchars($row["evento"]) . "</td>
                                        <td>" . htmlspecialchars($row["marcador"]) . "</td>
                                        <td>";

                                if (empty($row["resultado_partido"])) {
                                    echo "<form method='POST' class='form-resultado' action='ver_apuestas.php'>
                                            <input type='hidden' name='actualizar_resultado' value='" . $row["id"] . "'>
                                            <input class='input-resultado' type='text' name='nuevo_resultado' placeholder='0-0' required>
                                            <button class='btn-actualizar' type='submit'>✔</button>
                                          </form>";
                                } else {
                                    echo "<span class='resultado-final'>" . htmlspecialchars($row["resultado_partido"]) . "</span>";
                                }

                                echo "</td>
                                        <td>" . htmlspecialchars($row["fecha"]) . "</td>
                                        <td>" . htmlspecialchars($row["hora"]) . "</td>
                                        <td>
                                            <form method='POST' action='ver_apuestas.php' onsubmit='return confirm(\"¿Estás seguro de eliminar esta apuesta?\");' style='display:inline;'>
                                                <input type='hidden' name='eliminar' value='" . $row["id"] . "'>
                                                <button class='btn-eliminar' type='submit'>🗑️</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay apuestas registradas.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ApuestaMax - Panel Administrativo</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>


