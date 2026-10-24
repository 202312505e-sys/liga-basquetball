<section class="tarjeta">
    <a class="volver" href="<?= e(url('equipo')) ?>">← Volver a equipos</a>

    <div class="cabecera-lista">
        <h1>Jugadores de <?= e($equipo['nombre']) ?></h1>
        <?php if (esAdmin()): ?>
            <a class="boton" href="<?= e(url('jugador', 'crear', ['equipo_id' => $equipo['id']])) ?>">Nuevo jugador</a>
        <?php endif; ?>
    </div>

    <?php if (!$jugadores): ?>
        <p class="suave">Este equipo todavía no tiene jugadores.</p>
    <?php else: ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Dorsal</th>
                    <th>Jugador</th>
                    <th>Posición</th>
                    <th>Estatura</th>
                    <th>Nacimiento</th>
                    <?php if (esAdmin()): ?><th>Acciones</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($jugadores as $j): ?>
                <tr>
                    <td><?= e($j['dorsal']) ?></td>
                    <td><?= e($j['nombre'] . ' ' . $j['apellido']) ?></td>
                    <td><?= e($j['posicion']) ?></td>
                    <td><?= $j['estatura_cm'] !== null ? e($j['estatura_cm']) . ' cm' : '' ?></td>
                    <td><?= e($j['fecha_nacimiento']) ?></td>
                    <?php if (esAdmin()): ?>
                        <td class="acciones">
                            <a href="<?= e(url('jugador', 'editar', ['id' => $j['id']])) ?>">Editar</a>
                            <form method="post" action="<?= e(url('jugador', 'eliminar')) ?>"
                                  onsubmit="return confirm('¿Eliminar este jugador? También se borrarán sus estadísticas.');">
                                  <?= csrfCampo() ?>
                                <input type="hidden" name="id" value="<?= e($j['id']) ?>">
                                <button class="boton-peligro" type="submit">Eliminar</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>