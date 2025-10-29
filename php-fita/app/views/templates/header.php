<?php
// app/views/templates/header.php

// Inicia a sessão se ainda não foi iniciada (necessário para exibir mensagens)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Projeto MVC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .error { color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
        .success { color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px; }
        div { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 95%; padding: 8px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">

        <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']); // Limpa a mensagem
            }
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']); // Limpa a mensagem
            }
        ?>