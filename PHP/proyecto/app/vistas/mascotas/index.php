<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<p><a href="<?= RUTA_URL ?>/mascotas/nueva">➕ Registrar mascota</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>F. Nacimiento</th>
            <th>Dueño</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mascotas)): ?>
            <tr><td colspan="5">No hay mascotas registradas.</td></tr>
        <?php else: ?>
            <?php foreach ($mascotas as $mascota): ?>
                <tr>
                    <td>
                        <?php if (!empty($mascota->foto_url)): ?>
                            <!-- Mostramos la foto si existe -->
                            <img src="<?= RUTA_URL . '/../' . ltrim($mascota->foto_url, '/') ?>"
                                 alt="<?= htmlspecialchars($mascota->nombre) ?>"
                                 style="width:70px;height:70px;object-fit:cover;">
                        <?php else: ?>
                            Sin foto
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($mascota->nombre) ?></td>
                    <td><?= htmlspecialchars($mascota->tipo) ?></td>
                    <td><?= htmlspecialchars($mascota->fecha_nacimiento ?? '') ?></td>
                    <td>
                        <?= htmlspecialchars(
                            $mascota->nombre_duenio . ' ' . ($mascota->apellidos_duenio ?? '')
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
