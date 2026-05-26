<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

/**
 * Controlador Api – expone recursos REST en formato JSON.
 *
 * Endpoints:
 *   GET    /api/personas        -> lista todos los dueños
 *   POST   /api/personas        -> crea un nuevo dueño
 *   DELETE /api/mascota/{id}    -> elimina una mascota por id
 *
 * Autenticación: HTTP Basic (usuario: apiclinica | clave: clinicaapi)
 */
class Api extends Controlador
{
    public function __construct()
    {
        // La API usa autenticación HTTP Basic, no sesiones
        $this->autenticarBasic();
    }

    /**
     * Autenticación HTTP Basic.
     * El cliente envía "Authorization: Basic <base64(usuario:clave)>".
     * Si las credenciales son incorrectas, devuelve 401 y detiene.
     */
    private function autenticarBasic(): void
    {
        $usuario_api = 'apiclinica';
        $clave_api   = 'clinicaapi';

        // PHP rellena estas variables del servidor con las credenciales Basic
        $usuario = $_SERVER['PHP_AUTH_USER'] ?? '';
        $clave   = $_SERVER['PHP_AUTH_PW']   ?? '';

        if ($usuario !== $usuario_api || $clave !== $clave_api) {
            http_response_code(401);
            header('WWW-Authenticate: Basic realm="API Clínica Veterinaria"');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'No autorizado. Credenciales incorrectas.']);
            exit();
        }
    }

    /**
     * Envía una respuesta JSON con el código HTTP indicado y detiene la ejecución.
     */
    private function jsonResponse(array $datos, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        // JSON_UNESCAPED_UNICODE para que las tildes no salgan como \u00e1
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    /**
     * GET  /api/personas  -> devuelve JSON con todos los dueños      (1p)
     * POST /api/personas  -> crea un nuevo dueño a partir del body   (1p)
     */
    public function personas(): void
    {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $modeloPersona = $this->modelo('Persona');
            $personas      = $modeloPersona->getPersonas();

            // Convertimos los objetos stdClass a arrays para json_encode
            $resultado = array_map(fn($p) => (array)$p, $personas);

            $this->jsonResponse($resultado, 200);

        } elseif ($metodo === 'POST') {

            // Leemos el cuerpo de la petición – el cliente envía JSON
            $body = json_decode(file_get_contents('php://input'), true);

            $nombre    = trim($body['nombre']    ?? '');
            $apellidos = trim($body['apellidos'] ?? '');
            $telefono  = trim($body['telefono']  ?? '');
            $email     = trim($body['email']     ?? '');

            // Validación básica
            if (empty($nombre) || empty($apellidos)) {
                $this->jsonResponse(['error' => 'Nombre y apellidos son obligatorios.'], 400);
            }

            $modeloPersona = $this->modelo('Persona');
            $ok            = $modeloPersona->insertarPersona($nombre, $apellidos, $telefono, $email);

            if ($ok) {
                // 201 Created: el recurso se ha creado correctamente
                $this->jsonResponse(['mensaje' => 'Dueño creado correctamente.'], 201);
            } else {
                $this->jsonResponse(['error' => 'Error al crear el dueño.'], 500);
            }

        } else {
            // Método no permitido
            $this->jsonResponse(['error' => 'Método no permitido.'], 405);
        }
    }

    /**
     * DELETE /api/mascota/{id}  -> elimina la mascota con ese id     (1p)
     * El id llega como parámetro de URL: /api/mascota/5
     */
    public function mascota(int $id): void
    {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'DELETE') {

            $modeloMascota = $this->modelo('Mascota');

            // Primero comprobamos que la mascota existe
            $mascota = $modeloMascota->getMascotaPorId($id);
            if (!$mascota) {
                // 404 Not Found
                $this->jsonResponse(['error' => "No existe ninguna mascota con id $id."], 404);
            }

            $ok = $modeloMascota->eliminarMascota($id);

            if ($ok) {
                $this->jsonResponse(['mensaje' => "Mascota con id $id eliminada correctamente."], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al eliminar la mascota.'], 500);
            }

        } else {
            $this->jsonResponse(['error' => 'Método no permitido.'], 405);
        }
    }
}
