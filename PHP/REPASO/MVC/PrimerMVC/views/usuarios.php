<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Lista de usuarios</h1>
    <ul>
        <?php foreach ($users as $index => $user): ?>
            <li>
                <?php echo $user; ?> 
                <a href="?id=<?php echo $index; ?>">Eliminar</a>
                <a href="?id=<?php echo $index; ?>">Editar</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre">
        <button type="submit">
            Crear usuario 
        </button>
    </form>
</body>
</html>