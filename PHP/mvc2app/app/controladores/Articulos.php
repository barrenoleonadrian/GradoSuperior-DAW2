<?php
class Articulos extends Controlador {

    // Muestra la lista de artículos
    public function index() {
        $modelo = new Articulo();
        $articulos = $modelo->getArticulos(); // Obtenemos los artículos de la BD

        // Enviamos los artículos a la vista
        $this->vista('inicio', ['articulos' => $articulos]);
    }

    // Añade un artículo al carrito
    public function añadir() {
        session_start(); // Iniciamos la sesión para guardar el carrito

        $id     = $_GET['id'];
        $nombre = $_GET['nombre'];
        $precio = $_GET['precio'];

        // Si no existe el carrito en sesión, lo creamos vacío
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Añadimos el artículo al carrito como un array
        $_SESSION['carrito'][] = [
            'id'     => $id,
            'nombre' => $nombre,
            'precio' => $precio
        ];

        // Volvemos a la página principal
        header('Location: /?c=Articulos');
    }

    // Muestra el carrito
    public function carrito() {
        session_start();
        // Si el carrito no existe, lo ponemos como array vacío
        $carrito = $_SESSION['carrito'] ?? [];
        $this->vista('carrito', ['carrito' => $carrito]);
    }

    // Vacía el carrito
    public function vaciar() {
        session_start();
        unset($_SESSION['carrito']); // Eliminamos el carrito de la sesión
        header('Location: /?c=Articulos');
    }
}
?>