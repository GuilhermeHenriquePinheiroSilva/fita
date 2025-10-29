<?php 
// app/views/dashboard/index.php
require_once __DIR__ . '/../templates/header.php'; 
?>

<h1>Bem-vindo ao Dashboard, <?php echo htmlspecialchars($nome); ?>!</h1>

<p>Você está logado com sucesso.</p>

<p>Seu nível de acesso é: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong></p>

<?php if ($_SESSION['user_role'] == 'Administrador'): ?>
    <div style="background-color: #ffc; border: 1px solid #e6db55; padding: 10px;">
        <strong>Painel do Administrador:</strong>
        <p>Você tem permissões de administrador.</p>
        <a href="/admin/usuarios">Gerenciar Usuários</a>
    </div>
<?php endif; ?>

<br>
<a href="/logout">Sair</a>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>