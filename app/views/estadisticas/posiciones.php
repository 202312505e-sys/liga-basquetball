<section class="tarjeta">
    <h1>Estadísticas</h1>

    <nav class="pestanas">
        <a class="activa" href="<?= e(url('estadistica', 'posiciones')) ?>">Tabla de posiciones</a>
        <a href="<?= e(url('estadistica', 'anotadores')) ?>">Máximos anotadores</a>
    </nav>

    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>PJ</th>
                <th>PG</th>
                <th>PP</th>
                <th>PF</th>
                <th>PC</th>
                <th>Dif</th>
                <th>Pts</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($posiciones as $i => $fila): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($fila['nombre']) ?></td>
                <td><?= e($fila['pj']) ?></td>
                <td><?= e($fila['pg']) ?></td>
                <td><?= e($fila['pp']) ?></td>
                <td><?= e($fila['pf']) ?></td>
                <td><?= e($fila['pc']) ?></td>
                <td><?= e($fila['dif']) ?></td>
                <td><strong><?= e($fila['pts']) ?></strong></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p class="suave">PJ: partidos jugados · PG: ganados · PP: perdidos · PF: puntos a favor · PC: puntos en contra · Dif: diferencia · Pts: 2 por victoria, 1 por derrota.</p>
</section>