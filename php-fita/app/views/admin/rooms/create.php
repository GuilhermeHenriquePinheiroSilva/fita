<?php // app/views/admin/rooms/create.php ?>
<h1>Criar Sala</h1>
<form method="post" action="/admin/rooms/create">
    <label>Nome: <input name="name" required /></label><br/>
    <label>Capacidade: <input name="capacity" type="number" /></label><br/>
    <label>Local: <input name="location" /></label><br/>
    <button type="submit">Criar</button>
</form>
