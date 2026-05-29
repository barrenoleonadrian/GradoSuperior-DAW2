<?php
namespace Dwes\Biblioteca;

use Dwes\Biblioteca\Controlador;

// Controlador API REST: responde en JSON, no usa vistas.
// Endpoints:
//   GET    /api/autores        → lista todos los autores
//   POST   /api/autores        → crea un nuevo autor
//   DELETE /api/libro/{id}     → elimina un libro por id
class Api extends Controlador {

    public function __construct() {
        // La API usa autenticación HTTP Basic, no sesiones
        $this->autenticarBasic();
    }

    // Autenticación HTTP Basic.
    // El cliente envía "Authorization: Basic base64(usuario:clave)"
    // Si las credenciales son incorrectas devuelve 401 y detiene.
    private function autenticarBasic(): void {
        $usuario_api = 'apibiblioteca';
        $clave_api   = 'biblioteca2026';

        // PHP rellena estas variables con las credenciales Basic
        $usuario = $_SERVER['PHP_AUTH_USER'] ?? '';
        $clave   = $_SERVER['PHP_AUTH_PW']   ?? '';

        if ($usuario !== $usuario_api || $clave !== $clave_api) {
            http_response_code(401);
            header('WWW-Authenticate: Basic realm="API Biblioteca"');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'No autorizado. Credenciales incorrectas.']);
            exit();
        }
    }

    // Envía respuesta JSON con el código HTTP indicado y detiene la ejecución
    private function jsonResponse(array $datos, int $codigo = 200): void {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // GET  /api/autores  → devuelve todos los autores
    // POST /api/autores  → crea un nuevo autor
    public function autores(): void {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $autores   = $this->modelo('Autor')->getAutores();
            // Convertimos los objetos a arrays para json_encode
            $resultado = array_map(fn($a) => (array)$a, $autores);
            $this->jsonResponse($resultado, 200);

        } elseif ($metodo === 'POST') {
            // Leemos el JSON que envía el cliente en el body
            $body      = json_decode(file_get_contents('php://input'), true);
            $nombre    = trim($body['nombre']    ?? '');
            $apellidos = trim($body['apellidos'] ?? '');
            $pais      = trim($body['pais']      ?? '');

            // Validación básica
            if (empty($nombre) || empty($apellidos)) {
                $this->jsonResponse(['error' => 'Nombre y apellidos son obligatorios.'], 400);
            }

            $ok = $this->modelo('Autor')->insertarAutor($nombre, $apellidos, $pais);

            if ($ok) {
                // 201 Created: el recurso se ha creado correctamente
                $this->jsonResponse(['mensaje' => 'Autor creado correctamente.'], 201);
            } else {
                $this->jsonResponse(['error' => 'Error al crear el autor.'], 500);
            }

        } else {
            // Método no permitido
            $this->jsonResponse(['error' => 'Método no permitido.'], 405);
        }
    }

    // DELETE /api/libro/{id}  → elimina el libro con ese id
    public function libro(int $id): void {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'DELETE') {
            $modeloLibro = $this->modelo('Libro');

            // Primero comprobamos que el libro existe
            $libro = $modeloLibro->getLibroPorId($id);

            if (!$libro) {
                // 404 Not Found: el recurso no existe
                $this->jsonResponse(['error' => "No existe ningún libro con id $id."], 404);
            }

            $ok = $modeloLibro->eliminarLibro($id);

            if ($ok) {
                $this->jsonResponse(['mensaje' => "Libro con id $id eliminado correctamente."], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al eliminar el libro.'], 500);
            }

        } else {
            $this->jsonResponse(['error' => 'Método no permitido.'], 405);
        }
    }
}