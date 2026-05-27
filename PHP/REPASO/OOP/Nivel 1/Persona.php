<?php
    class Persona{
        public string $nombe;
        private int $edad;

        public function __construct(string $nombre, int $edad){
            $this->nombre = $nombre;
            $this->edad = $edad;
        }

        public function saludar(): string{
            return "Hola, soy {$this->nombre} y tengo {$this->edad}";
        }
    }