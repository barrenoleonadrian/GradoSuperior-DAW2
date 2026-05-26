<?php
namespace Dwes\Clinica;

/**
 * Core – mapea la URL al controlador/método/parámetro correcto.
 *
 * Formato URL: BASE/controlador/metodo/parametro
 * Ejemplo:     /mvcrecuperacion/public/personas/ficha/3
 */
class Core
{
    protected $controladorActual = 'Paginas';
    protected $metodoActual      = 'index';
    protected array $parametros  = [];

    public function __construct()
    {
        $url = $this->getUrl() ?? [];

        // 1) Controlador desde la URL
        $controlador = $url[0] ?? '';
        $controlador = strtok($controlador, '?');
        $controlador = trim($controlador);

        if ($controlador !== '') {
            $controladorClase = ucfirst(strtolower($controlador)); // personas -> Personas
            $fqcn = __NAMESPACE__ . '\\' . $controladorClase;

            // class_exists() dispara el autoload de Composer
            if (class_exists($fqcn)) {
                $this->controladorActual = $controladorClase;
                unset($url[0]);
            }
        }

        // 2) Instanciar el controlador
        $fqcnControlador         = __NAMESPACE__ . '\\' . $this->controladorActual;
        $this->controladorActual = new $fqcnControlador();

        // 3) Método desde la URL
        $metodo = $url[1] ?? $this->metodoActual;
        if (method_exists($this->controladorActual, $metodo)) {
            $this->metodoActual = $metodo;
            unset($url[1]);
        }

        // 4) Parámetros restantes
        $this->parametros = $url ? array_values($url) : [];

        // 5) Ejecutar el método con sus parámetros
        call_user_func_array(
            [$this->controladorActual, $this->metodoActual],
            $this->parametros
        );
    }

    // Obtiene y sanitiza la URL
    public function getUrl(): ?array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return null;
    }
}
