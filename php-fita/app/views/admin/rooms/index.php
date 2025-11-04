<?php // app/views/admin/rooms/index.php ?>
<h1>Gestão de Salas</h1>
<?php if(!empty($_SESSION['flash_success'])): ?><div class="alert success"><?=htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']);?></div><?php endif; ?>
<?php if(!empty($_SESSION['flash_error'])): ?><div class="alert error"><?=htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']);?></div><?php endif; ?>
<a href="/admin/rooms/create">Criar nova sala</a>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Capacidade</th><th>Local</th></tr></thead>
    <tbody>
    <?php foreach($rooms as $r): ?>
        <tr>
            <td><?=htmlspecialchars($r['id'])?></td>
            <td><?=htmlspecialchars($r['name'])?></td>
            <td><?=htmlspecialchars($r['capacity'])?></td>
            <td><?=htmlspecialchars($r['location'])?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
