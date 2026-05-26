<?php
namespace Dwes\Clinica;

/**
 * Modelo Veterinario – comprueba credenciales en la BD para el login.
 */
class Veterinario extends Controlador
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Busca en la BD un veterinario con ese email y clave.
     * Devuelve el objeto veterinario si las credenciales son correctas,
     * o null si no existe o la clave es incorrecta.
     */
    public function login(string $email, string $clave): ?object
    {
        $this->db->query(
            'SELECT * FROM veterinarios WHERE email = :email AND clave = :clave'
        );
        $this->db->bind(':email', $email);
        $this->db->bind(':clave', $clave);
        return $this->db->registro(); // null si no coincide ningún registro
    }
}
