<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<p><a href="<?= RUTA_URL ?>/personas/nueva">➕ Añadir nuevo dueño</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($personas)): ?>
            <tr>
                <td colspan="6">No hay personas registradas.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($personas as $persona): ?>
                <!-- Recorremos el array de objetos que devuelve el modelo -->
                <tr>
                    <td><?= $persona->id ?></td>
                    <td><?= htmlspecialchars($persona->nombre) ?></td>
                    <td><?= htmlspecialchars($persona->apellidos ?? '') ?></td>
                    <td><?= htmlspecialchars($persona->telefono ?? '') ?></td>
                    <td><?= htmlspecialchars($persona->email    ?? '') ?></td>
                    <td>
                        <!-- El id se pasa como parámetro en la URL -->
                        <a href="<?= RUTA_URL ?>/personas/ficha/<?= $persona->id ?>">
                            Ver ficha
                        </a>
                        &nbsp;|&nbsp;
                        <a href="<?= RUTA_URL ?>/personas/eliminar/<?= $persona->id ?>"
                           onclick="return confirm('¿Seguro que quieres eliminar a <?= htmlspecialchars($persona->nombre) ?>?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
