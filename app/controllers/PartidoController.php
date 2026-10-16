<?php
class PartidoController
{
    private Partido $partidos;
    private Equipo $equipos;
    private Jugador $jugadores;
    private Estadistica $estadisticas;

    public function __construct()
    {
        $this->partidos     = new Partido();
        $this->equipos      = new Equipo();
        $this->jugadores    = new Jugador();
        $this->estadisticas = new Estadistica();
    }

    public function index(): void
    {
        vista('partidos/index', [
            'titulo'   => 'Partidos',
            'partidos' => $this->partidos->todos(),
        ]);
    }

    public function programar(): void
    {
        requerirAdmin();

        $partido = ['local_id' => '', 'visitante_id' => '', 'fecha' => '', 'lugar' => ''];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partido = [
                'local_id'     => trim($_POST['local_id'] ?? ''),
                'visitante_id' => trim($_POST['visitante_id'] ?? ''),
                'fecha'        => trim($_POST['fecha'] ?? ''),
                'lugar'        => trim($_POST['lugar'] ?? ''),
            ];
            $errores = $this->validarProgramacion($partido);

            if (!$errores) {
                $fecha = DateTime::createFromFormat('Y-m-d\TH:i', $partido['fecha']);

                $this->partidos->programar([
                    'local_id'     => (int) $partido['local_id'],
                    'visitante_id' => (int) $partido['visitante_id'],
                    'fecha'        => $fecha->format('Y-m-d H:i:s'),
                    'lugar'        => $partido['lugar'] === '' ? null : $partido['lugar'],
                ]);
                flash('exito', 'Partido programado correctamente.');
                redirigir('partido');
            }
        }

