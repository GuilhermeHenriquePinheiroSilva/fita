<?php
require_once __DIR__ . '/../../config/Database.php';

class SalaModel {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO salas (nome, capacidade, acessibilidade, pratica, descricao) VALUES (:nome, :capacidade, :acessibilidade, :pratica, :descricao)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nome'=>$data['nome'],
            ':capacidade'=>$data['capacidade'],
            ':acessibilidade'=>!empty($data['acessibilidade'])?1:0,
            ':pratica'=>!empty($data['pratica'])?1:0,
            ':descricao'=>$data['descricao'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE salas SET nome=:nome, capacidade=:capacidade, acessibilidade=:acessibilidade, pratica=:pratica, descricao=:descricao WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'=>$data['nome'],
            ':capacidade'=>$data['capacidade'],
            ':acessibilidade'=>!empty($data['acessibilidade'])?1:0,
            ':pratica'=>!empty($data['pratica'])?1:0,
            ':descricao'=>$data['descricao'] ?? null,
            ':id'=>$id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM salas WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }

    public function getAll() {
        $sql = "SELECT * FROM salas ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM salas WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }
}
