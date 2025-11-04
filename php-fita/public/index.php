<?php
// public/index.php
session_start();

// --- 1. Carregamento de Dependências ---
require_once '../app/config/database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/BaseController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/ProfileController.php';

// --- 2. Conexão com o Banco ---
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// --- 3. Instância dos Controllers ---
// Passa a conexão PDO para eles
$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);
$profileController = new ProfileController($pdo);

// --- 4. Roteamento Simples ---
// Pega a URL da variável 'url' definida no .htaccess
// (ex: 'login', 'cadastro', 'dashboard')
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';
$method = $_SERVER['REQUEST_METHOD'];

switch ($url) {
    case '/':
    case 'login':
        if ($method == 'POST') {
            $authController->doLogin();
        } else {
            $authController->showLogin();
        }
        break;

    case 'cadastro':
        if ($method == 'POST') {
            $authController->doRegister();
        } else {
            $authController->showRegister();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        $dashboardController->index();
        break;

    case 'perfil/completar':
        if ($method == 'POST') {
            $profileController->doComplete();
        } else {
            $profileController->showCompleteForm();
        }
        break;
    
    // (Exemplo de Rota de Admin)
    case 'admin/usuarios':
        // Protege a rota chamando o 'checkRole' antes da ação
        $dashboardController->checkRole(['Administrador']); // Apenas Admins
        // (Aqui você chamaria o AdminController)
        echo "Página de Gerenciar Usuários (Apenas Admins)";
        break;

    default:
        http_response_code(404);
        require_once '../app/views/errors/404.php'; // Crie esta view
        break;
}
?>