<?php
session_start();

date_default_timezone_set('America/Bogota');

$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';

if (!isset($_POST['index']) || !isset($_SESSION['apuestashechas'])) {
    die("Error: No se ha seleccionado una apuesta válida.");
}

$index = $_POST['index'];
$apuesta = $_SESSION['apuestashechas'][$index] ?? null;

if (!$apuesta) {
    die("Error: Apuesta no encontrada.");
}

$montos_permitidos = [10000, 20000, 30000, 40000, 50000, 100000, 200000];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Apuesta - ApuestaMax</title>
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="css/editar.css">
</head>
<body>
    <header>
        <div class="logo">🔥 ApuestaMax</div>
        <nav>
            <ul>
                <li><a href="index.php">🎰 Inicio</a></li>
                <li><a href="#">🏅 🇨🇴 Liga Colombiana</a></li>
                <li><a href="#">🏆 UEFA Champions League</a></li>
                <li><a href="#">🌍 Copa Mundial de la FIFA</a></li>
                <li><a href="apuestashechas.php">💸 Apuestas Hechas</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="editar-contenedor">
            <h2 class="titulo-seccion">✏️ Editar Apuesta</h2>
            <form action="guardar_edicion.php" method="POST">
                <input type="hidden" name="index" value="<?php echo htmlspecialchars($index); ?>">

                <!-- Monto -->
                <label for="nuevo_monto">Selecciona el nuevo monto:</label>
                <select name="nuevo_monto" required>
                    <option value="">-- Selecciona un monto --</option>
                    <?php foreach ($montos_permitidos as $monto): ?>
                        <option value="<?php echo $monto; ?>" <?php echo ($apuesta['monto'] == $monto) ? 'selected' : ''; ?>>
                            <?php echo "$" . number_format($monto, 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Marcador -->
                <label for="nuevo_marcador">Editar marcador (ej: 2-1):</label>
                <input type="text" name="nuevo_marcador" id="nuevo_marcador" 
                    value="<?php echo htmlspecialchars($apuesta['marcador'] ?? ''); ?>" 
                    placeholder="Ej: 2-1" required><br>

                <button type="submit" class="btn-guardar">💾 Guardar Cambios</button>
                <a href="apuestashechas.php" class="btn-cancelar">❌ Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>
