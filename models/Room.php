<?php
// app/models/Room.php
class Room {
    protected $pdo;
    protected $table = 'rooms';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $capacity = 0, $location = null) {
        $stmt = $this->pdo->prepare('INSERT INTO rooms (name, capacity, location, created_at) VALUES (?,?,?,NOW())');
        $stmt->execute([$name, intval($capacity), $location]);
        return $this->pdo->lastInsertId();
    }

    public function getAll() {
        // optimized: only required columns, use index on name
        $stmt = $this->pdo->query('SELECT id, name, capacity, location FROM rooms ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM rooms WHERE id = ?');
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>