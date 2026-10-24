<?php
class Database
{
    private static ?PDO $pdo = null;

    public function __construct()
    {
        if (self::$pdo !== null) {
            return;
        }

        $archivo = RAIZ . '/config/database.php';

        if (!is_file($archivo)) {
            die('Falta el archivo config/database.php');
        }

        $cfg = require $archivo;

        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";

        try {
            self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
            http_response_code(500);
            die('No se pudo conectar con la base de datos. Intenta más tarde.');
        }
    }

    public function getConexion(): PDO
    {
        return self::$pdo;
    }
}