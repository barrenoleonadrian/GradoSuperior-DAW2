<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p class="ok"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<!--
    enctype="multipart/form-data" es OBLIGATORIO para subir archivos.
    Sin este atributo, $_FILES estará vacío.
-->
<form method="POST" action="<?= RUTA_URL ?>/mascotas/nueva" enctype="multipart/form-data">

    <label for="nombre">Nombre: *</label><br>
    <input type="text" id="nombre" name="nombre" maxlength="50" required><br><br>

    <label for="tipo">Tipo (gato, perro, tortuga...): *</label><br>
    <input type="text" id="tipo" name="tipo" maxlength="30" required><br><br>

    <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"><br><br>

    <!-- Selector de dueño: los datos vienen del modelo Persona -->
    <label for="id_persona">Dueño: *</label><br>
    <select id="id_persona" name="id_persona" required>
        <option value="">-- Selecciona un dueño --</option>
        <?php foreach ($personas as $persona): ?>
            <option value="<?= $persona->id ?>">
                <?= htmlspecialchars($persona->nombre . ' ' . ($persona->apellidos ?? '')) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Campo para subir imagen: accept limita el selector de archivos -->
    <label for="foto">Foto de la mascota (JPG, PNG, GIF - máx 2MB):</label><br>
    <input type="file" id="foto" name="foto" accept="image/*"><br><br>

    <button type="submit">Guardar</button>
    &nbsp;
    <a href="<?= RUTA_URL ?>/mascotas/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
