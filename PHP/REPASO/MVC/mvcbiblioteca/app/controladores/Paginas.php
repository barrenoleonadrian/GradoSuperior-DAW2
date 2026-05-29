<?php
namespace Usuario\Mvcrecuperacion;

use Usuario\Mvcrecuperacion\Controlador;

    class Paginas extends Controlador{

        public function __construct(){

        }

        public function index(){

            $datos = [
                'titulo' => NOMBRESITIO,
            ];

            $this->vista('paginas/inicio', $datos);    
        }


    }