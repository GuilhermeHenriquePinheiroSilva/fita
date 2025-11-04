<?php 
// app/views/profile/completar.php
require_once __DIR__ . '/../templates/header.php'; 
?>

<h1>Complete seu Perfil</h1>
<p>Para continuar a usar o sistema, precisamos que você complete seu perfil.</p>

<form action="/perfil/completar" method="POST">
    
    <p>Por favor, confirme que seus dados estão completos (simulação).</p>
    <button type="submit">Confirmar e Continuar</button>
</form>

<?php 
require_once __DIR__ . '/../templates/footer.php'; 
?>