<?php
require_once __DIR__ . '/../../config/Database.php';

class AlocacaoModel {
    private $db;
    const BUFFER_MINUTES = 15; // regra de negócio

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // verifica se sala disponível considerando buffer
    public function isSalaDisponivel($sala_id, $inicio, $fim) {
        $buffer = self::BUFFER_MINUTES * 60; // segundos
        // converter para timestamps
        $inicio_ts = strtotime($inicio) - $buffer;
        $fim_ts = strtotime($fim) + $buffer;

        $sql = "SELECT * FROM alocacoes WHERE sala_id = :sala_id AND NOT (UNIX_TIMESTAMP(fim) <= :inicio_ts OR UNIX_TIMESTAMP(inicio) >= :fim_ts)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sala_id'=>$sala_id,
            ':inicio_ts'=>$inicio_ts,
            ':fim_ts'=>$fim_ts
        ]);
        $row = $stmt->fetch();
        return !$row; // disponível se não encontrou conflito
    }

    // encontra salas candidatas que atendem requisitos e estão livres
    public function findSalaParaAtividade($atividade) {
        // atividade: ['inicio','fim','previsao_participantes', 'precisa_pratica', 'acessibilidade_req' (optional)]
        $sql = "SELECT * FROM salas WHERE capacidade >= :capacidade";
        if (!empty($atividade['precisa_pratica'])) {
            $sql .= " AND pratica = 1";
        }
        if (!empty($atividade['acessibilidade_req'])) {
            $sql .= " AND acessibilidade = 1";
        }
        $sql .= " ORDER BY capacidade ASC"; // preferir menor sala que caiba
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':capacidade'=>$atividade['previsao_participantes'] ?? 0]);
        $candidatas = $stmt->fetchAll();
        foreach ($candidatas as $sala) {
            if ($this->isSalaDisponivel($sala['id'], $atividade['inicio'], $atividade['fim'])) {
                return $sala;
            }
        }
        return null;
    }

    public function criarAlocacao($atividade_id, $sala_id) {
        $sql = "INSERT INTO alocacoes (atividade_id, sala_id, inicio, fim) SELECT :atividade_id, :sala_id, inicio, fim FROM atividades WHERE id = :atividade_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':atividade_id'=>$atividade_id, ':sala_id'=>$sala_id]);
        return $this->db->lastInsertId();
    }

    public function getAll() {
        $sql = "SELECT a.id, a.atividade_id, a.sala_id, a.inicio, a.fim, s.nome as sala_nome, at.titulo as atividade_titulo
                FROM alocacoes a
                JOIN salas s ON s.id = a.sala_id
                JOIN atividades at ON at.id = a.atividade_id
                ORDER BY a.inicio";
        return $this->db->query($sql)->fetchAll();
    }

    public function cancel($id) {
        $sql = "DELETE FROM alocacoes WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id'=>$id]);
    }
}
