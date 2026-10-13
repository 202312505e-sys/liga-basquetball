<?php
class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConexion();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $fila = $stmt->fetch();

        return $fila ?: null;
    }

    public function crear(array $datos): bool
    {
        $sql = 'INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $datos['nombre'],
            $datos['email'],
            password_hash($datos['password'], PASSWORD_DEFAULT),
            'visitante',
        ]);
    }
}