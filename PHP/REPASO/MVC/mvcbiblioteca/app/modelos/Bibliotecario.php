<?php
namespace Dwes\Biblioteca;

class Bibliotecario extends Controlador{
    private DB $db;

    public function __construct(){
        $this->db = new Db();
    }

    //Buscar bibliotecario con los campos que cojas en el login
    public function login(string $email, string $pass): ?object{
        $this->db->query('SELECT * FROM bibliotecarios WHERE email = :email AND pass = :pas');
        $this->db->bind(':email', $email);
        $this->db->bind(':pass', pass);
        return $this->db->registro();
    }
}