<section class="tarjeta">
    <h1>Estadísticas</h1>

    <nav class="pestanas">
        <a href="<?= e(url('estadistica', 'posiciones')) ?>">Tabla de posiciones</a>
        <a class="activa" href="<?= e(url('estadistica', 'anotadores')) ?>">Máximos anotadores</a>
    </nav>

    <?php if (!$anotadores): ?>
        <p class="suave">Todavía no hay estadísticas de jugadores registradas.</p>
    <?php else: ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Equipo</th>
                    <th>Partidos</th>
                    <th>Puntos</th>
                    <th>Promedio</th>
                    <th>Rebotes</th>
                    <th>Asistencias</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($anotadores as $i => $a): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($a['nombre'] . ' ' . $a['apellido']) ?></td>
                    <td><?= e($a['equipo']) ?></td>
                    <td><?= e($a['partidos']) ?></td>
                    <td><strong><?= e($a['total']) ?></strong></td>
                    <td><?= e($a['promedio']) ?></td>
                    <td><?= e($a['rebotes']) ?></td>
                    <td><?= e($a['asistencias']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>