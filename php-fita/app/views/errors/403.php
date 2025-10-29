<?php 
// app/views/errors/403.php
require_once __DIR__ . '/../templates/header.php'; 
?>

<h1 style="color: red;">Acesso Negado (403)</h1>
<p>Você não tem permissão para acessar esta página.</p>
<p>Seu nível de acesso é <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong>, mas esta página requer um nível diferente.</p>
<a href="/dashboard">Voltar ao Dashboard</a>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>