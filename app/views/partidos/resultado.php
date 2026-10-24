<?php
$planteles = [
    ['nombre' => $partido['local'],     'jugadores' => $locales],
    ['nombre' => $partido['visitante'], 'jugadores' => $visitantes],
];
?>
<section class="tarjeta">
    <h1>Registrar resultado</h1>
    <p class="suave">
        <?= e($partido['local']) ?> vs <?= e($partido['visitante']) ?>
        · <?= e(date('d/m/Y H:i', strtotime($partido['fecha']))) ?>
    </p>

    <?php foreach ($errores as $error): ?>
        <div class="alerta alerta-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('partido', 'registrarResultado', ['id' => $partido['id']])) ?>">
        <?= csrfCampo() ?>
        <div class="dos-columnas">
            <label>Puntos de <?= e($partido['local']) ?>
                <input type="number" name="puntos_local" value="<?= e($valores['puntos_local']) ?>" min="0" max="300" required>
            </label>
            <label>Puntos de <?= e($partido['visitante']) ?>
                <input type="number" name="puntos_visitante" value="<?= e($valores['puntos_visitante']) ?>" min="0" max="300" required>
            </label>
        </div>

        <h2>Estadísticas de los jugadores</h2>
        <p class="suave">Llena solo a quienes participaron. Los jugadores que queden en 0 no se guardan.</p>

        <div class="dos-columnas">
            <?php foreach ($planteles as $plantel): ?>
                <div>
                    <h3><?= e($plantel['nombre']) ?></h3>

                    <?php if (!$plantel['jugadores']): ?>
                        <p class="suave">Este equipo no tiene jugadores registrados.</p>
                    <?php else: ?>
                        <table class="tabla">
                            <thead>
                                <tr><th>Jugador</th><th>Pts</th><th>Reb</th><th>Ast</th></tr>
                            </thead>
                            <tbody>
                            <?php foreach ($plantel['jugadores'] as $j): ?>
                                <?php $v = $valores['estadisticas'][$j['id']] ?? []; ?>
                                <tr>
                                    <td>#<?= e($j['dorsal']) ?> <?= e($j['nombre'] . ' ' . $j['apellido']) ?></td>
                                    <td><input type="number" name="estadisticas[<?= e($j['id']) ?>][puntos]" value="<?= e($v['puntos'] ?? 0) ?>" min="0" max="300"></td>
                                    <td><input type="number" name="estadisticas[<?= e($j['id']) ?>][rebotes]" value="<?= e($v['rebotes'] ?? 0) ?>" min="0" max="300"></td>
                                    <td><input type="number" name="estadisticas[<?= e($j['id']) ?>][asistencias]" value="<?= e($v['asistencias'] ?? 0) ?>" min="0" max="300"></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="boton" type="submit">Guardar resultado</button>
        <a href="<?= e(url('partido')) ?>">Cancelar</a>
    </form>
</section>