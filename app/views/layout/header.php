<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Liga de Básquetbol') ?> | Liga de Básquetbol</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="barra">
    <div class="contenedor barra-interior">
        <a class="marca" href="<?= url('inicio') ?>">🏀 Liga de Básquetbol</a>
        <nav>
            <a href="<?= url('inicio') ?>">Inicio</a>
        </nav>
    </div>
</header>

<main class="contenedor">
<?php foreach (flashes() as $mensaje): ?>
    <div class="alerta alerta-<?= e($mensaje['tipo']) ?>"><?= e($mensaje['mensaje']) ?></div>
<?php endforeach; ?>