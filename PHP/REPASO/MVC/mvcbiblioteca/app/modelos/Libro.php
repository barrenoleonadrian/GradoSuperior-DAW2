<?php
namespace Dwes\Biblioteca;

class Libros extends Controlador{
    private Db $db;

    public function __construct(){
        $this->db = new Db();
    }

    //Devolver todos los libros con el nombre del autor
    public function getLibros(): array{
        $this->db->query('SELECT libros.*, autores.nombre AS nombre_autor FROM libros JOIN autores ON libros.id_autor = autores.id');
        return $this->db->registros();
    }

    //insertar Libro
    public function insertarLibro(string $titulo, string $genero, int $anio, int $id_autor):bool{
        $this->db->query('INSERT INTO libros (titulo, genero, anio, id_autor) VALUES (:titulo, :genero, :anio, :id_autor)');
        $this->db->bind(':titulo', $titulo);
        $this->db->bind(':genero', $genero);
        $this->db->bind(':anio', $anio);
        $this->db->bind(':id_autor', $id_autor);
        return $this->db->execute();
    }

    //eliminar libro
    public function eliminarLibro(int $id): bool{
        $this->db->query('DELETE FROM libro WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}