# Projeto-Fita-Back-end - Integração de Endpoints do 'fita'

Foram adicionados os seguintes endpoints (no padrão do projeto base) e usam a mesma conexão em `config/db.php`:

- `api_rooms.php` — CRUD para tabela `rooms`
  - GET /api_rooms.php -> lista todas salas
  - GET /api_rooms.php?id=1 -> busca sala por id
  - POST -> cria (name, capacity, resources (JSON array), is_practical, is_accessible)
  - PUT -> atualiza (id via query ou body)
  - DELETE -> deleta (id via query)

- `api_equipments.php` — CRUD para `equipments`
- `api_reservations.php` — CRUD para `reservations`
- `api_reservation_equipments.php` — CRUD para `equipment_reservations`
- `api_occupancy.php` — CRUD para `occupancy`

OBS: As rotas foram adicionadas no mesmo nível do projeto base. Ajuste `config/db.php` com suas credenciais e execute o arquivo `migrations/schema.sql` que se encontra em `fita/migrations/schema.sql` para criar as tabelas necessárias.

Token/Autenticação: mantida conforme configuração original do projeto base.

