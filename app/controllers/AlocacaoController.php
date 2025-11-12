<?php
require_once __DIR__ . '/../models/AlocacaoModel.php';
require_once __DIR__ . '/../models/AtividadeModel.php';

class AlocacaoController {
    private $model;
    private $atividadeModel;
    public function __construct() {
        $this->model = new AlocacaoModel();
        $this->atividadeModel = new AtividadeModel();
    }

    private function getJsonInput() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data ?: [];
    }

    // POST /api/alocacao/allocate
    // body: { "atividade_id": 1, "acessibilidade_req": 1 (opt) }
    public function allocate() {
        $data = $this->getJsonInput();
        if (empty($data['atividade_id'])) { http_response_code(400); echo json_encode(['error'=>'atividade_id é obrigatório']); return; }
        $atividade = $this->atividadeModel->getById($data['atividade_id']);
        if (!$atividade) { http_response_code(404); echo json_encode(['error'=>'Atividade não encontrada']); return; }
        $payload = [
            'inicio' => $atividade['inicio'],
            'fim' => $atividade['fim'],
            'previsao_participantes' => $atividade['previsao_participantes'],
            'precisa_pratica' => $atividade['precisa_pratica'],
            'acessibilidade_req' => !empty($data['acessibilidade_req'])?1:0
        ];
        $sala = $this->model->findSalaParaAtividade($payload);
        if (!$sala) { http_response_code(409); echo json_encode(['error'=>'Nenhuma sala disponível que atenda os requisitos']); return; }
        $aloc_id = $this->model->criarAlocacao($data['atividade_id'], $sala['id']);
        echo json_encode(['success'=>true, 'alocacao_id'=>$aloc_id, 'sala'=>$sala]);
    }

    public function list() {
        $rows = $this->model->getAll();
        echo json_encode(['data'=>$rows]);
    }

    public function cancel($id) {
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID requerido']); return; }
        $ok = $this->model->cancel($id);
        echo json_encode(['success'=>$ok]);
    }
}
