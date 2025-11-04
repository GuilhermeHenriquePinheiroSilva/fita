<?php
// app/config/database.php

$host = 'localhost'; // ou seu host (ex: 'localhost')
$db_name = 'fita-teste'; // Coloque o nome do seu DB
$username = 'root'; // Seu usuário do DB
$password = ''; // Sua senha do DB
$charset = 'utf8mb4'; // UTF-8 obrigatório

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

?>