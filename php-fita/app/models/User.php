<?php
// app/models/User.php

class User {
    private $pdo;

    // Recebe a conexão PDO quando é instanciado
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Encontra um usuário pelo seu email.
     * @param string $email
     * @return mixed Retorna os dados do usuário ou false se não encontrar
     */
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Encontra um usuário pelo seu ID.
     * @param int $id
     * @return mixed
     */
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Cria um novo usuário no banco.
     * @param string $nome
     * @param string $email
     * @param string $hash_senha
     * @return bool Retorna true em sucesso, false em falha (ex: email duplicado)
     */
    public function create($nome, $email, $hash_senha) {
        try {
            // A 'role' usa o DEFAULT 'Participante' do banco
            $stmt = $this->pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
            return $stmt->execute([$nome, $email, $hash_senha]);
        } catch (PDOException $e) {
            // Código 23000 é violação de integridade (ex: email UNIQUE duplicado)
            if ($e->getCode() == 23000) {
                return false; 
            }
            throw $e; // Lança outros erros
        }
    }

    /**
     * Marca o perfil de um usuário como completo.
     * @param int $id
     * @return bool
     */
    public function setProfileComplete($id) {
        $stmt = $this->pdo->prepare("UPDATE users SET perfil_completo = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>