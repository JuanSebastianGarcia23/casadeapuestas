<?php
session_start(); // Iniciar sesión para obtener los datos del usuario
$usuarioActivo = isset($_SESSION['usuario']) ;
$nombreUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado';

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
            'nombre' => $torneo['nombre'], 
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
        <link rel="stylesheet" href="css/inicio.css">
    </head>
    <body>
        <header>
            <div class="logo">🔥 ApuestaMax</div>
            <nav>
                <ul>
                   <li><a href="inicio.php">🎰 Inicio</a></li>
                   <li><a href="#">🏅 🇨🇴 Liga Colombiana</a></li>
                   <li><a href="#">🏆 UEFA Champions League</a></li>
                   <li><a href="#">🌍 Copa Mundial de la FIFA</a></li>
                   <li><a href="apuestashechas.php">💸 Apuestas Hechas</a></li>
                     <li><a href="historial_ganancias.php">📈 Historial de Ganancias</a></li>
                </ul>
           </nav>

           <div class="usuario">
                👤  <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>
                <a href="logout.php" style="margin-left: 10px; color: red;">Cerrar sesión</a>
            </div>

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
    <!-- Torneo en la parte superior -->
    <h3 id="modal-torneo"></h3>

    <!-- Información del evento -->
    <div id="modal-evento-info">
        <p id="modal-evento"></p>
        <p id="modal-fecha"></p>
        <p id="modal-hora"></p>
        <p id="modal-cuota"></p>
    </div>

    <!-- Selección de monto -->
    <label for="monto">Selecciona un monto o ingresa uno personalizado:</label>
    <select id="monto">
        <option value="">Selecciona un monto</option>
        <option value="10000">$10,000</option>
        <option value="20000">$20,000</option>
        <option value="30000">$30,000</option>
        <option value="40000">$40,000</option>
        <option value="50000">$50,000</option>
        <option value="100000">$100,000</option>
        <option value="200000">$200,000</option>
    </select>

    <label for="metodo-pago">Método de Pago:</label>
<select id="metodo-pago">
  <option value="">Selecciona una opción</option>
  <option value="nequi">Nequi</option>
  <option value="cuenta_bancaria">Cuenta Bancaria</option>
</select>

<!-- Campo dinámico para datos del método de pago -->
<div id="detalle-pago" style="display:none; margin-top:10px;">
  <label id="label-detalle" for="input-detalle">Número:</label>
  <input type="text" id="input-detalle" placeholder="Ingrese número de Nequi o cuenta bancaria">
</div>


    <!-- Campo para monto personalizado -->
    <input type="number" id="monto-personalizado" placeholder="Ingresa tu monto" min="1" style="display: none;"><br>

    <!-- Marcador predicho -->
    <label for="marcador">Marcador predicho (ej: 1-2):</label>
    <input type="text" id="marcador" placeholder="Ej: 2-1"><br>

    </select><br>

    <!-- Botones -->
    <button id="confirmar-apuesta">Apostar</button>
    <button id="cancelar">Cancelar</button>
</div>

<footer>
    <p>&copy; 2025 ApuestaMax. Juego Responsable.</p>
</footer>

<script>
// Función para calcular cuota dinámica según el monto
function obtenerCuotaPorMonto(monto) {
    monto = parseFloat(monto);
    if (monto >= 200000) return 2.5;
    if (monto >= 100000) return 2.2;
    if (monto >= 50000) return 2.0;
    if (monto >= 20000) return 1.8;
    if (monto >= 10000) return 1.5;
    return 1.2;
}

// Mostrar el modal al hacer clic en botón "Apostar"
document.querySelectorAll('.apostar-btn').forEach(button => {
    button.addEventListener('click', (e) => {
        const evento = button.parentElement.querySelector('h4').innerText;
        
        document.getElementById('modal-evento').innerText = evento;

        // Reiniciar campos
        document.getElementById('modal-cuota').innerText = 'Cuota: --';
        document.getElementById('monto').value = '';
        document.getElementById('monto-personalizado').value = '';
        document.getElementById('marcador').value = '';
        document.getElementById('metodo-pago').value = '';
        document.getElementById('input-detalle').value = '';
        document.getElementById('monto-personalizado').style.display = 'none';
        document.getElementById('detalle-pago').style.display = 'none';

        document.getElementById('overlay').style.display = 'block';
        document.getElementById('modal').style.display = 'block';
    });
});

