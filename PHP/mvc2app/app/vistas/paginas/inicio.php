<?php include __DIR__ . "/../inc/header.php"; ?>

<h1>Lista de Artículos</h1>

<a href="/Articulos/carrito">
     Ver carrito (<?= isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0 ?> items)
</a>

<hr>

<?php foreach ($articulos as $articulo): ?>
    <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
        <h2><?= $articulo->nombre ?></h2>
        <p><?= $articulo->descripcion ?></p>
        <p><strong>Precio: <?= $articulo->precio ?>€</strong></p>

        <a href="/Articulos/añadir/<?= $articulo->id ?>/<?= urlencode($articulo->nombre) ?>/<?= $articulo->precio ?>">
            Añadir al carrito
        </a>
    </div>
<?php endforeach; ?>

<?php include __DIR__ . "/../inc/footer.php"; ?>