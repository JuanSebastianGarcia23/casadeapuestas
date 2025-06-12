<?php
session_start(); // Iniciar sesión para obtener los datos del usuario
$usuarioActivo = isset($_SESSION['usuario']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN Y REGISTRO</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
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
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">

            <!-- Caja trasera con login y registro -->
            <div class="caja__trasera">
                <div class="caja__trasera__login">
                    <h2>Ya tengo una cuenta</h2>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar sesión</button>
                </div>
                <div class="caja__trasera__register">
                    <h2>Aún no tengo una <br>
                        cuenta</h2>
                    <p>Regístrate para iniciar sesión</p>
                    <button id="btn__registrarse">Registrarse</button>
                </div>
            </div>

            <!-- Contenedor de formularios -->
            <div class="contenedor__login-register">
                <form action="php/login_usuario_be.php" method="POST" class="formulario__login">
                    <h2>Iniciar sesión</h2>
                    <input type="text" placeholder="Correo electrónico" name="correo">
                    <input type="password" placeholder="Contraseña" name="contraseña">
                    <button>Entrar</button>
                </form>

                <form action="php/registro_usuario_be.php" method="POST" class="formulario__register">
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Nombre completo" name="nombre_completo">
                    <input type="text" placeholder="Correo electrónico" name="correo">
                    <input type="text" placeholder="Usuario" name="usuario">
                    <input type="password" placeholder="Contraseña" name="contraseña">
                    <button>Registrarse</button>
                </form>
            </div>

        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>
