<?php

    class EjemploControlador extends Controlador{

        public function __construct(){
            $this->articuloModelo = $this->modelo('Articulo');
            //echo 'Controlador páginas cargado'.'<br>';
        }

        public function index(){

            $articulos = $this->articuloModelo->obtenerArticulos();
            $datos = [
                'titulo' => 'ED 23-24',
                'articulos' => $articulos
            ];

            $this->vista('paginas/ejemplo-inicio', $datos);    
        }
        public function articulo(){
            $this->vista('paginas/articulo');
        }

        public function actualizar($num_registro){
            echo $num_registro;
        }
        

    }