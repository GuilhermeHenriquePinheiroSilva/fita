<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

function json_response($data, $code=200){ http_response_code($code); echo json_encode($data); exit; }

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $stmt = $db->prepare('SELECT * FROM rooms WHERE id = ? LIMIT 1');
        $stmt->execute([intval($_GET['id'])]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) json_response($row);
        json_response(['error'=>'Not found'],404);
    } else {
        $stmt = $db->query('SELECT * FROM rooms ORDER BY id DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        json_response($rows);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    if (empty($data['name']) || !isset($data['capacity'])) json_response(['error'=>'name and capacity required'],400);
    try {
        $stmt = $db->prepare('INSERT INTO rooms (name, capacity, resources, is_practical, is_accessible) VALUES (?, ?, ?, ?, ?)');
        $resources = isset($data['resources']) ? json_encode($data['resources']) : json_encode([]);
        $is_practical = !empty($data['is_practical']) ? 1 : 0;
        $is_accessible = isset($data['is_accessible']) ? (int)$data['is_accessible'] : 1;
        $stmt->execute([$data['name'], intval($data['capacity']), $resources, $is_practical, $is_accessible]);
        json_response(['id'=>$db->lastInsertId()],201);
    } catch (Exception $e) {
        json_response(['error'=>$e->getMessage()],500);
    }
} elseif ($method === 'PUT') {
    parse_str(file_get_contents('php://input'), $put_vars);
    $id = $_GET['id'] ?? ($put_vars['id'] ?? null);
    if (!$id) json_response(['error'=>'id required'],400);
    $fields = [];
    $values = [];
    foreach (['name','capacity','resources','is_practical','is_accessible'] as $col) {
        if (isset($put_vars[$col])) {
            if ($col === 'resources') $values[] = json_encode($put_vars[$col]);
            else $values[] = $put_vars[$col];
            $fields[] = "$col = ?";
        }
    }
    if (empty($fields)) json_response(['error'=>'no fields to update'],400);
    $values[] = intval($id);
    $sql = 'UPDATE rooms SET '.implode(',', $fields).' WHERE id = ?';
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($values);
        json_response(['success'=>true]);
    } catch (Exception $e) { json_response(['error'=>$e->getMessage()],500); }
} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) json_response(['error'=>'id required'],400);
    $stmt = $db->prepare('DELETE FROM rooms WHERE id = ?');
    $stmt->execute([intval($id)]);
    json_response(['success'=>true]);
} else {
    http_response_code(405);
    echo json_encode(['error'=>'Method not allowed']);
}
