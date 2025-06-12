<?php
session_start();
$usuarioActivo = isset($_SESSION['usuario']);
$nombreUsuario = $usuarioActivo ? $_SESSION['usuario'] : 'Invitado';
$idUsuario = $_SESSION['id'] ?? null; // ✅ Variable correctamente definida

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require 'php/conexion_be.php';

// Consultas para obtener usuarios y administradores
$sql_usuarios = "SELECT id, nombre_completo, correo, rol FROM usuarios WHERE rol = 'usuario'";  // Incluyendo 'id'
$sql_admins = "SELECT id, nombre_completo, correo, rol FROM usuarios WHERE rol = 'admin'"; // Incluyendo 'id'

$resultado_usuarios = $conexion->query($sql_usuarios);
$resultado_admins = $conexion->query($sql_admins);

// Función para cambiar el rol de un usuario
if (isset($_POST['cambiar_rol'])) {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_rol = $_POST['nuevo_rol'];
    
    $sql_actualizar = "UPDATE usuarios SET rol = '$nuevo_rol' WHERE id = $id_usuario";
    if ($conexion->query($sql_actualizar)) {
        header('Location: ver_usuarios.php'); // Recarga la página después de actualizar
        exit();
    } else {
        echo "Error al actualizar el rol.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="css/usuarios.css">
</head>
<body>
    <header>
        <h1>Usuarios Registrados</h1>
        <nav>
            <ul>
                <li><a href="admin.php">👨‍💼 Panel Administrativo</a></li>
                <li><a href="ver_apuestas.php">👁️ Ver Todas las Apuestas</a></li>
                <li><a href="estadisticas_apuestas.php">💰 Ganancias de la Plataforma</a></li>
                <?php if ($idUsuario == 1): ?>
                 <li><a href="ver_usuarios.php">👥 Ver Usuarios</a></li>
               <?php endif; ?>
                <div class="usuario">
                    👤 <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>
                    <a href="logout.php" style="margin-left: 10px; color: red;">Cerrar sesión</a>
                </div>
            </ul>
        </nav>
    </header>

    <main>
        <section class="usuarios">
            <!-- Tabla de administradores -->
            <h2>Administradores</h2>
            <table>
                <thead>
                    <tr>
                        <th>🧑 Nombre</th>
                        <th>📧 Correo</th>
                        <th>👥 Rol</th>
                        <th>🛠️ Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado_admins->num_rows > 0): ?>
                        <?php while ($row = $resultado_admins->fetch_assoc()): ?>
                            <tr> 
                                <td><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['rol']); ?></td>
                                <td>
                                    <!-- Verificamos que el id no sea 1 para mostrar el botón -->
                                    <?php if ($row['id'] !== 1): ?>
                                        <form action="ver_usuarios.php" method="POST">
                                            <input type="hidden" name="id_usuario" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="nuevo_rol" value="usuario">
                                            <button type="submit" name="cambiar_rol">Poner como Usuario</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No hay administradores registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Tabla de usuarios -->
            <h2>Usuarios</h2>
            <table>
                <thead>
                    <tr>
                        <th>🧑 Nombre</th>
                        <th>📧 Correo</th>
                        <th>👥 Rol</th>
                        <th>🛠️ Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado_usuarios->num_rows > 0): ?>
                        <?php while ($row = $resultado_usuarios->fetch_assoc()): ?>
                            <tr> 
                                <td><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['rol']); ?></td>
                                <td>
                                    <!-- Verificamos que el id no sea 1 para mostrar el botón -->
                                    <?php if ($row['id'] !== 1): ?>
                                        <form action="ver_usuarios.php" method="POST">
                                            <input type="hidden" name="id_usuario" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="nuevo_rol" value="admin">
                                            <button type="submit" name="cambiar_rol">Poner como Admin</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No hay usuarios registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </section>
    </main>

    <footer>
        <p>&copy; 2025 ApuestaMax. Juego Responsable.</p>
    </footer>
</body>
</html>

