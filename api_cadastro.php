<?php
// api_cadastro.php

// 1. Permite a origem exata do seu front-end (Live Server)
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");

// 2. Permite que o navegador envie cookies (essencial para sessões)
header("Access-Control-Allow-Credentials: true");

// 3. Define os métodos permitidos
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// 4. Define os cabeçalhos (headers) permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 5. Responde ao "preflight" (requisição OPTIONS)
// Esta é a parte que corrige o seu erro!
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Responde com OK
    exit; // Para a execução, pois foi só uma verificação
}

// -----------------------------------------------------------
// O resto do seu código começa AQUI:
// Ex:
// header("Content-Type: application/json"); // (Este já não precisa estar no topo)
require_once __DIR__ . '/controllers/AuthController.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new AuthController();
    $response = $controller->register($data);

    http_response_code($response['status']);
    echo json_encode(['message' => $response['message']]);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Método não permitido.']);
}