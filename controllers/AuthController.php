<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function register($data) {
        // 1. Validação de dados
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            return ['status' => 400, 'message' => 'Todos os campos são obrigatórios.'];
        }

        // 2. Regra: Senha com no mínimo 8 caracteres
        if (strlen($data['senha']) < 8) {
            return ['status' => 400, 'message' => 'A senha deve ter no mínimo 8 caracteres.'];
        }

        // 3. Regra: Validação de email único
        if ($this->usuarioModel->findByEmail($data['email'])) {
            return ['status' => 409, 'message' => 'Este email já está cadastrado.']; // 409 Conflict
        }

        // 4. Criptografar senha
        $senhaHash = password_hash($data['senha'], PASSWORD_BCRYPT);

        // 5. Determinar nível (pode vir do front-end ou ter um padrão)
        $nivel = $data['nivel_acesso'] ?? 'participante';

        // 6. Criar usuário
        $this->usuarioModel->createUser($data['nome'], $data['email'], $senhaHash, $nivel);
        return ['status' => 201, 'message' => 'Usuário cadastrado com sucesso.'];
    }

    public function login($data) {
        $usuario = $this->usuarioModel->findByEmail($data['email']);

        // 1. Verifica se usuário existe
        if (!$usuario) {
            return ['status' => 401, 'message' => 'Email ou senha inválidos.'];
        }

        // 2. Regra: Bloqueio temporário
        if ($usuario['bloqueado_ate'] && new DateTime() < new DateTime($usuario['bloqueado_ate'])) {
            return ['status' => 403, 'message' => 'Conta bloqueada temporariamente. Tente mais tarde.'];
        }

        // 3. Validação de Senha
        if (password_verify($data['senha'], $usuario['senha'])) {
            // Sucesso
            $this->usuarioModel->resetLock($usuario['email']);

            // 4. Iniciar Sessão
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];
            $_SESSION['last_activity'] = time(); // Para o timeout

            return ['status' => 200, 'message' => 'Login bem-sucedido.', 'user' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'nivel' => $usuario['nivel_acesso']
            ]];
        } else {
            // Falha
            // 5. Regra: Bloqueio após 3 tentativas
            $novasTentativas = $usuario['tentativas_falhas'] + 1;
            $this->usuarioModel->updateLoginAttempts($usuario['email'], $novasTentativas);

            if ($novasTentativas >= 3) {
                $this->usuarioModel->lockAccount($usuario['email']);
                return ['status' => 403, 'message' => 'Conta bloqueada por 15 minutos.'];
            }
            return ['status' => 401, 'message' => 'Email ou senha inválidos.'];
        }
    }
}