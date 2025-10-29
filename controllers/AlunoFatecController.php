<?php
// /controllers/AlunoFatecController.php

// Inclui o middleware de autenticação
require_once __DIR__ . '/../auth/AuthMiddleware.php';

class AlunoFatecController {
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
                    // GET (leitura) é público
                    if ($id) {
                        $data = $this->model->getById($id);
                        if ($data) {
                            $this->jsonResponse($data);
                        } else {
                            $this->jsonResponse(['error' => 'Aluno(a) Fatec não encontrado(a)'], 404);
                        }
                    } else {
                        $data = $this->model->getAll();
                        $this->jsonResponse($data);
                    }
                    break;
                
                case 'POST':
                    AuthMiddleware::checkAuth(); // <-- Rota Protegida
                    
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (empty($data['nome_completo']) || empty($data['email'])) {
                        $this->jsonResponse(['error' => 'Nome completo e Email são obrigatórios'], 400);
                    }
                    
                    $newId = $this->model->create($data);
                    $this->jsonResponse(['id' => $newId, 'message' => 'Aluno(a) Fatec criado(a) com sucesso'], 201);
                    break;

                case 'PUT':
                    AuthMiddleware::checkAuth(); // <-- Rota Protegida

                    if (!$id) {
                        $this->jsonResponse(['error' => 'ID é obrigatório para atualizar'], 400);
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                     if (empty($data['nome_completo']) || empty($data['email'])) {
                        $this->jsonResponse(['error' => 'Nome completo e Email são obrigatórios'], 400);
                    }

                    $this->model->update($id, $data);
                    $this->jsonResponse(['id' => $id, 'message' => 'Aluno(a) Fatec atualizado(a) com sucesso']);
                    break;

                case 'DELETE':
                    AuthMiddleware::checkAuth(); // <-- Rota Protegida

                    if (!$id) {
                        $this->jsonResponse(['error' => 'ID é obrigatório para deletar'], 400);
                    }
                    $this->model->delete($id);
                    $this->jsonResponse(['id' => $id, 'message' => 'Aluno(a) Fatec deletado(a) com sucesso']);
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

    // Função auxiliar para enviar respostas JSON
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
?>