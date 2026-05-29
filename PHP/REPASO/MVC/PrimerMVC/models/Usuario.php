<?php

class Usuario {


    public function __construct() {

        if(!isset($_SESSION["users"])){
            $_SESSION["users"] = ["Carlos", "Manolo", "Sofia"];
        }

    }

    public function getAll(): array{

        return $_SESSION["users"];
    
    }

    public function create(string $nombre): void{

        array_push($_SESSION["users"], $nombre);

    }

    public function delete(int $index): void{
        
        unset($_SESSION["users"][$index]);
    
    }

    public function update(int $index, string $nombre): void{

        if(isset($_SESSION["users"][$index])){
            $_SESSION["users"][$index]=$nombre;
        }

    }

}
