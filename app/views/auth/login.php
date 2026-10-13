<section class="tarjeta">
    <h1>Iniciar sesión</h1>

    <form method="post" action="<?= e(url('auth', 'login')) ?>">
        <label>Correo
            <input type="email" name="email" required>
        </label>
        <label>Contraseña
            <input type="password" name="password" required>
        </label>
        <button class="boton" type="submit">Entrar</button>
    </form>

    <p class="suave">¿No tienes cuenta? <a href="<?= e(url('auth', 'registro')) ?>">Regístrate</a></p>
</section>