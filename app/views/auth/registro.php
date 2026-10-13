<section class="tarjeta">
    <h1>Crear cuenta</h1>

    <?php foreach ($errores as $error): ?>
        <div class="alerta alerta-error"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="<?= e(url('auth', 'registro')) ?>">
        <label>Nombre
            <input type="text" name="nombre" value="<?= e($nombre) ?>" required>
        </label>
        <label>Correo
            <input type="email" name="email" value="<?= e($email) ?>" required>
        </label>
        <label>Contraseña (mínimo 6 caracteres)
            <input type="password" name="password" required>
        </label>
        <button class="boton" type="submit">Registrarme</button>
    </form>

    <p class="suave">¿Ya tienes cuenta? <a href="<?= e(url('auth', 'login')) ?>">Inicia sesión</a></p>
</section>