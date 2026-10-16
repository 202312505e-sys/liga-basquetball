<?php
class Partido
{
    private const CONSULTA = 'SELECT p.*, l.nombre AS local, v.nombre AS visitante
                              FROM partidos p
                              JOIN equipos l ON p.local_id = l.id
                              JOIN equipos v ON p.visitante_id = v.id';

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConexion();
    }

    public function todos(): array
    {
        return $this->pdo->query(self::CONSULTA . ' ORDER BY p.fecha DESC')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::CONSULTA . ' WHERE p.id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function programar(array $datos): bool
    {
        $sql = 'INSERT INTO partidos (local_id, visitante_id, fecha, lugar) VALUES (?, ?, ?, ?)';

        return $this->pdo->prepare($sql)->execute([
            $datos['local_id'],
            $datos['visitante_id'],
            $datos['fecha'],
            $datos['lugar'],
        ]);
    }

    public function registrarResultado(int $id, int $pl, int $pv): bool
    {
        $sql = "UPDATE partidos
                SET puntos_local = ?, puntos_visitante = ?, estado = 'jugado'
                WHERE id = ? AND estado = 'programado'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$pl, $pv, $id]);

        return $stmt->rowCount() === 1;
    }

    public function enTransaccion(callable $accion): void
    {
        $this->pdo->beginTransaction();

        try {
            $accion();
            $this->pdo->commit();
        } catch (Throwable $error) {
            $this->pdo->rollBack();
            throw $error;
        }
    }
}