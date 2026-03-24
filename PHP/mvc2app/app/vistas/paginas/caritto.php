<?php include "app/vistas/inc/header.php"; ?>

<h1> Mi Carrito</h1>

<a href="/?c=Articulos">Volver a la tienda</a>

<?php if (empty($carrito)): ?>
    <p>El carrito está vacío</p>
<?php else: ?>
    <?php $total = 0; // Variable para sumar el precio total ?>

    <?php foreach ($carrito as $item): ?>
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <p><strong><?= $item['nombre'] ?></strong> - <?= $item['precio'] ?>€</p>
        </div>
        <?php $total += $item['precio']; // Sumamos al total ?>
    <?php endforeach; ?>

    <h2>Total: <?= number_format($total, 2) ?>€</h2>

    <!-- Botón para vaciar el carrito -->
    <a href="/?c=Articulos&a=vaciar">Vaciar carrito</a>
<?php endif; ?>

<?php include "app/vistas/inc/footer.php"; ?>