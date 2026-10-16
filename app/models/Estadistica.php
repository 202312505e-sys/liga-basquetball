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

        public function tablaPosiciones(): array
    {
        $sql = "SELECT t.*,
                       t.pj - t.pg AS pp,
                       t.pf - t.pc AS dif,
                       t.pg * 2 + (t.pj - t.pg) AS pts
                FROM (
                    SELECT e.id, e.nombre,
                           COUNT(r.equipo_id)         AS pj,
                           COALESCE(SUM(r.gano), 0)   AS pg,
                           COALESCE(SUM(r.pf), 0)     AS pf,
                           COALESCE(SUM(r.pc), 0)     AS pc
                    FROM equipos e
                    LEFT JOIN (
                        SELECT local_id AS equipo_id,
                               puntos_local AS pf,
                               puntos_visitante AS pc,
                               (puntos_local > puntos_visitante) AS gano
                        FROM partidos
                        WHERE estado = 'jugado'
                        UNION ALL
                        SELECT visitante_id,
                               puntos_visitante,
                               puntos_local,
                               (puntos_visitante > puntos_local)
                        FROM partidos
                        WHERE estado = 'jugado'
                    ) r ON r.equipo_id = e.id
                    GROUP BY e.id, e.nombre
                ) t
                ORDER BY pts DESC, dif DESC, pf DESC, nombre";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function maximosAnotadores(int $limite = 10): array
    {
        $sql = 'SELECT j.id, j.nombre, j.apellido, e.nombre AS equipo,
                       COUNT(s.id)               AS partidos,
                       SUM(s.puntos)             AS total,
                       ROUND(AVG(s.puntos), 1)   AS promedio,
                       SUM(s.rebotes)            AS rebotes,
                       SUM(s.asistencias)        AS asistencias
                FROM estadisticas_jugador s
                JOIN jugadores j ON s.jugador_id = j.id
                JOIN equipos e   ON j.equipo_id = e.id
                GROUP BY j.id, j.nombre, j.apellido, e.nombre
                ORDER BY total DESC, promedio DESC, j.apellido
                LIMIT ?';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}