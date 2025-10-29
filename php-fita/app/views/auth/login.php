<?php 
// app/views/auth/login.php
require_once __DIR__ . '/../templates/header.php'; 
?>

<h2>Login</h2>
<form action="/login" method="POST">
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
    </div>
    <button type="submit">Entrar</button>
</form>
<p>Não tem conta? <a href="/cadastro">Cadastre-se</a></p>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>