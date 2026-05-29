<?php
namespace Dwes\Biblioteca;

class Autor extends Controlador{

    private Db $db;

    public function __construct(){
        //instanciamos la clase Db para conectarnos a la BD
        $this->db = new Db();
    }

    //Devuelve todos los autores
    public function getAutores(): array{
        $this->db->query('SELECT * FROM autores ORDER BY apellidos');
        return $this->db->registro(); 
    }

    //Devuelve un autor por su id
    public function getAutorPorId(int $id): ?object{
        $this->db->query('SELECT * FROM autores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    //insertar nuevo autor
    public function insertarAutor(string $nombre, string $apellido, string $pais): bool{
        $this->db->query('INSERT INTO autores (nombre, apellido, pais) VALUES (:nombre, :apellido, :pais)');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':apellido', $apellido);
        $this->db->bind(':pais', $pais);
        return $this->db->execute();
    }

    //Eliminar autor
    public function eliminarAutor(int $id): bool{
        $this->db->query('DELETE FROM autores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    //Solo id + nombre + apellidos para el <select> del formulario de libros
    public function getAutoresParaSelector(): array{
        $this->db->query('SELECT id, nombre, apellido FROM autores ORDER BY apellido');
        return $this->db->registros();
    }

}