        vista('partidos/programar', [
            'titulo'  => 'Programar partido',
            'partido' => $partido,
            'errores' => $errores,
            'equipos' => $this->equipos->todos(),
        ]);
    }

    public function registrarResultado(): void
    {
        requerirAdmin();

        $id      = (int) ($_GET['id'] ?? 0);
        $partido = $this->partidos->buscar($id);

        if (!$partido) {
            flash('error', 'El partido no existe.');
            redirigir('partido');
        }

        if ($partido['estado'] !== 'programado') {
            flash('error', 'Ese partido ya tiene el resultado registrado.');
            redirigir('partido');
        }

        $locales    = $this->jugadores->porEquipo((int) $partido['local_id']);
        $visitantes = $this->jugadores->porEquipo((int) $partido['visitante_id']);

        $valores = ['puntos_local' => '', 'puntos_visitante' => '', 'estadisticas' => []];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $enviadas = $_POST['estadisticas'] ?? [];

            $valores = [
                'puntos_local'     => trim($_POST['puntos_local'] ?? ''),
                'puntos_visitante' => trim($_POST['puntos_visitante'] ?? ''),
                'estadisticas'     => is_array($enviadas) ? $enviadas : [],
            ];

            $proceso = $this->procesarResultado($partido, array_merge($locales, $visitantes), $valores);
            $errores = $proceso['errores'];

            if (!$errores) {
                try {
                    $this->partidos->enTransaccion(function () use ($id, $proceso) {
                        if (!$this->partidos->registrarResultado($id, $proceso['pl'], $proceso['pv'])) {
                            throw new RuntimeException('El partido ya no estaba programado.');
                        }

                        foreach ($proceso['filas'] as $fila) {
                            $this->estadisticas->guardar($fila);
                        }
                    });

                    flash('exito', 'Resultado registrado correctamente.');
                    redirigir('partido');
                } catch (Throwable $error) {
                    error_log($error->getMessage());
                    $errores[] = 'No se pudo guardar el resultado. No se hizo ningún cambio.';
                }
            }
        }

        vista('partidos/resultado', [
            'titulo'     => 'Registrar resultado',
            'partido'    => $partido,
            'locales'    => $locales,
            'visitantes' => $visitantes,
            'valores'    => $valores,
            'errores'    => $errores,
        ]);
    }

    private function validarProgramacion(array $datos): array
    {
        $errores = [];

        $localId     = filter_var($datos['local_id'], FILTER_VALIDATE_INT);
        $visitanteId = filter_var($datos['visitante_id'], FILTER_VALIDATE_INT);

        if ($localId === false || !$this->equipos->buscar($localId)) {
            $errores[] = 'Elige un equipo local válido.';
        }

        if ($visitanteId === false || !$this->equipos->buscar($visitanteId)) {
            $errores[] = 'Elige un equipo visitante válido.';
        }

        if ($localId !== false && $localId === $visitanteId) {
            $errores[] = 'Un equipo no puede jugar contra sí mismo.';
        }

        $fecha = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha']);

        if (!$fecha || $fecha->format('Y-m-d\TH:i') !== $datos['fecha']) {
            $errores[] = 'La fecha y la hora no son válidas.';
        }

        if (mb_strlen($datos['lugar']) > 120) {
            $errores[] = 'El lugar no puede pasar de 120 caracteres.';
        }

        return $errores;
    }

    private function procesarResultado(array $partido, array $jugadores, array $valores): array
    {
        $errores = [];

        $pl = $this->entero($valores['puntos_local']);
        $pv = $this->entero($valores['puntos_visitante']);

        if ($pl === null) {
            $errores[] = 'Los puntos del local deben ser un número entre 0 y 300.';
        }

        if ($pv === null) {
            $errores[] = 'Los puntos del visitante deben ser un número entre 0 y 300.';
        }

        if ($pl !== null && $pv !== null && $pl === $pv) {
            $errores[] = 'En básquetbol no hay empates: los puntos deben ser distintos.';
        }

        $localId     = (int) $partido['local_id'];
        $visitanteId = (int) $partido['visitante_id'];
        $sumas       = [$localId => 0, $visitanteId => 0];
        $filas       = [];
        $hayInvalidas = false;

        foreach ($jugadores as $jugador) {
            $datos = $valores['estadisticas'][$jugador['id']] ?? [];

            if (!is_array($datos)) {
                $datos = [];
            }

            $puntos      = $this->leerEstadistica($datos, 'puntos');
            $rebotes     = $this->leerEstadistica($datos, 'rebotes');
            $asistencias = $this->leerEstadistica($datos, 'asistencias');

            if ($puntos === null || $rebotes === null || $asistencias === null) {
                $hayInvalidas = true;
                continue;
            }

            $sumas[(int) $jugador['equipo_id']] += $puntos;

            if ($puntos + $rebotes + $asistencias > 0) {
                $filas[] = [
                    'partido_id'  => (int) $partido['id'],
                    'jugador_id'  => (int) $jugador['id'],
                    'puntos'      => $puntos,
                    'rebotes'     => $rebotes,
                    'asistencias' => $asistencias,
                ];
            }
        }

        if ($hayInvalidas) {
            $errores[] = 'Las estadísticas de los jugadores deben ser números enteros entre 0 y 300.';
        }

        if ($pl !== null && $sumas[$localId] > $pl) {
            $errores[] = 'Los puntos de los jugadores de ' . $partido['local']
                . ' suman ' . $sumas[$localId] . ' y superan el marcador (' . $pl . ').';
        }

        if ($pv !== null && $sumas[$visitanteId] > $pv) {
            $errores[] = 'Los puntos de los jugadores de ' . $partido['visitante']
                . ' suman ' . $sumas[$visitanteId] . ' y superan el marcador (' . $pv . ').';
        }

        return ['errores' => $errores, 'pl' => $pl, 'pv' => $pv, 'filas' => $filas];
    }

    private function entero(string $valor): ?int
    {
        $numero = filter_var($valor, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 300],
        ]);

        return $numero === false ? null : $numero;
    }

    private function leerEstadistica(array $datos, string $campo): ?int
    {
        $valor = trim((string) ($datos[$campo] ?? ''));

        return $valor === '' ? 0 : $this->entero($valor);
    }
}