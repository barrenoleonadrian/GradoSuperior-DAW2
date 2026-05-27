<?php

interface Trabajador {

    public function trabajar(): string;

    public function descansar(): string;
}

class Programador implements Trabajador {

    public function trabajar(): string{
        return "Programando en PHP";
    }

    public function descansar(): string{
        return "Tomando café";
    }
}

class Diseñador implements Trabajador {

    public function trabajar(): string{
        return "Creando diseños";
    }

    public function descansar(): string{
        return "Inspirándose en Dribbble";
    }
}


$trabajadores = [
    new Programador(),
    new Diseñador()
];

foreach ($trabajadores as $t) {
    echo $t->trabajar() . "<br>";
    echo $t->descansar() . "<br>";
}