<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<ul>
    <li><a href="<?= RUTA_URL ?>/autores/index">Ver autores</a></li>
    <li><a href="<?= RUTA_URL ?>/autores/nuevo">Añadir autor</a></li>
    <li><a href="<?= RUTA_URL ?>/libros/index">Ver libros</a></li>
    <li><a href="<?= RUTA_URL ?>/libros/nuevo">Añadir libro</a></li>
</ul>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>