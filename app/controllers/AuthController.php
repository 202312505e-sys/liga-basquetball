<?php
class AuthController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $usuario = (new Usuario())->buscarPorEmail($email);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id']     = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol']    = $usuario['rol'];

                flash('exito', 'Bienvenido, ' . $usuario['nombre']);
                redirigir('inicio');
            }

            flash('error', 'Correo o contraseña incorrectos');
        }

        vista('auth/login', ['titulo' => 'Iniciar sesión']);
    }

    public function registro(): void
    {
        $errores = [];
        $nombre  = '';
        $email   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($nombre === '') {
                $errores[] = 'El nombre es obligatorio.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo no es válido.';
            }
            if (strlen($password) < 6) {
                $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
            }

            $modelo = new Usuario();

            if (!$errores && $modelo->buscarPorEmail($email)) {
                $errores[] = 'Ese correo ya está registrado.';
            }

            if (!$errores) {
                $modelo->crear([
                    'nombre'   => $nombre,
                    'email'    => $email,
                    'password' => $password,
                ]);
                flash('exito', 'Cuenta creada. Ya puedes iniciar sesión.');
                redirigir('auth', 'login');
            }
        }

        vista('auth/registro', [
            'titulo'  => 'Registrarse',
            'errores' => $errores,
            'nombre'  => $nombre,
            'email'   => $email,
        ]);
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('inicio');
        }
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nombre'], $_SESSION['usuario_rol']);
        session_regenerate_id(true);

        flash('exito', 'Sesión cerrada.');
        redirigir('inicio');
    }
}