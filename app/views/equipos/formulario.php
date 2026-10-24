<section class="tarjeta">
    <h1><?= e($titulo) ?></h1>

    <?php foreach ($errores as $error): ?>
        <div class="alerta alerta-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e($accion) ?>">
        <?= csrfCampo() ?>
        <label>Nombre
            <input type="text" name="nombre" value="<?= e($equipo['nombre']) ?>" maxlength="100" required>
        </label>
        <label>Ciudad
            <input type="text" name="ciudad" value="<?= e($equipo['ciudad']) ?>" maxlength="80" required>
        </label>
        <label>Entrenador (opcional)
            <input type="text" name="entrenador" value="<?= e($equipo['entrenador']) ?>" maxlength="100">
        </label>
        <label>Año de fundación (opcional)
            <input type="number" name="fundado_en" value="<?= e($equipo['fundado_en']) ?>" min="1900" max="<?= date('Y') ?>">
        </label>

        <button class="boton" type="submit"><?= e($textoBoton) ?></button>
        <a href="<?= e(url('equipo')) ?>">Cancelar</a>
    </form>
</section>