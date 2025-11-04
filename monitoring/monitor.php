<?php
// monitoring/monitor.php
// Simple script to sample DB performance and log slow queries.
// Configure DB in ../php-fita/app/config/database.php
require_once __DIR__ . '/../php-fita/app/config/database.php';
try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // sample: get InnoDB status and current connections
    $stmt = $pdo->query('SHOW STATUS LIKE "Threads_connected"');
    $threads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents(__DIR__ . '/monitor.log', date('c') . " threads:" . json_encode($threads) . PHP_EOL, FILE_APPEND);
    // detect slow running queries (requires PROCESSLIST permissions)
    $stmt = $pdo->query('SELECT ID, USER, HOST, DB, COMMAND, TIME, STATE, INFO FROM INFORMATION_SCHEMA.PROCESSLIST WHERE TIME > 5');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($rows)) {
        file_put_contents(__DIR__ . '/monitor.log', date('c') . " slow_queries:" . json_encode($rows) . PHP_EOL, FILE_APPEND);
    }
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/monitor.log', date('c') . " error:" . $e->getMessage() . PHP_EOL, FILE_APPEND);
}
echo "monitor ok\n";
?>
// Last run appended.
