<?php
// eliminar_evento.php

// Verifica si se ha enviado el ID del evento mediante POST
if (isset($_POST['id'])) {
    // Convierte el ID recibido en un número entero para mayor seguridad
    $id = intval($_POST['id']);

    // Crea una nueva conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "login_register_db");

    // Verifica si ocurrió un error en la conexión a la base de datos
    if ($conexion->connect_error) {
        // Si hay error, devuelve una respuesta en formato JSON y finaliza el script
        die(json_encode(["success" => false, "message" => "Error de conexión"]));
    }

    // Prepara una consulta SQL segura para eliminar el evento con el ID proporcionado
    $query = "DELETE FROM eventos WHERE id = ?";
    $stmt = $conexion->prepare($query);

    // Enlaza el parámetro de forma segura para evitar inyección SQL
    $stmt->bind_param("i", $id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, devuelve éxito en formato JSON
        echo json_encode(["success" => true]);
    } else {
        // Si hubo un error al eliminar, devuelve un mensaje de error en JSON
        echo json_encode(["success" => false, "message" => "No se pudo eliminar"]);
    }

    // Cierra la consulta preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se recibió un ID por POST, devuelve un mensaje de error
    echo json_encode(["success" => false, "message" => "ID no recibido"]);
}
?>


