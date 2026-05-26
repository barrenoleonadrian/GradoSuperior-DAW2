<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

/**
 * Controlador Mascotas – gestiona el alta y listado de mascotas.
 */
class Mascotas extends Controlador
{
    public function __construct()
    {
        // Seguridad: solo veterinarios logueados
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    // Listado de mascotas con nombre del dueño (JOIN en el modelo)
    public function index(): void
    {
        $modeloMascota = $this->modelo('Mascota');
        $mascotas      = $modeloMascota->getMascotas();

        $datos = [
            'titulo'   => 'Listado de Mascotas',
            'mascotas' => $mascotas,
        ];
        $this->vista('mascotas/index', $datos);
    }

    // Formulario de alta (GET) + procesamiento con subida de imagen (POST)
    public function nueva(): void
    {
        // Cargamos las personas para el <select> del formulario
        $modeloPersona = $this->modelo('Persona');
        $personas      = $modeloPersona->getPersonasParaSelector();

        $error = '';
        $ok    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1ª capa: trim() sobre los campos de texto
            $nombre     = trim($_POST['nombre']            ?? '');
            $tipo       = trim($_POST['tipo']              ?? '');
            $fecha      = trim($_POST['fecha_nacimiento']  ?? '');
            $id_persona = (int)($_POST['id_persona']       ?? 0);

            // Validación
            if (empty($nombre) || empty($tipo) || $id_persona <= 0) {
                $error = 'Nombre, tipo y dueño son obligatorios.';
            } elseif (strlen($nombre) > 50) {
                $error = 'El nombre no puede superar 50 caracteres (límite de la BD).';
            } elseif (strlen($tipo) > 30) {
                $error = 'El tipo no puede superar 30 caracteres.';
            } else {

                // ---- SUBIDA DE IMAGEN (de los apuntes: $_FILES, move_uploaded_file) ----
                $foto_url = ''; // valor por defecto si no se sube foto

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

                    $archivo    = $_FILES['foto'];
                    $extension  = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

                    if (!in_array($extension, $permitidas)) {
                        $error = 'Solo se permiten imágenes JPG, PNG o GIF.';
                    } elseif ($archivo['size'] > 2 * 1024 * 1024) {
                        $error = 'La imagen no puede superar 2 MB.';
                    } else {
                        // Nombre único para no sobreescribir imágenes anteriores
                        $nombreArchivo = uniqid('mascota_') . '.' . $extension;
                        // Ruta absoluta donde guardamos el archivo en el servidor
                        $rutaDestino   = __DIR__ . '/../../public/img/' . $nombreArchivo;

                        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                            // Guardamos la ruta relativa (la que usará el navegador)
                            $foto_url = '/public/img/' . $nombreArchivo;
                        } else {
                            $error = 'Error al mover la imagen al servidor.';
                        }
                    }
                }

                // Solo insertamos si no hubo error en la imagen
                if (empty($error)) {
                    $modeloMascota = $this->modelo('Mascota');
                    $resultado     = $modeloMascota->insertarMascota(
                        $nombre, $tipo, $fecha, $foto_url, $id_persona
                    );

                    if ($resultado) {
                        $ok = 'Mascota registrada correctamente.';
                    } else {
                        $error = 'Error al guardar en la base de datos.';
                    }
                }
            }
        }

        $datos = [
            'titulo'   => 'Registrar Mascota',
            'personas' => $personas, // para el <select> del formulario
            'error'    => $error,
            'ok'       => $ok,
        ];
        $this->vista('mascotas/nueva', $datos);
    }
}
