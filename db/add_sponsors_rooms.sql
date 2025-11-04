-- Migration: add sponsors, rooms, event_rooms and useful indexes
-- Run this SQL in your MySQL instance.
CREATE TABLE IF NOT EXISTS sponsors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact VARCHAR(255),
    website VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT DEFAULT 0,
    location VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- event_rooms provides optional separated mapping, but most booking data is in reservations.
CREATE TABLE IF NOT EXISTS event_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    room_id INT NOT NULL,
    sponsor_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (sponsor_id) REFERENCES sponsors(id) ON DELETE SET NULL,
    INDEX (room_id),
    INDEX (sponsor_id),
    INDEX (reservation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optimize heavy reservation queries: make sure start/end columns are indexed
ALTER TABLE reservations
    ADD INDEX IF NOT EXISTS idx_res_room_time (room_id, start, end);

-- NOTE: MySQL < 8.0 doesn't support IF NOT EXISTS for ALTER INDEX; run conditionally.

-- Ensure index for reservations (create explicitly if not exists)
CREATE INDEX idx_res_room_time ON reservations (room_id, start, end);
