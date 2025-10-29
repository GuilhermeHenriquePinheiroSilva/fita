<?php 
// app/views/auth/cadastro.php
require_once __DIR__ . '/../templates/header.php'; 
?>

<h2>Cadastro</h2>
<form action="/cadastro" method="POST">
    <div>
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="senha">Senha (mínimo 8 caracteres):</label>
        <input type="password" id="senha" name="senha" required minlength="8">
    </div>
    <div>
        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="8">
    </div>
    <button type="submit">Cadastrar</button>
</form>
<p>Já tem conta? <a href="/login">Faça Login</a></p>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>