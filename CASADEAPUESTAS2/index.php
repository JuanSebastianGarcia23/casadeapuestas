<?php
session_start();
$usuarioActivo = isset($_SESSION['usuario']) ? 'true' : 'false';

// CONEXIÓN A BASE DE DATOS
$conexion = new mysqli('localhost', 'root', '', 'login_register_db');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// CONSULTA TORNEOS Y EVENTOS
$torneos = [];
$sqlTorneos = "SELECT * FROM torneos";
$resultTorneos = $conexion->query($sqlTorneos);

if ($resultTorneos->num_rows > 0) {
    while ($torneo = $resultTorneos->fetch_assoc()) {
        // Para cada torneo, obtener sus eventos
        $sqlEventos = "SELECT * FROM eventos WHERE id_torneo = " . $torneo['id'];
        $resultEventos = $conexion->query($sqlEventos);
        $eventos = [];
        if ($resultEventos->num_rows > 0) {
            while ($evento = $resultEventos->fetch_assoc()) {
                $eventos[] = $evento;
            }
        }
        $torneos[] = [
            'nombre' => $torneo['nombre'], // <-- Corrección aquí
            'eventos' => $eventos
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Casa de Apuestas Premium</title>
        <link rel="stylesheet" href="css/styles.css">
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
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    
                </ul>
            </nav>
        </header>
        <main>
    <section class="apuestas">
        <h2>⚽ Apuestas Deportivas Fútbol</h2>

        <?php foreach ($torneos as $torneo): ?>
        <div class="torneo">
            <h3><?= htmlspecialchars($torneo['nombre']) ?></h3>
            <div class="eventos">
                <?php foreach ($torneo['eventos'] as $evento): ?>
                <div class="evento">
                    <h4>⚽ <?= htmlspecialchars($evento['equipo_local']) ?> vs <?= htmlspecialchars($evento['equipo_visitante']) ?></h4>
                    <p>
                        <?= htmlspecialchars($evento['premio_1']) ?> |
                        <?= htmlspecialchars($evento['premio_2']) ?> |
                        <?= htmlspecialchars($evento['premio_3']) ?>
                    </p>
                    <button class="apostar-btn">Apostar</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</main>

            
    
    <div class="overlay" id="overlay"></div>
    <div class="modal" id="modal">
        <h3 id="modal-evento"></h3>
        <p id="modal-cuota"></p>
        <label for="monto">Selecciona un monto o ingresa uno personalizado:</label>
        <select id="monto">
            <option value="">Selecciona un monto</option> <!-- Opción vacía para mostrar el campo personalizado -->
            <option value="10000">$10,000</option>
            <option value="50000">$50,000</option>
            <option value="100000">$100,000</option>
            <option value="200000">$200,000</option>
        </select>
        <!-- Campo para monto personalizado -->
        <input type="number" id="monto-personalizado" placeholder="Ingresa tu monto" min="1" style="display: none;">
        <button id="confirmar-apuesta">Apostar</button>
        <button id="cancelar">Cancelar</button>
    </div>
    
       <footer>
            <p>&copy; 2025 ApuestaMax. Juego Responsable.</p>
        </footer>
        <script src="js/script.js"></script>
      
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const botonesApostar = document.querySelectorAll(".apostar-btn");
    const modal = document.getElementById("modal");
    const overlay = document.getElementById("overlay");
    const modalEvento = document.getElementById("modal-evento");
    const modalCuota = document.getElementById("modal-cuota");
    const montoSelect = document.getElementById("monto");
    const montoPersonalizado = document.getElementById("monto-personalizado");
    const confirmarApuesta = document.getElementById("confirmar-apuesta");
    const cancelarBtn = document.getElementById("cancelar");

    // Variable para saber si el usuario ha iniciado sesión
    const usuarioActivo = <?php echo json_encode($usuarioActivo); ?>;

    // Mostrar el modal cuando el usuario hace clic en "Apostar"
    botonesApostar.forEach(boton => {
        boton.addEventListener("click", function () {
            if (usuarioActivo === "false") {
                alert("⚠️ Debes iniciar sesión para realizar una apuesta.");
                return;
            }
            modalEvento.innerText = this.parentElement.querySelector("h3").innerText;
            modalCuota.innerText = this.parentElement.querySelector("p").innerText;
            modal.style.display = "block";
            overlay.style.display = "block";
        });
    });

    // Mostrar campo de monto personalizado si se selecciona la opción vacía
    montoSelect.addEventListener("change", function () {
        if (this.value === "") {
            montoPersonalizado.style.display = "block"; // Mostrar el campo personalizado
        } else {
            montoPersonalizado.style.display = "none"; // Ocultar el campo personalizado
        }
    });

    // Confirmar la apuesta
    confirmarApuesta.addEventListener("click", function () {
        let monto;
        // Si el campo personalizado está visible y tiene un valor, usar ese valor
        if (montoSelect.value === "") {
            monto = montoPersonalizado.value;
        } else {
            monto = montoSelect.value;
        }

        // Validar que el monto sea un número positivo
        if (monto && !isNaN(monto) && monto > 0) {
            alert(`✅ Apuesta realizada\n\nEvento: ${modalEvento.innerText}\nMonto apostado: $${monto}\n\n¿Deseas seguir apostando?`);
            modal.style.display = "none";
            overlay.style.display = "none";
        } else {
            alert("Por favor, ingresa un monto válido.");
        }
    });

    // Cerrar el modal sin hacer ninguna apuesta
    cancelarBtn.addEventListener("click", function () {
        modal.style.display = "none";
        overlay.style.display = "none";
    });
});
</script>


</body>
</html>