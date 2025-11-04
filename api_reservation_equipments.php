<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';
$database = new Database(); $db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
function json_response($data,$code=200){ http_response_code($code); echo json_encode($data); exit; }

$table = 'equipment_reservations';

if ($method==='GET') {
    if (isset($_GET['id'])) {
        $stmt=$db->prepare("SELECT * FROM {$table} WHERE id = ? LIMIT 1"); $stmt->execute([intval($_GET['id'])]); $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) json_response($row); json_response(['error'=>'Not found'],404);
    } else {
        $stmt = $db->query("SELECT * FROM {$table} ORDER BY id DESC"); json_response($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($method==='POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    foreach(['equipment_id','reservation_id'] as $r) if (empty($data[$r])) json_response(['error'=>"$r required"],400);
    $qty = isset($data['qty']) ? intval($data['qty']) : 1;
    try { $stmt=$db->prepare("INSERT INTO {$table} (equipment_id,reservation_id,qty) VALUES (?,?,?)"); $stmt->execute([intval($data['equipment_id']), intval($data['reservation_id']), $qty]); json_response(['id'=>$db->lastInsertId()],201); }
    catch(Exception $e){ json_response(['error'=>$e->getMessage()],500); }
} elseif ($method==='PUT') {
    parse_str(file_get_contents('php://input'), $put_vars);
    $id = $_GET['id'] ?? ($put_vars['id'] ?? null); if (!$id) json_response(['error'=>'id required'],400);
    $fields=[];$values=[];
    foreach(['equipment_id','reservation_id','qty'] as $col){ if (isset($put_vars[$col])){ $values[] = $put_vars[$col]; $fields[]="$col = ?"; } }
    if (empty($fields)) json_response(['error'=>'no fields to update'],400);
    $values[] = intval($id); $sql = "UPDATE {$table} SET ".implode(',', $fields)." WHERE id = ?";
    try{ $stmt=$db->prepare($sql); $stmt->execute($values); json_response(['success'=>true]); }catch(Exception $e){ json_response(['error'=>$e->getMessage()],500); }
} elseif ($method==='DELETE') {
    $id = $_GET['id'] ?? null; if (!$id) json_response(['error'=>'id required'],400);
    $stmt=$db->prepare("DELETE FROM {$table} WHERE id = ?"); $stmt->execute([intval($id)]); json_response(['success'=>true]);
} else { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); }
