<?php
// app/models/Sponsor.php
class Sponsor {
    protected $pdo;
    protected $table = 'sponsors';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $contact = null, $website = null) {
        $stmt = $this->pdo->prepare('INSERT INTO sponsors (name, contact, website, created_at) VALUES (?,?,?,NOW())');
        $stmt->execute([$name, $contact, $website]);
        return $this->pdo->lastInsertId();
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT id, name, contact, website FROM sponsors ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM sponsors WHERE id = ?');
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>