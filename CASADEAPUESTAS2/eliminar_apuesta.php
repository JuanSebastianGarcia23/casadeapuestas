<?php
session_start();
include 'conexion_be.php';

// ✅ Asegurarse de que $conexion esté definido
$conexion = mysqli_connect("localhost", "root", "", "login_register_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = $_POST['index'];

    // Validar si existe la apuesta en la sesión
    if (isset($_SESSION['apuestashechas'][$index])) {
        $apuesta = $_SESSION['apuestashechas'][$index];

        // Si la apuesta tiene ID, eliminarla de la base de datos
        if (isset($apuesta['id'])) {
            $id = intval($apuesta['id']);

            $stmt = mysqli_prepare($conexion, "DELETE FROM apuestas WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);

            // Verifica errores por si acaso
            if (mysqli_stmt_error($stmt)) {
                echo "❌ Error al eliminar: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        }

        // Eliminar del arreglo de sesión
        unset($_SESSION['apuestashechas'][$index]);
        $_SESSION['apuestashechas'] = array_values($_SESSION['apuestashechas']); // Reindexa
    }
}

// Cierra la conexión
mysqli_close($conexion);

// Redirige nuevamente a la página de apuestas hechas
header("Location: apuestashechas.php");
exit();
