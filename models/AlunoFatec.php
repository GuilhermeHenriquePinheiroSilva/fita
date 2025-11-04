<?php
// /models/AlunoFatec.php

class AlunoFatec {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM alunos_fatec");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos_fatec WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO alunos_fatec 
                (nome_completo, email, nacionalidade, cidade, telefone, curso, semestre, data_nascimento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['cidade'] ?? null,
            $data['telefone'] ?? null,
            $data['curso'] ?? null,
            $data['semestre'] ?? null,
            $data['data_nascimento'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE alunos_fatec SET 
                nome_completo = ?, email = ?, nacionalidade = ?, cidade = ?, 
                telefone = ?, curso = ?, semestre = ?, data_nascimento = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['email'],
            $data['nacionalidade'] ?? null,
            $data['cidade'] ?? null,
            $data['telefone'] ?? null,
            $data['curso'] ?? null,
            $data['semestre'] ?? null,
            $data['data_nascimento'] ?? null,
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM alunos_fatec WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>