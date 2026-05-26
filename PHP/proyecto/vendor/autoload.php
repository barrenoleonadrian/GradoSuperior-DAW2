<?php
// Autoloader generado para namespace Dwes\Clinica
// Equivalente a lo que genera "composer dump-autoload"

spl_autoload_register(function (string $class): void {
    // Prefijo del namespace que gestiona este autoloader
    $prefijo = 'Dwes\\Clinica\\';
    $base    = __DIR__ . '/../app/';

    if (strncmp($prefijo, $class, strlen($prefijo)) !== 0) {
        return; // no es nuestro namespace
    }

    // Nombre de clase sin el prefijo
    $clase_relativa = substr($class, strlen($prefijo));
    $archivo        = $base . str_replace('\\', DIRECTORY_SEPARATOR, $clase_relativa) . '.php';

    // Buscamos en las tres carpetas registradas
    $carpetas = ['librerias', 'controladores', 'modelos'];
    foreach ($carpetas as $carpeta) {
        $ruta = $base . $carpeta . DIRECTORY_SEPARATOR . $clase_relativa . '.php';
        if (is_file($ruta)) {
            require $ruta;
            return;
        }
    }
});
