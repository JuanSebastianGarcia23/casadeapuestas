<?php
session_start();
include 'conexion_be.php';

if (isset($_POST['correo']) && isset($_POST['contraseña'])) {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    if (!$conexion) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $correo = mysqli_real_escape_string($conexion, $correo);
    $contraseña = mysqli_real_escape_string($conexion, $contraseña);

    $validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' AND contraseña='$contraseña'");

    if (mysqli_num_rows($validar_login) > 0) {
        $usuario = mysqli_fetch_assoc($validar_login);
        
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_completo'];
        $_SESSION['usuario'] = $usuario['nombre_completo'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['inicio_sesion'] = time();

        // ✅ Cargar apuestas anteriores
        $id_usuario = $_SESSION['id_usuario'];
        $_SESSION['apuestashechas'] = [];

        $consulta_apuestas = mysqli_query($conexion, "SELECT evento, monto, fecha, hora, marcador, resultado_partido FROM apuestas WHERE id_usuario = $id_usuario");

        while ($fila = mysqli_fetch_assoc($consulta_apuestas)) {
            $_SESSION['apuestashechas'][] = $fila;
        }

        // ✅ Cargar eventos guardados
        $_SESSION['eventos'] = [];
        $consulta_eventos = mysqli_query($conexion, "SELECT * FROM eventos");

        while ($evento = mysqli_fetch_assoc($consulta_eventos)) {
            $_SESSION['eventos'][] = $evento;
        }

        // Redirigir según rol
        if ($_SESSION['rol'] == 'admin') {
            header("Location: ../admin.php");
        } else {
            header("Location: ../inicio.php");
        }
        exit();
    } else {
        echo '
            <script>
                alert("Usuario no existe o datos incorrectos");
                window.location = "../index.php";
            </script>
        ';
        exit();
    }
} else {
    echo 'Por favor ingresa los datos correctamente.';
}
?>

