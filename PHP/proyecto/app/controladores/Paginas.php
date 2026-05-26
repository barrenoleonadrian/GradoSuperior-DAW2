<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

/**
 * Controlador principal – gestiona login, logout, logged y la página de inicio.
 * El examen exige que los métodos login, logout y logged estén aquí.
 */
class Paginas extends Controlador
{
    public function __construct()
    {
        // Iniciamos la sesión en el controlador principal
        // session_start() debe llamarse antes de usar $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * logged() – comprueba si hay una sesión activa.
     * Si no hay sesión, redirige al login y detiene la ejecución.
     * Se llama al principio de cada método que requiera autenticación.
     */
    public function logged(): void
    {
        if (!isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    // Página de inicio – requiere estar logueado
    public function index(): void
    {
        $this->logged(); // seguridad

        $datos = ['titulo' => 'Inicio - ' . NOMBRESITIO];
        $this->vista('paginas/inicio', $datos);
    }

    /**
     * login() – muestra el formulario (GET) y procesa el login (POST).
     * Usa el modelo Veterinario para comprobar credenciales en la BD.
     */
    public function login(): void
    {
        // Si ya está logueado no tiene sentido volver al login
        if (isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/index');
            exit();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1ª capa: trim() para eliminar espacios accidentales
            $email = trim($_POST['email'] ?? '');
            $clave = trim($_POST['clave'] ?? '');

            if (empty($email) || empty($clave)) {
                $error = 'Por favor, rellena todos los campos.';
            } else {
                // Usamos el modelo Veterinario para consultar la BD
                $modeloVet   = $this->modelo('Veterinario');
                $veterinario = $modeloVet->login($email, $clave);

                if ($veterinario) {
                    // Login correcto: guardamos datos en la sesión
                    $_SESSION['veterinario_id']     = $veterinario->id;
                    $_SESSION['veterinario_nombre'] = $veterinario->nombre;

                    header('Location: ' . RUTA_URL . '/paginas/index');
                    exit();
                } else {
                    $error = 'Email o clave incorrectos.';
                }
            }
        }

        $datos = [
            'titulo' => 'Login - ' . NOMBRESITIO,
            'error'  => $error,
        ];
        $this->vista('paginas/login', $datos);
    }

    // logout() – destruye la sesión y redirige al login
    public function logout(): void
    {
        session_unset();   // elimina todas las variables de sesión
        session_destroy(); // destruye la sesión por completo

        header('Location: ' . RUTA_URL . '/paginas/login');
        exit();
    }
}
