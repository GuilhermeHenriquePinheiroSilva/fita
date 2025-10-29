<?php
// /models/ParticipanteComunidadeGeral.php

class ParticipanteComunidadeGeral {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM participantes_comunidade_geral");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM participantes_comunidade_geral WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO participantes_comunidade_geral 
                (nome_completo, email, nacionalidade, telefone, grau_escolaridade, cidade, data_nascimento) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['telefone'] ?? null,
            $data['grau_escolaridade'] ?? null,
            $data['cidade'] ?? null,
            $data['data_nascimento'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE participantes_comunidade_geral SET 
                nome_completo = ?, email = ?, nacionalidade = ?, telefone = ?, 
                grau_escolaridade = ?, cidade = ?, data_nascimento = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['telefone'] ?? null,
            $data['grau_escolaridade'] ?? null,
            $data['cidade'] ?? null,
            $data['data_nascimento'] ?? null,
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM participantes_comunidade_geral WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>