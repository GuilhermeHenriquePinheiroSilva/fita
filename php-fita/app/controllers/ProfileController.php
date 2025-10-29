<?php
// app/controllers/ProfileController.php

require_once 'BaseController.php';

class ProfileController extends BaseController {
    private $userModel;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->userModel = new User($this->pdo);
    }

    // Ação: Mostrar o formulário para completar o perfil
    public function showCompleteForm() {
        $this->checkAuth(); // Precisa estar logado
        $this->view('profile/completar');
    }

    // Ação: Processar o POST de completar o perfil
    public function doComplete() {
        $this->checkAuth();
        
        // (Aqui você coletaria os dados do formulário: $_POST['telefone'], $_POST['endereco'], etc.)
        // Para este exemplo, vamos apenas marcar como completo.

        $userId = $_SESSION['user_id'];

        if ($this->userModel->setProfileComplete($userId)) {
            $_SESSION['perfil_completo'] = 1; // Atualiza a sessão
            $_SESSION['success_message'] = "Perfil completado com sucesso!";
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error_message'] = "Erro ao atualizar o perfil.";
            $this->redirect('/perfil/completar');
        }
    }
}
?>