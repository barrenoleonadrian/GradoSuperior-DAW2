<?php

    require_once "models/Usuario.php";

    class UsuariosController {

        public function index() {

            $userModel = new Usuario();

            if($_SERVER["REQUEST_METHOD"] === "POST"){
                $nombre = $_POST["nombre"];
                $userModel->create($nombre);
            }

            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $userModel->delete($id);
            }

                $users = $userModel->getAll();

            require "views/usuarios.php";
        }
    }