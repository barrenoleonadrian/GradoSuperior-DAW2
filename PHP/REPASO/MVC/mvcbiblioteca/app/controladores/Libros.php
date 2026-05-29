<?php
namespace Dwes\Biblioteca;
use Dwes\Biblioteca\Controlador;

class Libros extends Controlador{
    public function __construct(){
        //Segurdad: si no hay sesión activa redirigirnos al login
        if(session_status() === PHP_SESSION_NONE){
            session_start
        }
        if (!isset($_SESSION['bibliotecario_id'])){
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    //Listado de todos los libros con el nombre del autor(JOIN en el modelo)

    public function index(): void{
        $modeloLibro = $this->modelo('Libro');
        $libros = $modeloLibro->getLibros();
        $datos = [
            'titulo' => 'Listado de Libros',
            'libros' => $libros,
        ];

        $this->vista('libros/index', $datos);
    }

    //Formulario de alta (GET) y procesamiento (POST)
    public function nuevo(): void{
        //Necesitamos los autores para el <select> del formulario
        $modeloAutor = $this->modelo('Autor');
        $autores     = $modeloAutor->getAutoresParaSelector();

        $error = '';
        $ok    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1ª capa: trim() limpia espacios
            $titulo    = trim($_POST['titulo']    ?? '');
            $genero    = trim($_POST['genero']    ?? '');
            $anio      = (int)($_POST['anio']     ?? 0);
            $id_autor  = (int)($_POST['id_autor'] ?? 0);

            // Validación: campos obligatorios
            if (empty($titulo) || $id_autor <= 0) {
                $error = 'El título y el autor son obligatorios.';
            } else {

                // ---- SUBIDA DE IMAGEN ----
                // enctype="multipart/form-data" en el formulario es obligatorio
                $foto_url = '';

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

                    $archivo    = $_FILES['foto'];
                    $extension  = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                    $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

                    if (!in_array($extension, $permitidas)) {
                        $error = 'Solo se permiten imágenes JPG, PNG o GIF.';
                    } elseif ($archivo['size'] > 2 * 1024 * 1024) {
                        $error = 'La imagen no puede superar 2MB.';
                    } else {
                        // uniqid() genera un nombre único para no sobreescribir fotos
                        $nombreArchivo = uniqid('libro_') . '.' . $extension;
                        $rutaDestino   = __DIR__ . '/../../public/img/' . $nombreArchivo;

                        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                            $foto_url = '/public/img/' . $nombreArchivo;
                        } else {
                            $error = 'Error al subir la imagen al servidor.';
                        }
                    }
                }

                // Solo insertamos si no hubo error en la imagen
                if (empty($error)) {
                    $modeloLibro = $this->modelo('Libro');
                    $resultado   = $modeloLibro->insertarLibro(
                        $titulo, $genero, $anio, $foto_url, $id_autor
                    );

                    if ($resultado) {
                        $ok = 'Libro registrado correctamente.';
                    } else {
                        $error = 'Error al guardar en la base de datos.';
                    }
                }
            }
        }

        $datos = [
            'titulo'  => 'Nuevo Libro',
            'autores' => $autores,
            'error'   => $error,
            'ok'      => $ok,
        ];

        $this->vista('libros/nuevo', $datos);
    }

    // Elimina un libro por id y redirige al listado
    // URL: /libros/eliminar/3
    public function eliminar(int $id): void {
        $modeloLibro = $this->modelo('Libro');
        $modeloLibro->eliminarLibro($id);

        header('Location: ' . RUTA_URL . '/libros/index');
        exit();
    }
}