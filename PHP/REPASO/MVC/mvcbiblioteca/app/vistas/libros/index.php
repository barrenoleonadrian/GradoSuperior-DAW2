<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<p><a href="<?= RUTA_URL ?>/libros/nuevo">+ Añadir libro</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>Portada</th>
        <th>Título</th>
        <th>Género</th>
        <th>Año</th>
        <th>Autor</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($libros as $libro): ?>
        <tr>
            <td>
                <?php if (!empty($libro->foto_url)): ?>
                    <img src="<?= htmlspecialchars($libro->foto_url) ?>"
                         style="width:60px;height:80px;object-fit:cover">
                <?php else: ?>
                    Sin portada
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($libro->titulo) ?></td>
            <td><?= htmlspecialchars($libro->genero ?? '') ?></td>
            <td><?= htmlspecialchars($libro->anio ?? '') ?></td>
            <td>
                <?= htmlspecialchars($libro->nombre_autor . ' ' . $libro->apellidos_autor) ?>
            </td>
            <td>
                <a href="<?= RUTA_URL ?>/libros/eliminar/<?= $libro->id ?>"
                   onclick="return confirm('¿Eliminar este libro?')">
                    Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>