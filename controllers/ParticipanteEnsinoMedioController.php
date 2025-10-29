<?php
// /controllers/ParticipanteEnsinoMedioController.php

// Inclui o middleware (precisamos dele nesta classe)
require_once __DIR__ . '/../auth/AuthMiddleware.php';

class ParticipanteEnsinoMedioController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? null;

        try {
            switch ($method) {
                case 'GET':
                    // GET (leitura) pode continuar público
                    if ($id) {
                        $data = $this->model->getById($id);
                        if ($data) {
                            $this->jsonResponse($data);
                        } else {
                            $this->jsonResponse(['error' => 'Participante (EM) não encontrado'], 404);
                        }
                    } else {
                        $data = $this->model->getAll();
                        $this->jsonResponse($data);
                    }
                    break;
                
                case 'POST':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO
                    
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (empty($data['nome_completo']) || empty($data['email'])) {
                        $this->jsonResponse(['error' => 'Nome completo e Email são obrigatórios'], 400);
                    }
                    
                    $newId = $this->model->create($data);
                    $this->jsonResponse(['id' => $newId, 'message' => 'Participante (EM) criado com sucesso'], 201);
                    break;

                case 'PUT':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO

                    if (!$id) {
                        $this->jsonResponse(['error' => 'ID é obrigatório para atualizar'], 400);
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                     if (empty($data['nome_completo']) || empty($data['email'])) {
                        $this->jsonResponse(['error' => 'Nome completo e Email são obrigatórios'], 400);
                    }

                    $this->model->update($id, $data);
                    $this->jsonResponse(['id' => $id, 'message' => 'Participante (EM) atualizado com sucesso']);
                    break;

                case 'DELETE':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO

                    if (!$id) {
                        $this->jsonResponse(['error' => 'ID é obrigatório para deletar'], 400);
                    }
                    $this->model->delete($id);
                    $this->jsonResponse(['id' => $id, 'message' => 'Participante (EM) deletado com sucesso']);
                    break;

                default:
                    $this->jsonResponse(['error' => 'Método não permitido'], 405);
                    break;
            }
        } catch (PDOException $e) {
            $this->jsonResponse(['error' => 'Erro no banco de dados: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            $this->jsonResponse(['error' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
?>