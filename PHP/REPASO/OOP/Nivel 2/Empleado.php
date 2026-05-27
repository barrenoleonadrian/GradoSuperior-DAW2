<?php
    class Empleado{
        protected string $nombre;
        protected int $salario;

        public function __construct(string $nombre, int $salario){
            $this->nombre = $nombre;
            $this->salario = $salario;
        }

        public function mostrarInfo(): string{
            return "Empleado: {$this->nombre} - {$this->salario}";
        }

    }

    class Programador extends Empleado{
        private string $lenguaje;

        public function __construct(string $nombre, int $salario, string $lenguaje){
            parent::__construct($nombre, $salario);
            $this->lenguaje = $lenguaje;
        }

        public function getNombre(): string {
            return $this->nombre;
        }

        public function getSalario(): int {
            return $this->salario;
        }

        public function mostrarInfo(): string{
            return "Programador: {$this->getNombre} - {$this->getSalario} - {$this->lenguaje}";
        }
    }