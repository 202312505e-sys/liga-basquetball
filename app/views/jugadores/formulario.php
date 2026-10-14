<section class="tarjeta">
    <h1><?= e($titulo) ?></h1>
    <p class="suave">Equipo: <strong><?= e($equipo['nombre']) ?></strong></p>

    <?php foreach ($errores as $error): ?>
        <div class="alerta alerta-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e($accion) ?>">
        <label>Nombre
            <input type="text" name="nombre" value="<?= e($jugador['nombre']) ?>" maxlength="80" required>
        </label>
        <label>Apellido
            <input type="text" name="apellido" value="<?= e($jugador['apellido']) ?>" maxlength="80" required>
        </label>
        <label>Dorsal (0 a 99)
            <input type="number" name="dorsal" value="<?= e($jugador['dorsal']) ?>" min="0" max="99" required>
        </label>
        <label>Posición (opcional)
            <select name="posicion">
                <option value="">Sin definir</option>
                <?php foreach ($posiciones as $p): ?>
                    <option value="<?= e($p) ?>" <?= $jugador['posicion'] === $p ? 'selected' : '' ?>><?= e(ucfirst($p)) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Estatura en cm (opcional)
            <input type="number" name="estatura_cm" value="<?= e($jugador['estatura_cm']) ?>" min="100" max="250">
        </label>
        <label>Fecha de nacimiento (opcional)
            <input type="date" name="fecha_nacimiento" value="<?= e($jugador['fecha_nacimiento']) ?>" max="<?= date('Y-m-d') ?>">
        </label>

        <button class="boton" type="submit"><?= e($textoBoton) ?></button>
        <a href="<?= e(url('jugador', 'index', ['equipo_id' => $equipo['id']])) ?>">Cancelar</a>
    </form>
</section>