<?php
// app/controllers/DashboardController.php

require_once 'BaseController.php';

class DashboardController extends BaseController {

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    // Ação: Mostrar o dashboard
    public function index() {
        // 1. Garante que o usuário está logado
        $this->checkAuth();
        // 2. Garante que o perfil está completo
        $this->checkProfileComplete();

        // Passa o nome do usuário para a View
        $data = [
            'nome' => $_SESSION['user_nome']
        ];
        
        $this->view('dashboard/index', $data);
    }

    public function occupancy() {
        $this->checkRole(['Administrador','Organizador']);
        require_once __DIR__ . '/../models/EventRoom.php';
        $eventRoomModel = new EventRoom($this->pdo);
        $this->view('dashboard/occupancy', ['eventRoomModel' => $eventRoomModel]);
    }
}
?>