<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NOMBRESITIO ?></title>
</head>
<body>

<nav>
    <?php if (isset($_SESSION['bibliotecario_id'])): ?>
        <a href="<?= RUTA_URL ?>/paginas/index">Inicio</a> |
        <a href="<?= RUTA_URL ?>/autores/index">Autores</a> |
        <a href="<?= RUTA_URL ?>/autores/nuevo">+ Autor</a> |
        <a href="<?= RUTA_URL ?>/libros/index">Libros</a> |
        <a href="<?= RUTA_URL ?>/libros/nuevo">+ Libro</a> |
        <strong><?= htmlspecialchars($_SESSION['bibliotecario_nombre']) ?></strong> |
        <a href="<?= RUTA_URL ?>/paginas/logout">Cerrar sesión</a>
    <?php endif; ?>
</nav>
<hr>