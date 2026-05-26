<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

/**
 * Controlador Personas – gestiona el CRUD de personas (dueños de mascotas).
 */
class Personas extends Controlador
{
    public function __construct()
    {
        // Seguridad: si no hay sesión activa, redirigir al login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    // Listado de todas las personas (dueños)
    public function index(): void
    {
        $modeloPersona = $this->modelo('Persona');
        $personas      = $modeloPersona->getPersonas();

        $datos = [
            'titulo'   => 'Listado de Dueños',
            'personas' => $personas,
        ];
        $this->vista('personas/index', $datos);
    }

    // Ficha de una persona – el id llega como parámetro en la URL
    // Ejemplo: /personas/ficha/3
    public function ficha(int $id): void
    {
        $modeloPersona = $this->modelo('Persona');
        $persona       = $modeloPersona->getPersonaPorId($id);

        $datos = [
            'titulo'  => 'Ficha de Persona',
            'persona' => $persona,
        ];
        $this->vista('personas/ficha', $datos);
    }

    // Formulario de alta (GET) + procesamiento (POST)
    public function nueva(): void
    {
        $error = '';
        $ok    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1ª capa de validación: trim() elimina espacios al inicio y fin
            $nombre    = trim($_POST['nombre']    ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $telefono  = trim($_POST['telefono']  ?? '');
            $email     = trim($_POST['email']     ?? '');

            // Validación PHP (2ª capa)
            if (empty($nombre) || empty($apellidos)) {
                $error = 'Nombre y apellidos son obligatorios.';
            } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // filter_var valida el formato del email (de los apuntes)
                $error = 'El email no tiene un formato válido.';
            } elseif (!empty($telefono) && !preg_match('/^[0-9]{9}$/', $telefono)) {
                // Expresión regular: exactamente 9 dígitos (de los apuntes)
                $error = 'El teléfono debe tener exactamente 9 dígitos.';
            } else {
                $modeloPersona = $this->modelo('Persona');
                $resultado     = $modeloPersona->insertarPersona($nombre, $apellidos, $telefono, $email);

                if ($resultado) {
                    $ok = 'Persona registrada correctamente.';
                } else {
                    $error = 'Error al guardar en la base de datos.';
                }
            }
        }

        $datos = [
            'titulo' => 'Registrar Dueño',
            'error'  => $error,
            'ok'     => $ok,
        ];
        $this->vista('personas/nueva', $datos);
    }

    // Elimina una persona y redirige al listado
    // Ejemplo URL: /personas/eliminar/3
    public function eliminar(int $id): void
    {
        $modeloPersona = $this->modelo('Persona');
        $modeloPersona->eliminarPersona($id);

        header('Location: ' . RUTA_URL . '/personas/index');
        exit();
    }
}
