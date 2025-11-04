# API de Gerenciamento de Participantes (PHP/MySQL)

Este é um projeto de API backend simples, construído em PHP puro, para gerenciar o cadastro de participantes de eventos. Ele utiliza uma arquitetura MVC (Model-View-Controller) e protege as rotas de criação, atualização e deleção (POST, PUT, DELETE) com autenticação via Bearer Token.

## Estrutura de Endpoints

O projeto possui dois conjuntos de endpoints (CRUDs) principais:
1.  **Ensino Médio:** `/api_ensino_medio.php`
2.  **Comunidade Geral:** `/api_comunidade_geral.php`

## Pré-requisitos

* PHP 7.4 ou superior
* Servidor MySQL
* **XAMPP** (ou WAMP, MAMP)
* Um cliente de API como `curl` (disponível no Shell do XAMPP) ou Postman

---

## 1. Instalação e Configuração

### Passo 1: Banco de Dados

1.  Inicie o **MySQL** pelo painel do XAMPP.
2.  Abra o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
3.  Crie um novo banco de dados chamado `meu_projeto_db`.
4.  Clique no banco `meu_projeto_db` e vá para a aba "SQL".
5.  Execute o script abaixo para criar as tabelas:

```sql
CREATE TABLE IF NOT EXISTS participantes_ensino_medio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    nacionalidade VARCHAR(100),
    telefone VARCHAR(20),
    serie VARCHAR(50),
    cidade VARCHAR(100),
    instituicao VARCHAR(255),
    data_nascimento DATE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS participantes_comunidade_geral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    nacionalidade VARCHAR(100),
    telefone VARCHAR(20),
    grau_escolaridade VARCHAR(100),
    cidade VARCHAR(100),
    data_nascimento DATE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Passo 2: Arquivos do Projeto
Certifique-se de que esta pasta do projeto (ex: projeto_participantes) está localizada dentro do diretório htdocs do seu XAMPP.

Caminho de exemplo: C:\xampp\htdocs\projeto_participantes

Passo 3: Configurar a Conexão com o Banco
Abra o arquivo /config/db.php.

Localize a linha: $pass = '';

Se o seu usuário root do MySQL tiver uma senha, coloque-a entre as aspas. Caso contrário, deixe como está.

2. Como Executar o Projeto
Você pode rodar o projeto de duas maneiras. O "Método 1" é o padrão para o XAMPP.

Método 1: Usando o Apache (XAMPP)
Esta é a forma mais simples.

Abra o Painel de Controle do XAMPP.

Inicie os módulos Apache e MySQL.

Pronto! O projeto já está "rodando" e acessível.

URL Base: http://localhost/projeto_participantes/ (Use o nome da sua pasta no lugar de projeto_participantes se for diferente)

Método 2: Usando o Servidor Embutido do PHP
Inicie apenas o MySQL no painel do XAMPP.

Abra o Shell do XAMPP (ou qualquer terminal).

Navegue até a pasta do seu projeto:

Bash

cd C:\xampp\htdocs\projeto_participantes
Inicie o servidor do PHP:

Bash

php -S localhost:8000
Mantenha este terminal aberto enquanto estiver testando.

URL Base: http://localhost:8000/

3. Como Testar a API (Tutorial CRUD)
Todos os exemplos abaixo usarão a URL do Método 1 (XAMPP). Se estiver usando o Método 2, apenas substitua http://localhost/projeto_participantes/ por http://localhost:8000/.

Os testes devem ser feitos no Shell do XAMPP usando o curl.

3.1. Autenticação
Rotas Públicas (GET): Não exigem autenticação.

Rotas Protegidas (POST, PUT, DELETE): Exigem um Bearer Token.

O token de autenticação "secreto" para este projeto está no arquivo auth/AuthMiddleware.php: Token: meu_token_secreto_123

Como enviar o token (via cabeçalho Authorization):

Bash

-H "Authorization: Bearer meu_token_secreto_123"
3.2. API: Participantes (Ensino Médio)
Endpoint: api_ensino_medio.php

A. Listar Todos (GET) - Público
Bash

curl http://localhost/projeto_participantes/api_ensino_medio.php
B. Buscar Um (GET) - Público
Substitua ?id=1 pelo ID desejado.

Bash

curl http://localhost/projeto_participantes/api_ensino_medio.php?id=1
C. Criar (POST) - Protegido
Teste 1 (FALHA - Sem Token):

Bash

curl -X POST -H "Content-Type: application/json" -d "{\"nome_completo\":\"Joao Sem Token\", \"email\":\"joao.semtoken@email.com\"}" http://localhost/projeto_participantes/api_ensino_medio.php
Resposta Esperada: {"error":"Token de autorização não fornecido."}

Teste 2 (SUCESSO - Com Token):

Bash

curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer meu_token_secreto_123" -d "{\"nome_completo\":\"Joao Autorizado\", \"email\":\"joao.auth@email.com\", \"serie\":\"3o Ano\", \"instituicao\":\"Colegio Central\"}" http://localhost/projeto_participantes/api_ensino_medio.php
Resposta Esperada: {"id":1,"message":"Participante (EM) criado com sucesso"}

D. Atualizar (PUT) - Protegido
Vamos atualizar o participante com id=1.

Teste 1 (FALHA - Sem Token):

Bash

curl -X PUT -H "Content-Type: application/json" -d "{\"nome_completo\":\"Update Sem Token\"}" http://localhost/projeto_participantes/api_ensino_medio.php?id=1
Resposta Esperada: {"error":"Token de autorização não fornecido."}

Teste 2 (SUCESSO - Com Token):

Bash

curl -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer meu_token_secreto_123" -d "{\"nome_completo\":\"Joao Autorizado Silva\", \"email\":\"joao.auth.silva@email.com\", \"serie\":\"Formado\"}" http://localhost/projeto_participantes/api_ensino_medio.php?id=1
Resposta Esperada: {"id":"1","message":"Participante (EM) atualizado com sucesso"}

E. Deletar (DELETE) - Protegido
Vamos deletar o participante com id=1.

Teste 1 (FALHA - Sem Token):

Bash

curl -X DELETE http://localhost/projeto_participantes/api_ensino_medio.php?id=1
Resposta Esperada: {"error":"Token de autorização não fornecido."}

Teste 2 (SUCESSO - Com Token):

Bash

curl -X DELETE -H "Authorization: Bearer meu_token_secreto_123" http://localhost/projeto_participantes/api_ensino_medio.php?id=1
Resposta Esperada: {"id":"1","message":"Participante (EM) deletado com sucesso"}

3.3. API: Participantes (Comunidade Geral)
Endpoint: api_comunidade_geral.php (O processo de teste é idêntico, mudando apenas o endpoint e os dados)

A. Listar Todos (GET) - Público
Bash

curl http://localhost/projeto_participantes/api_comunidade_geral.php
B. Criar (POST) - Protegido
Teste 1 (FALHA - Sem Token):

Bash

curl -X POST -H "Content-Type: application/json" -d "{\"nome_completo\":\"Maria Sem Token\", \"email\":\"maria.semtoken@email.com\"}" http://localhost/projeto_participantes/api_comunidade_geral.php
Resposta Esperada: {"error":"Token de autorização não fornecido."}

Teste 2 (SUCESSO - Com Token):

Bash

curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer meu_token_secreto_123" -d "{\"nome_completo\":\"Maria Autorizada\", \"email\":\"maria.auth@email.com\", \"grau_escolaridade\":\"Superior Completo\"}" http://localhost/projeto_participantes/api_comunidade_geral.php
Resposta Esperada: {"id":1,"message":"Participante (CG) criado com sucesso"}

(O teste para PUT e DELETE da Comunidade Geral segue o mesmo padrão acima).
### 3.4. API: Alunos (Fatec)

**Endpoint:** `api_aluno_fatec.php`

#### A. Listar Todos (GET) - Público

```sh
curl http://localhost/projeto_participantes/api_aluno_fatec.php
B. Buscar Um (GET) - Público
Substitua ?id=1 pelo ID desejado.

