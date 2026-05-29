<?php

use Dwes\Biblioteca;

use Dwes\Biblioteca\Controlador;

// Controlador Autores: gestiona el listado, alta y eliminacion de autores.
// Extiende Controlador para heredar modelo() y vista()
class Autores extends Controlador{
    public function __construct(){
        // Seguridad: si no hay sesión activa redirigimos al login
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['bibliotecario_id'])){
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    //Listado de todos los autores
    public function index(): void{
        $modeloAutor = $this->modelo('Autor');
        $autores = $modeloAutor->getAutores();

        $datos = [
            'titulo' => 'Listado de Autores',
            'autores' => $autores,
        ];

        $this->vista('autores/index', $datos);
    }

    // Formulario de alta (GET) y procesamiento (POST)
    public function nuevo(): void{
        $error = '';
        $ok = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // 1ª capa de trim()
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellidos'] ?? '');
            $pais = trim($_POST['pais'] ?? '');

            //Validación: campos obligatorios
            if(empty($nombre) || empty($apellido)){
                $error = 'nombre y apellidos son obligatorios.';
            }else{
                $modeloAutor = $this->modelo('Autor');
                $resultado = $modeloAutor->insertarAutor($nombre, $apellido, $pais);

                if($resultado){
                    $ok = 'Autor registrado correctamente.';
                }else{
                    $error = 'Error al guardar en la base de datos';
                }
            }

        }

        $datos = [
            'titulo' => 'Nuevo Autor',
            'error' => $error,
            'ok' => $ok,
        ];

        $this->vista('autores/nuevo', $datos);
    }

    //Eliminar un autor
    public function eliminar(int $id): void{
        $modeloAutor = $this->modelo('Autor');
        $modeloAutor->eliminarAutor($id);

        header('Location: ' . RUTA_URL . '/autores/index');
        exit();
    }
}