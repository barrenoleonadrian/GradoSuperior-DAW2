<?php
// El header se incluye en todas las vistas.
// session_start() ya lo hizo el controlador, pero por seguridad comprobamos.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= RUTA_URL ?>/css/estilos.css">
    <title><?= NOMBRESITIO ?></title>
</head>
<body>

<header>
    <h2>🐾 <?= NOMBRESITIO ?></h2>
    <nav>
        <?php if (isset($_SESSION['veterinario_id'])): ?>
            <!-- Menú solo visible cuando hay sesión activa -->
            <a href="<?= RUTA_URL ?>/paginas/index">Inicio</a>
            <a href="<?= RUTA_URL ?>/personas/index">Dueños</a>
            <a href="<?= RUTA_URL ?>/personas/nueva">+ Dueño</a>
            <a href="<?= RUTA_URL ?>/mascotas/index">Mascotas</a>
            <a href="<?= RUTA_URL ?>/mascotas/nueva">+ Mascota</a>
            <span class="usuario">
                👤 <?= htmlspecialchars($_SESSION['veterinario_nombre']) ?>
            </span>
            <a href="<?= RUTA_URL ?>/paginas/logout" class="logout">Cerrar sesión</a>
        <?php endif; ?>
    </nav>
</header>
<hr>

<main>
