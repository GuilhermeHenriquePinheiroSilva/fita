<?php
// /api_aluno_fatec.php

header("Content-Type: application/json");

// Inclui (importa) as classes necessárias
require_once 'config/db.php';
require_once 'models/AlunoFatec.php';
require_once 'controllers/AlunoFatecController.php';

// Obtém a conexão com o banco
$pdo = Database::getConnection();

// "Injeção de Dependência":
$model = new AlunoFatec($pdo);
$controller = new AlunoFatecController($model);

// Pede ao Controller para processar a requisição
$controller->handleRequest();
?>