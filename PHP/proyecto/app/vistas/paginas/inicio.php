<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>
<h2>Bienvenido, <?= htmlspecialchars($_SESSION['veterinario_nombre'] ?? '') ?> 👋</h2>

<ul>
    <li><a href="<?= RUTA_URL ?>/personas/index">📋 Ver listado de dueños</a></li>
    <li><a href="<?= RUTA_URL ?>/personas/nueva">➕ Registrar nuevo dueño</a></li>
    <li><a href="<?= RUTA_URL ?>/mascotas/index">🐾 Ver listado de mascotas</a></li>
    <li><a href="<?= RUTA_URL ?>/mascotas/nueva">➕ Registrar nueva mascota</a></li>
</ul>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
