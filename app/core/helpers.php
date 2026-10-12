<?php
function vista(string $nombre, array $datos = []): void
{
    extract($datos);
    require RAIZ . '/app/views/layout/header.php';
    require RAIZ . '/app/views/' . $nombre . '.php';
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