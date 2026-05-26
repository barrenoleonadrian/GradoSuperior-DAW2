<?php
namespace Dwes\Clinica;

use PDO;
use PDOException;

/**
 * Db – wrapper sobre PDO que el propio framework nos proporciona.
 * Proporciona métodos para preparar, vincular y ejecutar consultas.
 */
class Db
{
    private string $host      = DB_HOST;
    private string $usuario   = DB_USUARIO;
    private string $password  = DB_PASSWORD;
    private string $nombre_db = DB_NOMBRE;

    private $dbh;  // database handler (manejador PDO)
    private $stmt; // sentencia preparada actual
    private string $error = '';

    public function __construct()
    {
        $dsn     = 'mysql:host=' . $this->host . ';dbname=' . $this->nombre_db;
        $opciones = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->usuario, $this->password, $opciones);
            // Soporte de caracteres especiales (tildes, ñ…)
            $this->dbh->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepara una sentencia SQL
    public function query(string $sql): void
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Vincula un parámetro con su valor (detección automática de tipo)
    public function bind(string $parametro, $valor, $tipo = null): void
    {
        if (is_null($tipo)) {
            switch (true) {
                case is_int($valor):   $tipo = PDO::PARAM_INT;  break;
                case is_bool($valor):  $tipo = PDO::PARAM_BOOL; break;
                case is_null($valor):  $tipo = PDO::PARAM_NULL; break;
                default:               $tipo = PDO::PARAM_STR;  break;
            }
        }
        $this->stmt->bindValue($parametro, $valor, $tipo);
    }

    // Ejecuta la sentencia preparada
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    // Número de filas afectadas / devueltas
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    // Devuelve todos los registros como array de objetos
    public function registros(int $fetchMode = PDO::FETCH_OBJ): array
    {
        $this->execute();
        return $this->stmt->fetchAll($fetchMode);
    }

    // Devuelve un único registro como objeto
    public function registro(int $fetchMode = PDO::FETCH_OBJ): mixed
    {
        $this->execute();
        $row = $this->stmt->fetch($fetchMode);
        return $row ?: null;
    }
}
