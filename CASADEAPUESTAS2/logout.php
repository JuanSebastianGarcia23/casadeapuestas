<?php
session_start();
session_destroy(); // Destruye todos los datos de sesión
header("Location: index.php"); // Redirige al inicio (ajusta si el path es diferente)
exit();
?>
