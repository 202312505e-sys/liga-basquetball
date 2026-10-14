<?php
class Jugador
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConexion();
    }

    public function porEquipo(int $equipoId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM jugadores WHERE equipo_id = ? ORDER BY dorsal');
        $stmt->execute([$equipoId]);

        return $stmt->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM jugadores WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function existeDorsal(int $equipoId, int $dorsal, int $exceptoId = 0): bool
    {
        $sql  = 'SELECT COUNT(*) FROM jugadores WHERE equipo_id = ? AND dorsal = ? AND id <> ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$equipoId, $dorsal, $exceptoId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO jugadores (equipo_id, nombre, apellido, dorsal, posicion, estatura_cm, fecha_nacimiento)
                VALUES (?, ?, ?, ?, ?, ?, ?)';

        return $this->pdo->prepare($sql)->execute([
            $datos['equipo_id'],
            $datos['nombre'],
            $datos['apellido'],
            $datos['dorsal'],
            $datos['posicion'],
            $datos['estatura_cm'],
            $datos['fecha_nacimiento'],
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE jugadores
                SET nombre = ?, apellido = ?, dorsal = ?, posicion = ?, estatura_cm = ?, fecha_nacimiento = ?
                WHERE id = ?';

        return $this->pdo->prepare($sql)->execute([
            $datos['nombre'],
            $datos['apellido'],
            $datos['dorsal'],
            $datos['posicion'],
            $datos['estatura_cm'],
            $datos['fecha_nacimiento'],
            $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM jugadores WHERE id = ?')->execute([$id]);
    }
}