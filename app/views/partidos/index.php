<section class="tarjeta">
    <div class="cabecera-lista">
        <h1>Partidos</h1>
        <?php if (esAdmin()): ?>
            <a class="boton" href="<?= e(url('partido', 'programar')) ?>">Programar partido</a>
        <?php endif; ?>
    </div>

    <?php if (!$partidos): ?>
        <p class="suave">Todavía no hay partidos programados.</p>
    <?php else: ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Local</th>
                    <th>Marcador</th>
                    <th>Visitante</th>
                    <th>Lugar</th>
                    <th>Estado</th>
                    <?php if (esAdmin()): ?><th>Acciones</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($partidos as $p): ?>
                <tr>
                    <td><?= e(date('d/m/Y H:i', strtotime($p['fecha']))) ?></td>
                    <td><?= e($p['local']) ?></td>
                    <td class="marcador">
                        <?= $p['estado'] === 'jugado' ? e($p['puntos_local']) . ' - ' . e($p['puntos_visitante']) : '—' ?>
                    </td>
                    <td><?= e($p['visitante']) ?></td>
                    <td><?= e($p['lugar']) ?></td>
                    <td><span class="etiqueta etiqueta-<?= e($p['estado']) ?>"><?= e(ucfirst($p['estado'])) ?></span></td>
                    <?php if (esAdmin()): ?>
                        <td>
                            <?php if ($p['estado'] === 'programado'): ?>
                                <a href="<?= e(url('partido', 'registrarResultado', ['id' => $p['id']])) ?>">Registrar resultado</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>