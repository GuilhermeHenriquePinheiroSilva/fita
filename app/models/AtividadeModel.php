<?php
require_once __DIR__ . '/../../config/Database.php';

class AtividadeModel {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO atividades (titulo, tipo, inicio, fim, previsao_participantes, precisa_pratica) VALUES (:titulo, :tipo, :inicio, :fim, :previsao_participantes, :precisa_pratica)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo'=>$data['titulo'],
            ':tipo'=>$data['tipo'],
            ':inicio'=>$data['inicio'],
            ':fim'=>$data['fim'],
            ':previsao_participantes'=>$data['previsao_participantes'] ?? 0,
            ':precisa_pratica'=>!empty($data['precisa_pratica'])?1:0
        ]);
        return $this->db->lastInsertId();
    }

    public function getById($id) {
        $sql = "SELECT * FROM atividades WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $sql = "SELECT * FROM atividades ORDER BY inicio";
        return $this->db->query($sql)->fetchAll();
    }
}
