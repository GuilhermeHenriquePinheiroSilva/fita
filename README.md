# FITA - Alocação e Gestão de Salas (Backend)

Esta pasta contém a implementação backend (PHP MVC) específica para a **Função 5 - Alocação e Gestão de Salas** do projeto FITA.
Principais pontos implementados:
- Endpoints JSON (API REST simples) em `index.php`:
  - `/api/salas/list` - GET - lista salas
  - `/api/salas/get/{id}` - GET - detalhes de uma sala
  - `/api/salas/create` - POST - cria sala (JSON)
  - `/api/salas/update/{id}` - PUT - atualiza sala (JSON)
  - `/api/salas/delete/{id}` - DELETE - exclui sala
  - `/api/alocacao/allocate` - POST - aloca automaticamente uma sala para uma atividade (body: {atividade_id, acessibilidade_req})
  - `/api/alocacao/list` - GET - lista alocações
  - `/api/alocacao/cancel/{id}` - DELETE - cancela alocação
- Respeita regras: capacidade máxima, buffer de 15 minutos entre atividades, salas práticas e acessibilidade.
- Arquivo SQL em `database/schema.sql` com schema e dados de exemplo.

## Como usar
1. Copie os arquivos para seu servidor (Apache/Nginx + PHP 8 + MySQL).
2. Importe `database/schema.sql` no seu MySQL (ajuste DB credentials em `config/Database.php`).
3. Teste endpoints via Postman / fetch AJAX das views.

