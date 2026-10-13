<?php
function vista(string $archivoVista, array $datos = []): void
{
    extract($datos);
    require RAIZ . '/app/views/layout/header.php';
    require RAIZ . '/app/views/' . $archivoVista . '.php';
    require RAIZ . '/app/views/layout/footer.php';
}

function url(string $c, string $a = 'index', array $params = []): string
{
    return 'index.php?' . http_build_query(array_merge(['c' => $c, 'a' => $a], $params));
}

function redirigir(string $c, string $a = 'index', array $params = []): void
{
    header('Location: ' . url($c, $a, $params));
    exit;
}

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function flashes(): array
{
    $mensajes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $mensajes;
}


//funcion de loggeo
function estaLogueado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function esAdmin(): bool
{
    return ($_SESSION['usuario_rol'] ?? '') === 'admin';
}

//funcion para requerir admin
function requerirAdmin(): void
{
    if (!estaLogueado()) {
        flash('error', 'Debes iniciar sesión para continuar.');
        redirigir('auth', 'login');
    }

    if (!esAdmin()) {
        flash('error', 'No tienes permiso para realizar esta acción.');
        redirigir('inicio');
    }
}