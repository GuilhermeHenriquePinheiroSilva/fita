<?php
// app/models/EventRoom.php
class EventRoom {
    protected $pdo;
    protected $table = 'event_rooms'; // new table that maps events to rooms (if desired)

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Check for time conflicts in the reservations table for a given room.
     * Returns true if conflict exists.
     */
    public function hasConflict($room_id, $start, $end, $exclude_reservation_id = null) {
        // Use inclusive-exclusive overlap logic: (start < existing_end) AND (end > existing_start)
        $sql = 'SELECT COUNT(*) as cnt FROM reservations WHERE room_id = ? AND (start < ? AND end > ?)';
        $params = [intval($room_id), $end, $start];
        if ($exclude_reservation_id) {
            $sql .= ' AND id != ?';
            $params[] = intval($exclude_reservation_id);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['cnt']) > 0;
    }

    /**
     * Get occupancy summary per room for a date range (used by dashboard).
     * Returns aggregated durations in seconds per room.
     */
    public function occupancySummary($from, $to) {
        $sql = "SELECT r.id, r.name, COUNT(res.id) AS bookings, 
            SUM(TIMESTAMPDIFF(SECOND, GREATEST(res.start, ?), LEAST(res.end, ?))) AS occupied_seconds
            FROM rooms r
            LEFT JOIN reservations res ON res.room_id = r.id AND res.start < ? AND res.end > ?
            GROUP BY r.id, r.name
            ORDER BY r.name";
        // parameters repeated for GREATEST/LEAST filters
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$from, $to, $to, $from]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>