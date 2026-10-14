<?php
class JugadorController
{
    private const POSICIONES = ['base', 'escolta', 'alero', 'ala-pivot', 'pivot'];

    private Jugador $modelo;
    private Equipo $equipos;

    public function __construct()
    {
        $this->modelo  = new Jugador();
        $this->equipos = new Equipo();
    }

    public function index(): void
    {
        $equipo = $this->equipoDesdeUrl();

        vista('jugadores/index', [
            'titulo'    => 'Jugadores de ' . $equipo['nombre'],
            'equipo'    => $equipo,
            'jugadores' => $this->modelo->porEquipo((int) $equipo['id']),
        ]);
    }

    public function crear(): void
    {
        requerirAdmin();

        $equipo  = $this->equipoDesdeUrl();
        $jugador = [
            'nombre' => '', 'apellido' => '', 'dorsal' => '',
            'posicion' => '', 'estatura_cm' => '', 'fecha_nacimiento' => '',
        ];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jugador = $this->leerFormulario();
            $errores = $this->validar($jugador, (int) $equipo['id']);

            if (!$errores) {
                $datos = $this->normalizar($jugador);
                $datos['equipo_id'] = (int) $equipo['id'];

                $this->modelo->crear($datos);
                flash('exito', 'Jugador agregado correctamente.');
                redirigir('jugador', 'index', ['equipo_id' => $equipo['id']]);
            }
        }

        vista('jugadores/formulario', [
            'titulo'      => 'Nuevo jugador',
            'equipo'      => $equipo,
            'jugador'     => $jugador,
            'errores'     => $errores,
            'posiciones'  => self::POSICIONES,
            'accion'      => url('jugador', 'crear', ['equipo_id' => $equipo['id']]),
            'textoBoton'  => 'Agregar jugador',
        ]);
    }

    public function editar(): void
    {
        requerirAdmin();

        $id     = (int) ($_GET['id'] ?? 0);
        $actual = $this->modelo->buscar($id);

        if (!$actual) {
            flash('error', 'El jugador no existe.');
            redirigir('equipo');
        }

        $equipo  = $this->equipos->buscar((int) $actual['equipo_id']);
        $jugador = $actual;
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jugador = $this->leerFormulario();
            $errores = $this->validar($jugador, (int) $actual['equipo_id'], $id);

            if (!$errores) {
                $this->modelo->actualizar($id, $this->normalizar($jugador));
                flash('exito', 'Jugador actualizado correctamente.');
                redirigir('jugador', 'index', ['equipo_id' => $actual['equipo_id']]);
            }
        }

        vista('jugadores/formulario', [
            'titulo'      => 'Editar jugador',
            'equipo'      => $equipo,
            'jugador'     => $jugador,
            'errores'     => $errores,
            'posiciones'  => self::POSICIONES,
            'accion'      => url('jugador', 'editar', ['id' => $id]),
            'textoBoton'  => 'Guardar cambios',
        ]);
    }

    public function eliminar(): void
    {
        requerirAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('equipo');
        }

        $id      = (int) ($_POST['id'] ?? 0);
        $jugador = $this->modelo->buscar($id);

        if (!$jugador) {
            flash('error', 'El jugador no existe.');
            redirigir('equipo');
        }

        $this->modelo->eliminar($id);
        flash('exito', 'Jugador eliminado.');
        redirigir('jugador', 'index', ['equipo_id' => $jugador['equipo_id']]);
    }

    private function equipoDesdeUrl(): array
    {
        $id     = (int) ($_GET['equipo_id'] ?? 0);
        $equipo = $this->equipos->buscar($id);

        if (!$equipo) {
            flash('error', 'El equipo no existe.');
            redirigir('equipo');
        }

        return $equipo;
    }

    private function leerFormulario(): array
    {
        return [
            'nombre'           => trim($_POST['nombre'] ?? ''),
            'apellido'         => trim($_POST['apellido'] ?? ''),
            'dorsal'           => trim($_POST['dorsal'] ?? ''),
            'posicion'         => trim($_POST['posicion'] ?? ''),
            'estatura_cm'      => trim($_POST['estatura_cm'] ?? ''),
            'fecha_nacimiento' => trim($_POST['fecha_nacimiento'] ?? ''),
        ];
    }

    private function validar(array $datos, int $equipoId, int $jugadorId = 0): array
    {
        $errores = [];

        if ($datos['nombre'] === '') {
            $errores[] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($datos['nombre']) > 80) {
            $errores[] = 'El nombre no puede pasar de 80 caracteres.';
        }

        if ($datos['apellido'] === '') {
            $errores[] = 'El apellido es obligatorio.';
        } elseif (mb_strlen($datos['apellido']) > 80) {
            $errores[] = 'El apellido no puede pasar de 80 caracteres.';
        }

        $dorsal = filter_var($datos['dorsal'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 99],
        ]);

        if ($datos['dorsal'] === '') {
            $errores[] = 'El dorsal es obligatorio.';
        } elseif ($dorsal === false) {
            $errores[] = 'El dorsal debe ser un número entre 0 y 99.';
        } elseif ($this->modelo->existeDorsal($equipoId, $dorsal, $jugadorId)) {
            $errores[] = 'Ese dorsal ya lo usa otro jugador del equipo.';
        }

        if ($datos['posicion'] !== '' && !in_array($datos['posicion'], self::POSICIONES, true)) {
            $errores[] = 'La posición no es válida.';
        }

        if ($datos['estatura_cm'] !== '') {
            $estatura = filter_var($datos['estatura_cm'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 100, 'max_range' => 250],
            ]);

            if ($estatura === false) {
                $errores[] = 'La estatura debe estar entre 100 y 250 cm.';
            }
        }

        if ($datos['fecha_nacimiento'] !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d', $datos['fecha_nacimiento']);

            if (!$fecha
                || $fecha->format('Y-m-d') !== $datos['fecha_nacimiento']
                || $fecha > new DateTime('today')) {
                $errores[] = 'La fecha de nacimiento no es válida.';
            }
        }

        return $errores;
    }

    private function normalizar(array $datos): array
    {
        return [
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'],
            'dorsal'           => (int) $datos['dorsal'],
            'posicion'         => $datos['posicion'] === '' ? null : $datos['posicion'],
            'estatura_cm'      => $datos['estatura_cm'] === '' ? null : (int) $datos['estatura_cm'],
            'fecha_nacimiento' => $datos['fecha_nacimiento'] === '' ? null : $datos['fecha_nacimiento'],
        ];
    }
}