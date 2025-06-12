<?php

// Incluye el archivo de conexión a la base de datos
include 'conexion_be.php';

// Obtiene los valores enviados por el formulario a través de POST
$nombre_completo = $_POST['nombre_completo'];
$correo = $_POST['correo'];
$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

// Prepara una consulta SQL para insertar los datos en la tabla "usuarios"
$query = "INSERT INTO usuarios (nombre_completo, correo, usuario, contraseña) 
          VALUES ('$nombre_completo', '$correo', '$usuario', '$contraseña')";

// Ejecuta la consulta en la base de datos
$ejecutar = mysqli_query($conexion, $query);

// Verifica si la consulta se ejecutó correctamente
if($ejecutar){
    // Si se registró correctamente, muestra un mensaje y redirige al usuario al inicio
    echo '
    <script>
    alert("Usuario registrado exitosamente");
    window.location="../index.php"; // Redirige a la página principal
    </script>
    ';
}else{
    // Si ocurrió un error al registrar, muestra un mensaje de error y redirige
    echo '
    <script>
    alert("Intente nuevamente, usuario no registrado");
    window.location="../index.php"; // Redirige a la página principal
    </script>
    ';
}
?>
