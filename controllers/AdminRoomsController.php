<?php
// app/controllers/AdminRoomsController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../models/EventRoom.php';

class AdminRoomsController extends BaseController {
    protected $roomModel;
    protected $eventRoomModel;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->roomModel = new Room($pdo);
        $this->eventRoomModel = new EventRoom($pdo);
    }

    public function index() {
        $this->checkRole(['Administrador','Organizador']);
        $rooms = $this->roomModel->getAll();
        $this->view('admin/rooms/index', ['rooms' => $rooms]);
    }

    public function create() {
        $this->checkRole(['Administrador']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? null;
            $capacity = $_POST['capacity'] ?? 0;
            $location = $_POST['location'] ?? null;
            if (!$name) { $_SESSION['flash_error'] = 'Nome obrigatório'; header('Location: /admin/rooms'); exit; }
            $id = $this->roomModel->create($name, $capacity, $location);
            $_SESSION['flash_success'] = 'Sala criada (ID: ' . $id . ')';
            header('Location: /admin/rooms'); exit;
        }
        $this->view('admin/rooms/create');
    }
}
?>