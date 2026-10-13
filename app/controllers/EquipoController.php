<?php
class EquipoController
{
    private Equipo $modelo;

    public function __construct()
    {
        $this->modelo = new Equipo();
    }

    public function index(): void
    {
        vista('equipos/index', [
            'titulo'  => 'Equipos',
            'equipos' => $this->modelo->todos(),
        ]);
    }

    public function crear(): void
    {
        requerirAdmin();

        $equipo  = ['nombre' => '', 'ciudad' => '', 'entrenador' => '', 'fundado_en' => ''];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $equipo  = $this->leerFormulario();
            $errores = $this->validar($equipo);

            if (!$errores) {
                $this->modelo->crear($this->normalizar($equipo));
                flash('exito', 'Equipo creado correctamente.');
                redirigir('equipo');
            }
        }

        vista('equipos/formulario', [
            'titulo'     => 'Nuevo equipo',
            'equipo'     => $equipo,
            'errores'    => $errores,
            'accion'     => url('equipo', 'crear'),
            'textoBoton' => 'Crear equipo',
        ]);
    }

    public function editar(): void
    {
        requerirAdmin();

        $id     = (int) ($_GET['id'] ?? 0);
        $actual = $this->modelo->buscar($id);

        if (!$actual) {
            flash('error', 'El equipo no existe.');
            redirigir('equipo');
        }

        $equipo  = $actual;
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $equipo  = $this->leerFormulario();
            $errores = $this->validar($equipo, $id);

            if (!$errores) {
                $this->modelo->actualizar($id, $this->normalizar($equipo));
                flash('exito', 'Equipo actualizado correctamente.');
                redirigir('equipo');
            }
        }

        vista('equipos/formulario', [
            'titulo'     => 'Editar equipo',
            'equipo'     => $equipo,
            'errores'    => $errores,
            'accion'     => url('equipo', 'editar', ['id' => $id]),
            'textoBoton' => 'Guardar cambios',
        ]);
    }

    public function eliminar(): void
    {
        requerirAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('equipo');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->modelo->buscar($id)) {
            flash('error', 'El equipo no existe.');
            redirigir('equipo');
        }

        try {
            $this->modelo->eliminar($id);
            flash('exito', 'Equipo eliminado.');
        } catch (PDOException $error) {
            flash('error', 'No se puede eliminar: el equipo tiene partidos registrados.');
        }

        redirigir('equipo');
    }

    private function leerFormulario(): array
    {
        return [
            'nombre'     => trim($_POST['nombre'] ?? ''),
            'ciudad'     => trim($_POST['ciudad'] ?? ''),
            'entrenador' => trim($_POST['entrenador'] ?? ''),
            'fundado_en' => trim($_POST['fundado_en'] ?? ''),
        ];
    }

    private function validar(array $datos, int $id = 0): array
    {
        $errores = [];

        if ($datos['nombre'] === '') {
            $errores[] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($datos['nombre']) > 100) {
            $errores[] = 'El nombre no puede pasar de 100 caracteres.';
        } elseif ($this->modelo->existeNombre($datos['nombre'], $id)) {
            $errores[] = 'Ya existe un equipo con ese nombre.';
        }

        if ($datos['ciudad'] === '') {
            $errores[] = 'La ciudad es obligatoria.';
        } elseif (mb_strlen($datos['ciudad']) > 80) {
            $errores[] = 'La ciudad no puede pasar de 80 caracteres.';
        }

        if (mb_strlen($datos['entrenador']) > 100) {
            $errores[] = 'El nombre del entrenador no puede pasar de 100 caracteres.';
        }

        if ($datos['fundado_en'] !== '') {
            $anio = filter_var($datos['fundado_en'], FILTER_VALIDATE_INT);

            if ($anio === false || $anio < 1900 || $anio > (int) date('Y')) {
                $errores[] = 'El año de fundación debe estar entre 1900 y ' . date('Y') . '.';
            }
        }

        return $errores;
    }

    private function normalizar(array $datos): array
    {
        $datos['entrenador'] = $datos['entrenador'] === '' ? null : $datos['entrenador'];
        $datos['fundado_en'] = $datos['fundado_en'] === '' ? null : (int) $datos['fundado_en'];

        return $datos;
    }
}