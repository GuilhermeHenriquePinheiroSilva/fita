<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';
$database = new Database(); $db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
function json_response($data,$code=200){ http_response_code($code); echo json_encode($data); exit; }
if ($method==='GET') {
    if (isset($_GET['id'])) {
        $stmt=$db->prepare('SELECT * FROM reservations WHERE id = ? LIMIT 1'); $stmt->execute([intval($_GET['id'])]); $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) json_response($row); json_response(['error'=>'Not found'],404);
    } else {
        $stmt=$db->query('SELECT * FROM reservations ORDER BY id DESC'); $rows=$stmt->fetchAll(PDO::FETCH_ASSOC); json_response($rows);
    }
} elseif ($method==='POST') {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    foreach (['room_id','title','start','end'] as $req) if (empty($data[$req])) json_response(['error'=>"$req required"],400);
    try {
        $stmt = $db->prepare('INSERT INTO reservations (room_id,title,start,end,attendees,resources) VALUES (?,?,?,?,?,?)');
        $resources = isset($data['resources']) ? json_encode($data['resources']) : json_encode([]);
        $attendees = isset($data['attendees']) ? intval($data['attendees']) : 1;
        $stmt->execute([intval($data['room_id']), $data['title'], $data['start'], $data['end'], $attendees, $resources]);
        json_response(['id'=>$db->lastInsertId()],201);
    } catch (Exception $e) { json_response(['error'=>$e->getMessage()],500); }
} elseif ($method==='PUT') {
    parse_str(file_get_contents('php://input'), $put_vars);
    $id = $_GET['id'] ?? ($put_vars['id'] ?? null); if (!$id) json_response(['error'=>'id required'],400);
    $fields=[];$values=[];
    foreach(['room_id','title','start','end','attendees','resources'] as $col){ if (isset($put_vars[$col])){ if ($col==='resources') $values[] = json_encode($put_vars[$col]); else $values[] = $put_vars[$col]; $fields[]="$col = ?"; } }
    if (empty($fields)) json_response(['error'=>'no fields to update'],400);
    $values[] = intval($id); $sql='UPDATE reservations SET '.implode(',', $fields).' WHERE id = ?';
    try{ $stmt=$db->prepare($sql); $stmt->execute($values); json_response(['success'=>true]); }catch(Exception $e){ json_response(['error'=>$e->getMessage()],500); }
} elseif ($method==='DELETE') {
    $id = $_GET['id'] ?? null; if (!$id) json_response(['error'=>'id required'],400);
    $stmt = $db->prepare('DELETE FROM reservations WHERE id = ?'); $stmt->execute([intval($id)]); json_response(['success'=>true]);
} else { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); }
