<?php
    class Mascota{
        private string $nombre;
        private string $tipo;
        private int $edad;

        public function __construct(string $nombre, string $tipo, int $edad){
            $this->nombre = $nombre;
            $this->tipo = $tipo;
            $this->edad = $edad;
        }

        public function presentarse(): string{
            return "Soy {$this->nombre}, un {$this->tipo} de {$this->edad} años";
        }

        public function cumplirAnios(): void{
            $this->edad++;
        }

        public function esMayor(): bool{
            if($this->edad >= 7){
                return true;
            }
                return false;
            
        }
    }