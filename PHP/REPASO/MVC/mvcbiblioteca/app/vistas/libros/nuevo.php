<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p style="color:green"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<!--
    enctype="multipart/form-data" es obligatorio para subir archivos.
    Sin él $_FILES estará vacío.
-->
<form method="POST" action="<?= RUTA_URL ?>/libros/nuevo"
      enctype="multipart/form-data">

    <label>Título: *</label><br>
    <input type="text" name="titulo" maxlength="150" required><br><br>

    <label>Género:</label><br>
    <input type="text" name="genero" maxlength="50"><br><br>

    <label>Año:</label><br>
    <input type="number" name="anio" min="0" max="2100"><br><br>

    <!-- El <select> se rellena con los autores que viene del controlador -->
    <label>Autor: *</label><br>
    <select name="id_autor" required>
        <option value="">-- Selecciona un autor --</option>
        <?php foreach ($autores as $autor): ?>
            <option value="<?= $autor->id ?>">
                <?= htmlspecialchars($autor->apellidos . ', ' . $autor->nombre) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Portada:</label><br>
    <input type="file" name="foto" accept="image/*"><br><br>

    <button type="submit">Guardar</button>
    <a href="<?= RUTA_URL ?>/libros/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>