
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>FITA - Gestão de Salas</title>
  <script>
  async function carregarSalas(){
    const res = await fetch('/api/salas/list');
    const json = await res.json();
    const tbody = document.querySelector('#salas tbody');
    tbody.innerHTML = '';
    json.data.forEach(s => {
      tbody.innerHTML += `<tr><td>${s.id}</td><td>${s.nome}</td><td>${s.capacidade}</td></tr>`;
    });
  }
  window.onload = carregarSalas;
  </script>
</head>
<body>
  <h1>Gestão de Salas - FITA</h1>
  <table id="salas" border="1" cellpadding="5">
    <thead><tr><th>ID</th><th>Nome</th><th>Capacidade</th></tr></thead>
    <tbody></tbody>
  </table>
</body>
</html>
