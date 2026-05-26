<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if ($persona): ?>
    <table border="1" cellpadding="8">
        <tr><th>ID</th>       <td><?= $persona->id ?></td></tr>
        <tr><th>Nombre</th>   <td><?= htmlspecialchars($persona->nombre) ?></td></tr>
        <tr><th>Apellidos</th><td><?= htmlspecialchars($persona->apellidos ?? '') ?></td></tr>
        <tr><th>Teléfono</th> <td><?= htmlspecialchars($persona->telefono ?? '') ?></td></tr>
        <tr><th>Email</th>    <td><?= htmlspecialchars($persona->email    ?? '') ?></td></tr>
    </table>
<?php else: ?>
    <p>Persona no encontrada.</p>
<?php endif; ?>

<br>
<a href="<?= RUTA_URL ?>/personas/index">← Volver al listado</a>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
