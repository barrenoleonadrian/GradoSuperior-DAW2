<?php
// Vista login: no usa header.php completo para no mostrar el menú
// sin estar autenticado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= RUTA_URL ?>/css/estilos.css">
    <title>Login - <?= NOMBRESITIO ?></title>
</head>
<body>

<main>
    <h1>🔐 Login Veterinarios</h1>
    <h2><?= NOMBRESITIO ?></h2>

    <?php if (!empty($error)): ?>
        <!-- Mostramos el error si lo hay (htmlspecialchars evita XSS) -->
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!--
        method="POST" -> los datos NO van en la URL (más seguro para contraseñas)
        action apunta al método login() del controlador Paginas
    -->
    <form method="POST" action="<?= RUTA_URL ?>/paginas/login">

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"
               placeholder="felix@veterinarios.com" required><br><br>

        <label for="clave">Contraseña:</label><br>
        <input type="password" id="clave" name="clave" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</main>

</body>
</html>
