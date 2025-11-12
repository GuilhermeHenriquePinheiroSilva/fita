-- SQL dump for FITA - Alocação e Gestão de Salas
CREATE DATABASE IF NOT EXISTS `fita_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fita_db`;

-- Salas
CREATE TABLE IF NOT EXISTS salas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  capacidade INT NOT NULL DEFAULT 0,
  acessibilidade TINYINT(1) DEFAULT 0,
  pratica TINYINT(1) DEFAULT 0,
  descricao TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Atividades
CREATE TABLE IF NOT EXISTS atividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  tipo VARCHAR(100) NOT NULL,
  inicio DATETIME NOT NULL,
  fim DATETIME NOT NULL,
  previsao_participantes INT DEFAULT 0,
  precisa_pratica TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Alocações
CREATE TABLE IF NOT EXISTS alocacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  atividade_id INT NOT NULL,
  sala_id INT NOT NULL,
  inicio DATETIME NOT NULL,
  fim DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (atividade_id) REFERENCES atividades(id) ON DELETE CASCADE,
  FOREIGN KEY (sala_id) REFERENCES salas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Equipamentos
CREATE TABLE IF NOT EXISTS equipamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reservas de equipamentos vinculadas a atividades
CREATE TABLE IF NOT EXISTS reservas_equipamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipamento_id INT NOT NULL,
  atividade_id INT NOT NULL,
  quantidade INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id) ON DELETE CASCADE,
  FOREIGN KEY (atividade_id) REFERENCES atividades(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data
INSERT INTO salas (nome, capacidade, acessibilidade, pratica, descricao) VALUES
('Auditório Principal', 200, 1, 0, 'Auditório com som e projetor'),
('Sala 101', 40, 1, 1, 'Laboratório com bancadas'),
('Sala 102', 30, 0, 1, 'Laboratório prático'),
('Sala 103', 25, 1, 0, 'Sala para palestras menores'),
('Sala 201', 60, 0, 0, 'Sala grande');

INSERT INTO atividades (titulo, tipo, inicio, fim, previsao_participantes, precisa_pratica) VALUES
('Oficina IoT', 'Oficina', '2025-11-20 09:00:00', '2025-11-20 11:00:00', 30, 1),
('Palestra Abertura', 'Palestra', '2025-11-20 10:00:00', '2025-11-20 11:30:00', 150, 0),
('Minicurso Robótica', 'Minicurso', '2025-11-20 11:30:00', '2025-11-20 13:00:00', 50, 1);

INSERT INTO equipamentos (nome, descricao) VALUES ('Projetor','Projetor HDMI'),('Microfone','Microfone sem fio');

-- Reserva exemplo
INSERT INTO reservas_equipamentos (equipamento_id, atividade_id, quantidade) VALUES (1,1,1),(2,2,2);
