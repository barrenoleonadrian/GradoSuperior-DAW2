<?php
namespace Dwes\Clinica;

/**
 * Controlador base – del que extienden todos los controladores.
 * Proporciona los métodos modelo() y vista().
 */
class Controlador
{
    // Carga e instancia un modelo por nombre
    public function modelo(string $modelo)
    {
        // Normaliza: 'persona' -> 'Persona'
        $modeloClase = ucfirst(strtolower(trim($modelo)));
        $fqcn        = __NAMESPACE__ . '\\' . $modeloClase;

        if (!class_exists($fqcn)) {
            throw new \RuntimeException("Modelo no encontrado: $fqcn");
        }

        return new $fqcn();
    }

    // Carga una vista y extrae las variables del array $datos
    public function vista(string $vista, array $datos = [])
    {
        // Hace disponibles todas las claves de $datos como variables
        // Ej: $datos['titulo'] -> $titulo
        extract($datos);

        $ruta = dirname(__DIR__)
            . DIRECTORY_SEPARATOR . 'vistas'
            . DIRECTORY_SEPARATOR
            . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $vista)
            . '.php';

        if (is_file($ruta)) {
            require $ruta;
            return;
        }

        throw new \RuntimeException("La vista no existe: $ruta");
    }
}
