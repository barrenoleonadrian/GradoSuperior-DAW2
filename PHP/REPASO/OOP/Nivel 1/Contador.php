<?php
    class Contador{
        private int $contador;

        public function __construct(){
            $this->contador = 0;
        }

        public function incrementar(): void{
            $this->contador++;
        }

        public function decrementar(): void{
            $this->contador--;

            if ($this->contador < 0){
                $this->contador = 0;
            }
        }

        public function mostrar(): string{
            return "Contador actual: {$this->contador}"
        }

        public function reiniciar(): void{
            $this->contador = 0;
        }
    }

    $contador = new Contador();

    $contador->incrementar();
    $contador->incrementar();
    $contador->incrementar();

    $contador->decrementar();

    echo $contador->mostrar();