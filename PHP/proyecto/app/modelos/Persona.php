<?php
namespace Dwes\Clinica;

/**
 * Modelo Persona – gestiona la tabla 'personas' (dueños de mascotas).
 */
class Persona extends Controlador
{
    private Db $db;

    public function __construct()
    {
        // Instanciamos la clase Db del framework para conectarnos a la BD
        $this->db = new Db();
    }

    // Devuelve TODAS las personas ordenadas por nombre
    public function getPersonas(): array
    {
        $this->db->query('SELECT * FROM personas ORDER BY nombre');
        return $this->db->registros(); // array de objetos
    }

    // Devuelve UNA persona por su id
    public function getPersonaPorId(int $id): ?object
    {
        // Usamos instrucción preparada para evitar inyección SQL
        $this->db->query('SELECT * FROM personas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->registro(); // objeto o null
    }

    // Inserta una nueva persona con instrucción preparada por nombre
    public function insertarPersona(string $nombre, string $apellidos, string $telefono, string $email): bool
    {
        $this->db->query(
            'INSERT INTO personas (nombre, apellidos, telefono, email)
             VALUES (:nombre, :apellidos, :telefono, :email)'
        );
        $this->db->bind(':nombre',    $nombre);
        $this->db->bind(':apellidos', $apellidos);
        $this->db->bind(':telefono',  $telefono);
        $this->db->bind(':email',     $email);
        return $this->db->execute();
    }

    // Elimina una persona por id
    public function eliminarPersona(int $id): bool
    {
        $this->db->query('DELETE FROM personas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Devuelve id + nombre + apellidos (para el <select> del formulario de mascotas)
    public function getPersonasParaSelector(): array
    {
        $this->db->query('SELECT id, nombre, apellidos FROM personas ORDER BY nombre');
        return $this->db->registros();
    }
}
