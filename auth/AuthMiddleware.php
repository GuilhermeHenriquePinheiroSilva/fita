<?php
// auth/AuthMiddleware.php

class AuthMiddleware {
    
    // REQUISITO: Sessão de timeout automático
    // Definir o tempo de inatividade em segundos (aqui, 30 minutos)
    private $timeout = 1800; // (60 segundos * 30 minutos)

    public function __construct() {
        // Garante que a sessão seja iniciada em qualquer lugar
        // que o middleware for chamado.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica se a sessão do usuário expirou por inatividade.
     * Se sim, destrói a sessão e envia resposta 401.
     */
    private function checkTimeout() {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $this->timeout)) {
            session_unset();
            session_destroy();
            $this->sendResponse(401, 'Sessão expirada por inatividade. Por favor, faça login novamente.');
        }
        // Se não expirou, atualiza o tempo da última atividade
        $_SESSION['last_activity'] = time(); 
    }

    /**
     * Função principal: Verifica se o usuário está logado.
     * REQUISITO: Validação de login
     */
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendResponse(401, 'Acesso negado. Você precisa estar logado.');
        }

        // Se está logado, verifica o timeout
        $this->checkTimeout();
    }

    /**
     * REQUISITO: Diferentes níveis de acesso
     * Verifica se o usuário logado tem um nível de acesso específico (ex: 'administrador').
     */
    public function checkRole($required_role) {
        $this->checkAuth(); // Garante que está logado primeiro

        if (!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] !== $required_role) {
            $this->sendResponse(403, 'Acesso proibido. Você não tem permissão para este recurso.');
        }
    }

    /**
     * REQUISITO: Diferentes níveis de acesso (Mais flexível)
     * Verifica se o usuário tem permissão igual ou superior a um nível mínimo.
     * Ex: 'organizador' pode acessar coisas de 'palestrante' e 'participante'.
     */
    public function checkRoleOrHigher($min_role) {
        $this->checkAuth();

        $roles = [
            'participante' => 1,
            'palestrante' => 2,
            'organizador' => 3,
            'administrador' => 4
        ];

        if (!isset($roles[$_SESSION['nivel_acesso']]) || !isset($roles[$min_role])) {
            $this->sendResponse(400, 'Nível de acesso inválido.');
        }

        $user_level = $roles[$_SESSION['nivel_acesso']];
        $required_level = $roles[$min_role];

        if ($user_level < $required_level) {
            $this->sendResponse(403, 'Acesso proibido. Você não tem permissão suficiente.');
        }
    }

    /**
     * Retorna o ID do usuário logado.
     */
    public function getUserId() {
        $this.checkAuth(); // Garante que está logado e atualiza o timeout
        return $_SESSION['user_id'];
    }


    /**
     * Envia uma resposta JSON padronizada e encerra o script.
     */
    private function sendResponse($statusCode, $message) {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode(['message' => $message]);
        exit; // Para a execução do script
    }
}
?>