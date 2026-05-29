<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p style="color:green"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<form method="POST" action="<?= RUTA_URL ?>/autores/nuevo">

    <label>Nombre: *</label><br>
    <input type="text" name="nombre" maxlength="50" required><br><br>

    <label>Apellidos: *</label><br>
    <input type="text" name="apellidos" maxlength="100" required><br><br>

    <label>País:</label><br>
    <input type="text" name="pais" maxlength="50"><br><br>

    <button type="submit">Guardar</button>
    <a href="<?= RUTA_URL ?>/autores/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>