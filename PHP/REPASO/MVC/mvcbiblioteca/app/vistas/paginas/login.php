<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
</head>
<body>

<h1>Login - <?= NOMBRESITIO ?></h1>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!--
    method POST: los datos van en el cuerpo HTTP, no en la URL.
    Más seguro para contraseñas.
-->
<form method="POST" action="<?= RUTA_URL ?>/paginas/login">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Clave:</label><br>
    <input type="password" name="clave" required><br><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>