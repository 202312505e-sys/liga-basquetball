<?php
class Estadistica
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConexion();
    }

    public function guardar(array $datos): bool
    {
        $sql = 'INSERT INTO estadisticas_jugador (partido_id, jugador_id, puntos, rebotes, asistencias)
                VALUES (?, ?, ?, ?, ?)';

        return $this->pdo->prepare($sql)->execute([
            $datos['partido_id'],
            $datos['jugador_id'],
            $datos['puntos'],
            $datos['rebotes'],
            $datos['asistencias'],
        ]);
    }
}