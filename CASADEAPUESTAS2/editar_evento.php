<?php
// editar_evento.php

// Verifica si todos los datos necesarios fueron enviados vía POST
if (isset($_POST['id'], $_POST['equipo_local'], $_POST['equipo_visitante'], $_POST['premio_1'], $_POST['premio_2'], $_POST['premio_3'])) {

    // Asigna los valores recibidos del formulario a variables locales
    $id = intval($_POST['id']); // Convierte el id a entero por seguridad
    $equipo_local = $_POST['equipo_local'];
    $equipo_visitante = $_POST['equipo_visitante'];
    $premio_1 = $_POST['premio_1'];
    $premio_2 = $_POST['premio_2'];
    $premio_3 = $_POST['premio_3'];

    // Crea una nueva conexión a la base de datos (MySQL)
    $conexion = new mysqli("localhost", "root", "", "login_register_db");

    // Verifica si hay error de conexión
    if ($conexion->connect_error) {
        die(json_encode(["success" => false, "message" => "Error de conexión"]));
    }

    // Prepara la consulta SQL para actualizar los datos del evento
    $query = "UPDATE eventos SET equipo_local = ?, equipo_visitante = ?, premio_1 = ?, premio_2 = ?, premio_3 = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);

    // Enlaza los parámetros de forma segura para evitar inyecciones SQL
    $stmt->bind_param("sssssi", $equipo_local, $equipo_visitante, $premio_1, $premio_2, $premio_3, $id);

    // Ejecuta la consulta y devuelve el resultado en formato JSON
    if ($stmt->execute()) {
        // Si se actualizó correctamente
        echo json_encode(["success" => true]);
    } else {
        // Si ocurrió un error durante la actualización
        echo json_encode(["success" => false, "message" => "No se pudo actualizar"]);
    }

    // Cierra la consulta preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si faltan datos en la solicitud POST
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
}
?>

