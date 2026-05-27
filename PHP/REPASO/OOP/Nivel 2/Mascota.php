<?php

class Animal {

    protected string $nombre;
    protected int $edad;

    public function __construct(string $nombre, int $edad){
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function hacerSonido(): string{
        return "Sonido genérico";
    }
}

class Perro extends Animal {

    public function hacerSonido(): string{
        return "Guau guau";
    }
}

class Gato extends Animal {

    public function hacerSonido(): string{
        return "Miau";
    }
}

$perro = new Perro("Toby", 4);
$gato = new Gato("Misu", 2);

echo $perro->hacerSonido();
echo "<br>";
echo $gato->hacerSonido();