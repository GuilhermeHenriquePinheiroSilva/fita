<?php
// app/controllers/BaseController.php

class BaseController {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Carrega um arquivo de View.
     * @param string $viewName O nome da view (ex: 'auth/login')
     * @param array $data Dados para extrair e tornar disponíveis na view
     */
    protected function view($viewName, $data = []) {
        // Transforma as chaves do array em variáveis (ex: $data['nome'] vira $nome)
        extract($data);

        // O caminho é relativo à pasta 'app/views/'
        require_once __DIR__ . "/../views/$viewName.php";
    }

    /**
     * Redireciona o usuário para uma URL.
     * @param string $url A URL (ex: '/login' ou '/dashboard')
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    /**
     * Verifica se o usuário está logado. Se não, redireciona para o login.
     */
    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Você precisa estar logado para acessar.";
            $this->redirect('/login');
        }
    }

    /**
     * Verifica se o perfil está completo.
     * Se não estiver, redireciona para a página de completar perfil.
     */
    protected function checkProfileComplete() {
        // Pega a URL atual para evitar loop de redirect
        $currentUrl = isset($_GET['url']) ? $_GET['url'] : '/';

        if (isset($_SESSION['perfil_completo']) && 
            $_SESSION['perfil_completo'] == 0 && 
            $currentUrl !== 'perfil/completar') 
        {
            $_SESSION['error_message'] = "Você precisa completar seu perfil para continuar.";
            $this->redirect('/perfil/completar');
        }
    }

    /**
     * Verifica se o usuário tem uma das roles permitidas.
     * @param array $roles (ex: ['Administrador', 'Organizador'])
     */
    protected function checkRole(array $roles) {
        $this->checkAuth(); // Garante que está logado primeiro

        if (!in_array($_SESSION['user_role'], $roles)) {
            // Não tem permissão. Podemos carregar uma view de "Acesso Negado".
            http_response_code(403); // Forbidden
            $this->view('errors/403');
            exit;
        }
    }
}
?>