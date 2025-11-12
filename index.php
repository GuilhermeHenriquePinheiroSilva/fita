<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/app/controllers/SalaController.php';
require_once __DIR__ . '/app/controllers/AlocacaoController.php';

// Simple router: expect /api/{resource}/{action}
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = preg_replace('#^'.preg_quote($script_name).'#', '', $uri);
$path = trim($path, '/');
$parts = explode('/', $path);

header('Content-Type: application/json; charset=utf-8');

if (count($parts) >= 1 && $parts[0] === 'api') {
    $resource = $parts[1] ?? null;
    $action = $parts[2] ?? 'index';
} else {
    // Rota padrão: carrega view principal
    if ($path === '' || $path === '/') {
        include __DIR__ . '/app/views/dashboard.php';
        exit;
    }

    echo json_encode(['error' => 'Invalid endpoint. Use /api/...']);
    exit;
}

try {
    if ($resource === 'salas') {
        $ctrl = new SalaController();
        switch ($action) {
            case 'list': $ctrl->list(); break;
            case 'get': $ctrl->get($parts[3] ?? null); break;
            case 'create': $ctrl->create(); break;
            case 'update': $ctrl->update($parts[3] ?? null); break;
            case 'delete': $ctrl->delete($parts[3] ?? null); break;
            default: echo json_encode(['error'=>'Unknown action for salas']); break;
        }
    } elseif ($resource === 'alocacao') {
        $ctrl = new AlocacaoController();
        switch ($action) {
            case 'allocate': $ctrl->allocate(); break;
            case 'list': $ctrl->list(); break;
            case 'cancel': $ctrl->cancel($parts[3] ?? null); break;
            default: echo json_encode(['error'=>'Unknown action for alocacao']); break;
        }
    } else {
    // Rota padrão: carrega view principal
    if ($path === '' || $path === '/') {
        include __DIR__ . '/app/views/dashboard.php';
        exit;
    }

        echo json_encode(['error'=>'Unknown resource']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
