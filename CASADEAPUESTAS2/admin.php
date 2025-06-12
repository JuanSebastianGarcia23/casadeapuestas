<?php
session_start();
$usuarioActivo = isset($_SESSION['usuario']);
$nombreUsuario = $usuarioActivo ? $_SESSION['usuario'] : 'Invitado';
$idUsuario = $_SESSION['id'] ?? null; // ✅ Variable correctamente definida

// Verificar si el usuario está logueado y es admin
if (!$usuarioActivo || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <link rel="stylesheet" href="css/admin.css">
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
        <h2>⚽ Apuestas Deportivas Fútbol</h2>

        <?php include 'cargar_eventos.php'; ?>

    </section>
</main>

<footer>
    <p>&copy; 2025 ApuestaMax. Juego Responsable.</p>
</footer>

<script src="js/admin.js"></script>

</body>
</html>
