<?php
require_once __DIR__ . '/../models/SalaModel.php';

class SalaController {
    private $model;
    public function __construct() {
        $this->model = new SalaModel();
    }

    private function getJsonInput() {
        $data = json_decode(file_get_contents('php://input'), true);
        return $data ?: [];
    }

    public function list() {
        $rows = $this->model->getAll();
        echo json_encode(['data'=>$rows]);
    }

    public function get($id) {
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID requerido']); return; }
        $row = $this->model->getById($id);
        if (!$row) { http_response_code(404); echo json_encode(['error'=>'Sala não encontrada']); return; }
        echo json_encode(['data'=>$row]);
    }

    public function create() {
        $data = $this->getJsonInput();
        if (empty($data['nome']) || empty($data['capacidade'])) {
            http_response_code(400); echo json_encode(['error'=>'nome e capacidade obrigatórios']); return;
        }
        $id = $this->model->create($data);
        echo json_encode(['success'=>true, 'id'=>$id]);
    }

    public function update($id) {
        $data = $this->getJsonInput();
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID requerido']); return; }
        $ok = $this->model->update($id, $data);
        echo json_encode(['success'=>$ok]);
    }

    public function delete($id) {
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID requerido']); return; }
        $ok = $this->model->delete($id);
        echo json_encode(['success'=>$ok]);
    }
}
