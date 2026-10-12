<?php
session_start();
define('RAIZ', dirname(__DIR__));

require RAIZ . '/app/core/helpers.php';

spl_autoload_register(function (string $clase) {
    foreach (['app/core', 'app/controllers', 'app/models'] as $carpeta) {
        $archivo = RAIZ . "/$carpeta/$clase.php";
        if (is_file($archivo)) {
            require $archivo;
            return;
        }
    }
});

$c = $_GET['c'] ?? 'inicio';
$a = $_GET['a'] ?? 'index';

if (!preg_match('/^[a-z]+$/', $c) || !preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $a)) {
    http_response_code(404);
    vista('404', ['titulo' => 'Página no encontrada']);
    exit;
}

if ($c === 'inicio') {
    vista('inicio', ['titulo' => 'Inicio']);
    exit;
}

$clase = ucfirst($c) . 'Controller';

if (!class_exists($clase)) {
    http_response_code(404);
    vista('404', ['titulo' => 'Página no encontrada']);
    exit;
}

$controlador = new $clase();

if (!is_callable([$controlador, $a])) {
    http_response_code(404);
    vista('404', ['titulo' => 'Página no encontrada']);
    exit;
}

$controlador->$a();