// Cancelar modal
document.getElementById('cancelar').addEventListener('click', () => {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('modal').style.display = 'none';
    document.getElementById('monto-personalizado').style.display = 'none';
    document.getElementById('detalle-pago').style.display = 'none';
    document.getElementById('monto').value = '';
    document.getElementById('monto-personalizado').value = '';
    document.getElementById('marcador').value = '';
    document.getElementById('modal-cuota').innerText = 'Cuota: --';
    document.getElementById('metodo-pago').value = '';
    document.getElementById('input-detalle').value = '';
});

// Mostrar campo personalizado si no se selecciona monto
document.getElementById('monto').addEventListener('change', function () {
    const montoPersonalizado = document.getElementById('monto-personalizado');
    if (this.value === '') {
        montoPersonalizado.style.display = 'block';
    } else {
        montoPersonalizado.style.display = 'none';
        const cuota = obtenerCuotaPorMonto(this.value);
        document.getElementById('modal-cuota').innerText = `Cuota: ${cuota}`;
    }
});

// Mostrar cuota al escribir monto personalizado
document.getElementById('monto-personalizado').addEventListener('input', function () {
    const valor = this.value;
    if (valor && valor > 0) {
        const cuota = obtenerCuotaPorMonto(valor);
        document.getElementById('modal-cuota').innerText = `Cuota: ${cuota}`;
    } else {
        document.getElementById('modal-cuota').innerText = 'Cuota: --';
    }
});

// Mostrar campo de detalle según el método de pago seleccionado
document.getElementById('metodo-pago').addEventListener('change', function () {
    const metodo = this.value;
    const detallePago = document.getElementById('detalle-pago');
    const label = document.getElementById('label-detalle');
    const input = document.getElementById('input-detalle');

    if (metodo === 'nequi') {
        detallePago.style.display = 'block';
        label.innerText = 'Número de Nequi:';
        input.placeholder = 'Ej: 3001234567';
    } else if (metodo === 'cuenta_bancaria') {
        detallePago.style.display = 'block';
        label.innerText = 'Número de Cuenta Bancaria:';
        input.placeholder = 'Ej: 1234567890';
    } else {
        detallePago.style.display = 'none';
        input.value = '';
    }
});


// Confirmar apuesta
document.getElementById('confirmar-apuesta').addEventListener('click', () => {
    const evento = document.getElementById('modal-evento').innerText;

    const montoSelect = document.getElementById('monto').value;
    const montoPersonalizado = document.getElementById('monto-personalizado').value;
    let monto = montoSelect || montoPersonalizado;

    const marcador = document.getElementById('marcador').value.trim();
    const metodoPago = document.getElementById('metodo-pago').value;
    const detallePago = document.getElementById('input-detalle').value.trim();

    // Validaciones
    if (!monto || isNaN(monto) || monto <= 0) {
        alert('Por favor selecciona o ingresa un monto válido.');
        return;
    }

    if (!marcador.match(/^\d+\s*-\s*\d+$/)) {
        alert('Por favor ingresa un marcador válido (ej: 2-1).');
        return;
    }

    if (!metodoPago) {
        alert('Por favor selecciona un método de pago.');
        return;
    }

    if (!detallePago) {
        alert('Por favor ingresa el número correspondiente al método de pago.');
        return;
    }

    // Calcular cuota dinámica
    const cuota = obtenerCuotaPorMonto(monto);

    // Enviar datos al servidor
    fetch('guardar_apuesta.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `evento=${encodeURIComponent(evento)}&cuota=${encodeURIComponent(cuota)}&monto=${encodeURIComponent(monto)}&marcador=${encodeURIComponent(marcador)}&metodo_pago=${encodeURIComponent(metodoPago)}&detalle_pago=${encodeURIComponent(detallePago)}`
    })
    .then(response => response.text())
    .then(data => {
        alert('✅ Apuesta guardada exitosamente.');

        // Cerrar y limpiar modal
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('modal').style.display = 'none';
        document.getElementById('monto').value = '';
        document.getElementById('monto-personalizado').value = '';
        document.getElementById('monto-personalizado').style.display = 'none';
        document.getElementById('marcador').value = '';
        document.getElementById('modal-cuota').innerText = 'Cuota: --';
        document.getElementById('metodo-pago').value = '';
        document.getElementById('input-detalle').value = '';
        document.getElementById('detalle-pago').style.display = 'none';
    })
    .catch(error => {
        console.error('Error al guardar la apuesta:', error);
        alert('❌ Error al guardar la apuesta. Intenta de nuevo.');
    });
});
</script>



</body>
</html>