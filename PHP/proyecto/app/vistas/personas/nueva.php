<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p class="ok"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<!--
    method="POST": datos en el cuerpo de la petición
    Los maxlength coinciden con los VARCHAR de la BD
-->
<form method="POST" action="<?= RUTA_URL ?>/personas/nueva">

    <label for="nombre">Nombre: *</label><br>
    <input type="text" id="nombre" name="nombre" maxlength="50" required><br><br>

    <label for="apellidos">Apellidos: *</label><br>
    <input type="text" id="apellidos" name="apellidos" maxlength="100" required><br><br>

    <label for="telefono">Teléfono:</label><br>
    <input type="text" id="telefono" name="telefono" maxlength="20"
           placeholder="9 dígitos"><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" maxlength="100"><br><br>

    <button type="submit">Guardar</button>
    &nbsp;
    <a href="<?= RUTA_URL ?>/personas/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
