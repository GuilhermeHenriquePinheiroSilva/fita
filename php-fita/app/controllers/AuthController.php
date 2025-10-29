<?php
// app/controllers/AuthController.php

require_once 'BaseController.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo); // Chama o construtor do BaseController
        $this->userModel = new User($this->pdo); // Instancia o Model
    }

    // Ação: Mostrar a View de login
    public function showLogin() {
        $this->view('auth/login');
    }

    // Ação: Processar o POST do login
    public function doLogin() {
        $email = trim($_POST['email']);
        $senha_digitada = trim($_POST['senha']);

        $user = $this->userModel->findByEmail($email);

        // Regra de Negócio: Bloqueio de 3 tentativas (requer DB/sessão) - NÃO IMPLEMENTADO (complexo)
        
        // Verifica se o usuário existe e a senha está correta
        if ($user && password_verify($senha_digitada, $user['senha'])) {
            // Sucesso: Salva dados na sessão
            session_regenerate_id(true); // Segurança
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['perfil_completo'] = (int)$user['perfil_completo'];

            // Regra de Negócio: Perfil completo obrigatório
            if ($_SESSION['perfil_completo'] == 0) {
                $this->redirect('/perfil/completar');
            }

            // Perfil completo, redireciona para o dashboard
            $this->redirect('/dashboard');
        } else {
            // Falha
            $_SESSION['error_message'] = "Email ou senha incorretos.";
            $this->redirect('/login');
        }
    }

    // Ação: Mostrar View de cadastro
    public function showRegister() {
        $this->view('auth/cadastro');
    }

    // Ação: Processar o POST do cadastro
    public function doRegister() {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $confirmar_senha = trim($_POST['confirmar_senha']);

        // Regras de Negócio
        if (strlen($senha) < 8) {
            $_SESSION['error_message'] = "A senha deve ter no mínimo 8 caracteres.";
            $this->redirect('/cadastro');
        }
        if ($senha !== $confirmar_senha) {
            $_SESSION['error_message'] = "As senhas não coincidem.";
            $this->redirect('/cadastro');
        }

        $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

        // Tenta criar o usuário
        if ($this->userModel->create($nome, $email, $hash_senha)) {
            $_SESSION['success_message'] = "Cadastro realizado com sucesso! Faça o login.";
            $this->redirect('/login');
        } else {
            // Regra de Negócio: Email único
            $_SESSION['error_message'] = "Este email já está cadastrado.";
            $this->redirect('/cadastro');
        }
    }

    // Ação: Logout
    public function logout() {
        session_unset();
        session_destroy();
        session_start(); // Reinicia para a mensagem de sucesso
        $_SESSION['success_message'] = "Você saiu com sucesso.";
        $this->redirect('/login');
    }
}
?>