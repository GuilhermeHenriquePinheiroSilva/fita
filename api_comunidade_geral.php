<?php
// /api_comunidade_geral.php

header("Content-Type: application/json");

// Inclui (importa) as classes necessárias
// Note os caminhos corretos:
require_once 'config/db.php';
require_once 'models/ParticipanteComunidadeGeral.php';
require_once 'controllers/ParticipanteComunidadeGeralController.php';

// Obtém a conexão com o banco
$pdo = Database::getConnection();

// "Injeção de Dependência":
// 1. Cria o Model (dando a ele a conexão com o banco)
$model = new ParticipanteComunidadeGeral($pdo);
// 2. Cria o Controller (dando a ele o Model)
$controller = new ParticipanteComunidadeGeralController($model);

// 3. Pede ao Controller para processar a requisição
$controller->handleRequest();
?>