Bash

curl http://localhost/projeto_participantes/api_aluno_fatec.php?id=1
C. Criar (POST) - Protegido
Teste 1 (FALHA - Sem Token):

Bash

curl -X POST -H "Content-Type: application/json" -d "{\"nome_completo\":\"Carlos Sem Token\", \"email\":\"carlos.semtoken@fatec.com\"}" http://localhost/projeto_participantes/api_aluno_fatec.php
Resposta Esperada: {"error":"Token de autorização não fornecido."}

Teste 2 (SUCESSO - Com Token):

Bash

curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer meu_token_secreto_123" -d "{\"nome_completo\":\"Carlos Autorizado\", \"email\":\"carlos.auth@fatec.com\", \"curso\":\"ADS\", \"semestre\":\"4o\"}" http://localhost/projeto_participantes/api_aluno_fatec.php
Resposta Esperada: {"id":1,"message":"Aluno(a) Fatec criado(a) com sucesso"}

D. Atualizar (PUT) - Protegido
Vamos atualizar o aluno com id=1.

Bash

curl -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer meu_token_secreto_123" -d "{\"nome_completo\":\"Carlos Autorizado Santos\", \"email\":\"carlos.santos@fatec.com\", \"semestre\":\"5o\"}" http://localhost/projeto_participantes/api_aluno_fatec.php?id=1
Resposta Esperada: {"id":"1","message":"Aluno(a) Fatec atualizado(a) com sucesso"}

E. Deletar (DELETE) - Protegido
Vamos deletar o aluno com id=1.

Bash

curl -X DELETE -H "Authorization: Bearer meu_token_secreto_123" http://localhost/projeto_participantes/api_aluno_fatec.php?id=1
Resposta Esperada: {"id":"1","message":"Aluno(a) Fatec deletado(a) com sucesso"}