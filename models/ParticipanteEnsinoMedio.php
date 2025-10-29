<?php
// /models/ParticipanteEnsinoMedio.php

class ParticipanteEnsinoMedio {
    private $pdo;

    // Recebe a conexão PDO quando é criado
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM participantes_ensino_medio");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM participantes_ensino_medio WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO participantes_ensino_medio 
                (nome_completo, email, nacionalidade, telefone, serie, cidade, instituicao, data_nascimento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['telefone'] ?? null,
            $data['serie'] ?? null,
            $data['cidade'] ?? null,
            $data['instituicao'] ?? null,
            $data['data_nascimento'] ?? null
        ]);
        return $this->pdo->lastInsertId(); // Retorna o ID do novo participante
    }

    public function update($id, $data) {
        $sql = "UPDATE participantes_ensino_medio SET 
                nome_completo = ?, email = ?, nacionalidade = ?, telefone = ?, 
                serie = ?, cidade = ?, instituicao = ?, data_nascimento = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['telefone'] ?? null,
            $data['serie'] ?? null,
            $data['cidade'] ?? null,
            $data['instituicao'] ?? null,
            $data['data_nascimento'] ?? null,
            $id
        ]);
        return $stmt->rowCount(); // Retorna o número de linhas afetadas
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM participantes_ensino_medio WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>