<?php
class Controlador {
    public function vista($vista, $datos = []) {
        extract($datos);
        // __DIR__ apunta a /librerias, subimos un nivel con .. para llegar a /app
        require_once __DIR__ . "/../vistas/paginas/" . $vista . ".php";
    }
}
?>