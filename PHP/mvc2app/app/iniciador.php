<?php
session_start();

// __DIR__ devuelve la ruta absoluta de la carpeta donde está iniciador.php
// Así PHP siempre encuentra los archivos sin importar desde dónde se llame

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/librerias/Db.php";
require_once __DIR__ . "/librerias/Controlador.php";
require_once __DIR__ . "/modelos/Articulo.php";
require_once __DIR__ . "/controladores/Articulos.php";

$c = $_GET['c'] ?? 'Articulos';
$a = $_GET['a'] ?? 'index';

$controlador = new $c();
$controlador->$a();
?>