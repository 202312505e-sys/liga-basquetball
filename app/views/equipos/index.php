<section class="tarjeta">
    <div class="cabecera-lista">
        <h1>Equipos</h1>
        <?php if (esAdmin()): ?>
            <a class="boton" href="<?= e(url('equipo', 'crear')) ?>">Nuevo equipo</a>
        <?php endif; ?>
    </div>

    <?php if (!$equipos): ?>
        <p class="suave">Todavía no hay equipos registrados.</p>
    <?php else: ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Entrenador</th>
                    <th>Fundado</th>
                    <?php if (esAdmin()): ?><th>Acciones</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><a href="<?= e(url('jugador', 'index', ['equipo_id' => $equipo['id']])) ?>"><?= e($equipo['nombre']) ?></a></td>
                    <td><?= e($equipo['ciudad']) ?></td>
                    <td><?= e($equipo['entrenador']) ?></td>
                    <td><?= e($equipo['fundado_en']) ?></td>
                    <?php if (esAdmin()): ?>
                        <td class="acciones">
                            <a href="<?= e(url('equipo', 'editar', ['id' => $equipo['id']])) ?>">Editar</a>
                            <form method="post" action="<?= e(url('equipo', 'eliminar')) ?>"
                                  onsubmit="return confirm('¿Eliminar este equipo? También se borrarán sus jugadores.');">
                                <input type="hidden" name="id" value="<?= e($equipo['id']) ?>">
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