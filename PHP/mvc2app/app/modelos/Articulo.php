<?php
class Articulo {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function getArticulos() {
        // Usamos query() en vez de consulta() porque así lo tiene tu Db.php
        $this->db->query("SELECT * FROM articulos");
        // registros() ejecuta y devuelve todos los resultados como objetos
        return $this->db->registros();
    }
}
?>