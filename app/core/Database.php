<?php
class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $archivo = RAIZ . '/config/database.php';

        if (!is_file($archivo)) {
            die('Falta el archivo config/database.php');
        }

        $cfg = require $archivo;

        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";

        try {
            $this->pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('No se pudo conectar: ' . htmlspecialchars($e->getMessage()));
        }
    }

    public function getConexion(): PDO
    {
        return $this->pdo;
    }
}