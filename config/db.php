<?php
// /config/db.php

class Database {
    private static $pdo;

    public static function getConnection() {
        // Garante que a conexão seja criada apenas uma vez
        if (!isset(self::$pdo)) {
            $host = 'localhost';
            $db   = 'fita-bd';
            $user = 'root';
            $pass = ''; // <-- Coloque sua senha aqui, se houver
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                 self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                 // Em um projeto real, você trataria esse erro de forma mais elegante
                 throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$pdo;
    }
}
?>