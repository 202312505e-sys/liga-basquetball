<section class="tarjeta">
    <h1>Programar partido</h1>

    <?php foreach ($errores as $error): ?>
        <div class="alerta alerta-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('partido', 'programar')) ?>">
        <label>Equipo local
            <select name="local_id" required>
                <option value="">Elige un equipo</option>
                <?php foreach ($equipos as $eq): ?>
                    <option value="<?= e($eq['id']) ?>" <?= (string) $partido['local_id'] === (string) $eq['id'] ? 'selected' : '' ?>><?= e($eq['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Equipo visitante
            <select name="visitante_id" required>
                <option value="">Elige un equipo</option>
                <?php foreach ($equipos as $eq): ?>
                    <option value="<?= e($eq['id']) ?>" <?= (string) $partido['visitante_id'] === (string) $eq['id'] ? 'selected' : '' ?>><?= e($eq['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Fecha y hora
            <input type="datetime-local" name="fecha" value="<?= e($partido['fecha']) ?>" required>
        </label>
        <label>Lugar (opcional)
            <input type="text" name="lugar" value="<?= e($partido['lugar']) ?>" maxlength="120">
        </label>

        <button class="boton" type="submit">Programar partido</button>
        <a href="<?= e(url('partido')) ?>">Cancelar</a>
    </form>
</section>