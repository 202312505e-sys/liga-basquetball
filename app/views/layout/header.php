<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Liga de Básquetbol') ?> | Liga de Básquetbol</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="barra">
    <div class="contenedor barra-interior">
        <a class="marca" href="<?= url('inicio') ?>">
            <img src="img/icono.png" alt="Logo de la Liga de Básquetbol">
            <span>Liga de Básquetbol</span>
        </a>
        <nav>
            <a href="<?= url('inicio') ?>">Inicio</a>
            <a href="<?= url('equipo') ?>">Equipos</a>
            <a href="<?= url('partido') ?>">Partidos</a>
            <a href="<?= url('estadistica', 'posiciones') ?>">Estadísticas</a>
            <?php if (estaLogueado()): ?>
                <span class="usuario"><?= e($_SESSION['usuario_nombre']) ?></span>
                <a href="<?= url('auth', 'logout') ?>">Salir</a>
            <?php else: ?>
                <a href="<?= url('auth', 'login') ?>">Iniciar sesión</a>
                <a href="<?= url('auth', 'registro') ?>">Registrarse</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="contenedor">
<?php foreach (flashes() as $mensaje): ?>
    <div class="alerta alerta-<?= e($mensaje['tipo']) ?>"><?= e($mensaje['mensaje']) ?></div>
<?php endforeach; ?>
