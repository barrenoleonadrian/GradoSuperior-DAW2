<?php
namespace Dwes\Biblioteca;

use Dwes\Biblioteca\Controlador;

    class Paginas extends Controlador{

        public function __construct(){
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
        }

        //Comprobar si está logueado o no
        public function logged(): void{
            if (!isset($_SESSION['bibliotecario_id'])){
                header('Location: ' . RUTA_URL . '/paginas/login');
                exit();
            }
        }

        //Pagina de inicio, tienes que estar logueado
        public function index(){

            $this->logged();

            $datos = [
                'titulo' => NOMBRESITIO,
            ];

            $this->vista('paginas/inicio', $datos);    
        }

        // Muestra el formulario de login(GET) y lo procesa (POST)
        public function login(): void{
            //Si ya está logueado no necesita ver el login
            if (isset($_SESSION['bibliotecario'])){
                header('Location: ' . RUTA_URL . '/paginas/index');
                exit();
            }

            $error = '';

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                //1ª primera capa es trim() para eliminar cualquier espacio
                $email = trim($_POST['email'] ?? '');
                $clave = trim($_POST['clave'] ?? '');

                if(empty($email) || empty($clave)) {
                    $error = 'Por favor rellena todos los campos.';
                }else{
                    // Usamos el modelo Bibliotecario para consultar en la BD
                    $modeloBibliotecario = $this->modelo('Bbliotecario');
                    $bibliotecario = $modeloBibliotecario->login($email, $clave);

                    if($bibliotecario){
                        //Credenciales correctas: guardamos en sesion
                        $_SESSION['bibliotecario_id'] = $bibliotecario->id;
                        $_SESSION['bibliotecario_nombre'] = $bibliotecario->nombre;

                        header('Location: ' . RUTA_URL . '/paginas/index');
                        exit();
                    }else{
                        $error = 'Email o clave incorrectos';
                    }
                }
            }

            $datos = [
                'titulo' => 'Login - ' . NOMBRESITIO,
                'error' => $error,
            ];

            $this->vista('paginas/login', $datos);

        }

        //Destruye la sesion
        public function logout(): void{
            session_unset();
            session_destroy();

            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }

    }