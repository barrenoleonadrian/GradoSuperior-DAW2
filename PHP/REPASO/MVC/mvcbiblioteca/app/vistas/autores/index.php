<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<p><a href="<?= RUTA_URL ?>/autores/nuevo">+ Añadir autor</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>País</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($autores as $autor): ?>
        <tr>
            <td><?= htmlspecialchars($autor->nombre) ?></td>
            <td><?= htmlspecialchars($autor->apellidos) ?></td>
            <td><?= htmlspecialchars($autor->pais ?? '') ?></td>
            <td>
                <a href="<?= RUTA_URL ?>/autores/eliminar/<?= $autor->id ?>"
                   onclick="return confirm('¿Eliminar a <?= htmlspecialchars($autor->nombre) ?>?')">
                    Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>