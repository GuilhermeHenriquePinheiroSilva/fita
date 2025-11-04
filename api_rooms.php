<?php
header('Content-Type: application/json');
require_once __DIR__ . '/php-fita/app/config/database.php';
require_once __DIR__ . '/php-fita/app/models/Room.php';
$database = new Database(); $db = $database->getConnection();
$roomModel = new Room($db);
$method = $_SERVER['REQUEST_METHOD'];
function jr($d,$c=200){http_response_code($c); echo json_encode($d); exit;}
if ($method==='GET') {
    if (isset($_GET['id'])) { $r = $roomModel->find(intval($_GET['id'])); if ($r) jr($r); jr(['error'=>'Not found'],404); }
    jr($roomModel->getAll());
} elseif ($method==='POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    if (empty($data['name'])) jr(['error'=>'name required'],400);
    try { $id = $roomModel->create($data['name'], $data['capacity'] ?? 0, $data['location'] ?? null); jr(['id'=>$id],201); } catch(Exception $e){ jr(['error'=>$e->getMessage()],500); }
} else { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); }
?>