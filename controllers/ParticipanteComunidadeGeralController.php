<?php
// /controllers/ParticipanteComunidadeGeralController.php

// Inclui o middleware
require_once __DIR__ . '/../auth/AuthMiddleware.php';

class ParticipanteComunidadeGeralController {
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
                    // GET (leitura) continua público
                    if ($id) {
                        // ... (código do GET ID)
                    } else {
                        // ... (código do GET ALL)
                    }
                    break;
                
                case 'POST':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO
                    
                    $data = json_decode(file_get_contents('php://input'), true);
                    // ... (resto do código do POST)
                    $this->model->create($data);
                    // ...
                    break;

                case 'PUT':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO

                    if (!$id) {
                        // ...
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    // ... (resto do código do PUT)
                    $this->model->update($id, $data);
                    // ...
                    break;

                case 'DELETE':
                    AuthMiddleware::checkAuth(); // <-- CHECAGEM DE AUTENTICAÇÃO

                    if (!$id) {
                        // ...
                    }
                    $this->model->delete($id);
                    // ...
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

    // Copie e cole a função jsonResponse() do outro controller aqui
    // ...
    // private function jsonResponse(...) { ... }
}
?>