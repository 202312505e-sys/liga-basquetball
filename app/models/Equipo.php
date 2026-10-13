<?php
class Equipo
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConexion();
    }

    public function todos(): array
    {
        return $this->pdo->query('SELECT * FROM equipos ORDER BY nombre')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM equipos WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function existeNombre(string $nombre, int $exceptoId = 0): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM equipos WHERE nombre = ? AND id <> ?');
        $stmt->execute([$nombre, $exceptoId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO equipos (nombre, ciudad, entrenador, fundado_en) VALUES (?, ?, ?, ?)';

        return $this->pdo->prepare($sql)->execute([
            $datos['nombre'],
            $datos['ciudad'],
            $datos['entrenador'],
            $datos['fundado_en'],
        ]);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE equipos SET nombre = ?, ciudad = ?, entrenador = ?, fundado_en = ? WHERE id = ?';

        return $this->pdo->prepare($sql)->execute([
            $datos['nombre'],
            $datos['ciudad'],
            $datos['entrenador'],
            $datos['fundado_en'],
            $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        return $this->pdo->prepare('DELETE FROM equipos WHERE id = ?')->execute([$id]);
    }
}