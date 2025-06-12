<?php
// cargar_eventos.php

// Crea una nueva conexión a la base de datos (servidor: localhost, usuario: root, sin contraseña, base de datos: login_register_db)
$conexion = new mysqli("localhost", "root", "", "login_register_db");

// Verifica si ocurrió un error de conexión
if ($conexion->connect_error) {
    // Si falla la conexión, termina el script y muestra un mensaje de error
    die("Error de conexión: " . $conexion->connect_error);
}

// Prepara una consulta para seleccionar todos los eventos ordenados por id_torneo y luego por id descendente
$query = "SELECT * FROM eventos ORDER BY id_torneo, id DESC";
$resultado = $conexion->query($query);

// Define los nombres de los torneos usando su id como clave
$torneos = [
    1 => "🏅 🇨🇴 Liga Colombiana",
    2 => "🏆 UEFA Champions League",
    3 => "🌍 Copa Mundial de la FIFA"
];

// Inicializa un arreglo para agrupar los eventos según el torneo
$eventosAgrupados = [];

// Recorre los resultados de la consulta y agrupa los eventos por el id del torneo
while ($evento = $resultado->fetch_assoc()) {
    $idTorneo = (int) $evento['id_torneo']; // Asegura que el id_torneo sea tratado como número entero
    $eventosAgrupados[$idTorneo][] = $evento; // Agrega el evento al grupo correspondiente
}

// Ahora se generan los bloques HTML para mostrar los eventos por torneo
foreach ($torneos as $idTorneo => $nombreTorneo) {
    echo "<div class='torneo'>"; // Contenedor de cada torneo
    echo "<h3>$nombreTorneo</h3>"; // Título del torneo
    echo "<div class='eventos'>"; // Contenedor para los eventos del torneo

    // Si hay eventos para este torneo, los recorre
    if (!empty($eventosAgrupados[$idTorneo])) {
        foreach ($eventosAgrupados[$idTorneo] as $evento) {
            // Escapa los datos para evitar problemas de seguridad (inyecciones XSS)
            $equipoLocal = htmlspecialchars($evento['equipo_local']);
            $equipoVisitante = htmlspecialchars($evento['equipo_visitante']);
            $premio1 = htmlspecialchars($evento['premio_1']);
            $premio2 = htmlspecialchars($evento['premio_2']);
            $premio3 = htmlspecialchars($evento['premio_3']);

            // Genera el bloque HTML de cada evento
            echo "
                <div class='evento'>
                    <h4>⚽ $equipoLocal vs $equipoVisitante</h4>
                    <p>Premio: $premio1 | $premio2 | $premio3</p>
                    <div class='botones'>
                        <!-- Botón para editar el evento, con el id del evento en data-id -->
                        <button class='editar-btn' data-id='{$evento['id']}'>Editar</button>
                        <!-- Botón para eliminar el evento, con el id del evento en data-id -->
                        <button class='eliminar-btn' data-id='{$evento['id']}'>Eliminar</button>
                    </div>
                </div>
            ";
        }
    } else {
        // Si no hay eventos para este torneo, muestra un mensaje
        echo "<p>No hay eventos para este torneo.</p>";
    }

    echo "</div><br>"; // Cierra el contenedor de eventos y agrega espacio
    // Botón para agregar un nuevo evento al torneo
    echo "<button class='agregar-btn' data-torneo-id='$idTorneo'>➕ Agregar Evento</button>";
    echo "</div>"; // Cierra el contenedor del torneo
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
