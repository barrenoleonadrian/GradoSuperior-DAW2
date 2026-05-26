<?php
namespace Dwes\Clinica;

/**
 * Modelo Mascota – gestiona la tabla 'mascotas'.
 */
class Mascota extends Controlador
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    // Devuelve todas las mascotas con el nombre del dueño (JOIN con personas)
    public function getMascotas(): array
    {
        $this->db->query(
            'SELECT mascotas.*,
                    personas.nombre   AS nombre_duenio,
                    personas.apellidos AS apellidos_duenio
             FROM mascotas
             JOIN personas ON mascotas.id_persona = personas.id
             ORDER BY mascotas.nombre'
        );
        return $this->db->registros();
    }

    // Devuelve una mascota por id
    public function getMascotaPorId(int $id): ?object
    {
        $this->db->query('SELECT * FROM mascotas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Inserta una mascota nueva
    // $foto_url es la ruta relativa a la imagen subida al servidor
    public function insertarMascota(
        string $nombre,
        string $tipo,
        string $fecha,
        string $foto_url,
        int    $id_persona
    ): bool {
        $this->db->query(
            'INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona)
             VALUES (:nombre, :tipo, :fecha_nacimiento, :foto_url, :id_persona)'
        );
        $this->db->bind(':nombre',           $nombre);
        $this->db->bind(':tipo',             $tipo);
        $this->db->bind(':fecha_nacimiento', $fecha);
        $this->db->bind(':foto_url',         $foto_url);
        $this->db->bind(':id_persona',       $id_persona);
        return $this->db->execute();
    }

    // Elimina una mascota por id (también usada por la API REST)
    public function eliminarMascota(int $id): bool
    {
        $this->db->query('DELETE FROM mascotas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
