<?php

class Coche {

    private string $marca;
    private string $modelo;
    private int $velocidad;

    public function __construct(string $marca, string $modelo){
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->velocidad = 0;
    }

    public function acelerar(int $cantidad): void{
        $this->velocidad += $cantidad;
    }

    public function frenar(int $cantidad): void{

        $this->velocidad -= $cantidad;

        if($this->velocidad < 0){
            $this->velocidad = 0;
        }
    }

    public function mostrarVelocidad(): string{
        return "La velocidad actual es {$this->velocidad} km/h";
    }
}

$coche = new Coche("BMW", "M3");

$coche->acelerar(50);
$coche->acelerar(30);
$coche->frenar(20);

echo $coche->mostrarVelocidad();