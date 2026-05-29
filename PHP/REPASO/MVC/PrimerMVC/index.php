<?php

session_start();

require_once "controllers/usuariosController.php";

$controller = new UsuariosController();

$controller->index();