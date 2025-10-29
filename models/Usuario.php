<?php
// models/Usuario.php
require_once __DIR__ . '/../config/db.php'; // Seu arquivo de conexão

class Usuario {
    private $conn;

    public function __construct() {
        // Chamando o método estático getConnection() da classe Database
        $this->conn = Database::getConnection(); 
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($nome, $email, $senhaHash, $nivel) {
        // Insere o usuário
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, nivel_acesso) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash, $nivel]);
        $usuario_id = $this->conn->lastInsertId();

        // Cria um perfil vazio para ele
        $stmt_perfil = $this->conn->prepare("INSERT INTO perfis (usuario_id) VALUES (?)");
        $stmt_perfil->execute([$usuario_id]);

        return $usuario_id;
    }

    public function updateLoginAttempts($email, $tentativas) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET tentativas_falhas = ? WHERE email = ?");
        $stmt->execute([$tentativas, $email]);
    }

    public function lockAccount($email) {
        // Bloqueia por 15 minutos
        $stmt = $this->conn->prepare("UPDATE usuarios SET bloqueado_ate = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $stmt->execute([$email]);
    }

    public function resetLock($email) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET tentativas_falhas = 0, bloqueado_ate = NULL WHERE email = ?");
        $stmt->execute([$email]);
    }

    public function isProfileComplete($usuario_id) {
        $stmt = $this->conn->prepare("SELECT perfil_completo FROM perfis WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
        return $perfil && $perfil['perfil_completo'] == 1;
    }
}