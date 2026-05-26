<?php
declare(strict_types=1);

// Punto de entrada único de la aplicación
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/iniciador.php';

use Dwes\Clinica\Core;

// Instanciamos el Core, que se encarga de enrutar la petición
$iniciar = new Core();
