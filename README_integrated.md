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



## Enhancements added by assistant
- Added models: Sponsor, Room, EventRoom (app/models).
- Added admin controller and views for room management (app/controllers/AdminRoomsController.php and app/views/admin/rooms).
- Migration SQL at db/add_sponsors_rooms.sql to create sponsors, rooms, event_rooms and indexes.
- Monitoring sample script monitoring/monitor.php to log basic DB stats and slow queries.
- EventRoom model implements conflict detection and occupancy summary used by dashboard.

Follow instructions in the migration file to apply DB changes.


## Finalized integrations performed
- Integrated conflict checks into api_reservations.php using EventRoom->hasConflict(). Returns HTTP 409 on conflict.
- Added API endpoints: api_sponsors.php and api_rooms.php (CRUD create + read).
- Dashboard view at app/views/dashboard/occupancy.php and DashboardController::occupancy() to render it.
- Appended explicit CREATE INDEX for reservations to migration SQL for compatibility.
- Monitoring script available at monitoring/monitor.php (simple slow query sampler).
