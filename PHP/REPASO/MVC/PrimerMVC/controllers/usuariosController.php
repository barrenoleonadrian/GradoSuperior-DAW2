<?php

    require_once "models/Usuario.php";

    class UsuariosController {

        public function index() {

            $userModel = new User();
            $users = $userModel->getUsers();

            require "views/users.php";
        }
    }