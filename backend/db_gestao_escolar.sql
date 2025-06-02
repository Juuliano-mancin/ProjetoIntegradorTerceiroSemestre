-- 
-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS db_gestao_escolar
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE db_gestao_escolar;

-- Tabela de Alunos
CREATE TABLE IF NOT EXISTS tb_alunos (
    id_alunos INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nome_social VARCHAR(100),
    sobrenome VARCHAR(150) NOT NULL,
    genero ENUM('masculino', 'feminino', 'nao_binario', 'transgenero', 'outro', 'nao_informado') NOT NULL DEFAULT 'nao_informado',
    nascimento DATE NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    rg VARCHAR(12) NOT NULL UNIQUE,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    tipo_matricula ENUM('pre_vestibular', 'regular') NOT NULL DEFAULT 'pre_vestibular',
    semestre INT NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    cep CHAR(8),
    logradouro VARCHAR(200) NOT NULL,
    numero VARCHAR(10),
    complemento VARCHAR(30),
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf CHAR(2) NOT NULL,
    cont_emergencia_nome VARCHAR(150),
    cont_emergencia_relacao ENUM('pai_mae', 'parentesco_proximo', 'outros', 'nenhum') DEFAULT 'nenhum',
    cont_emergencia_contato VARCHAR(20),
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_aluno_nome (nome, sobrenome),
    INDEX idx_aluno_matricula (matricula),
    INDEX idx_aluno_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Cadastro de alunos';

-- Tabela de Turmas
CREATE TABLE IF NOT EXISTS tb_turmas (
    id_turmas INT AUTO_INCREMENT PRIMARY KEY,
    nome_turma VARCHAR(100) NOT NULL,
    tipo_turma ENUM('pre_vestibular', 'regular') NOT NULL DEFAULT 'pre_vestibular',
    periodo ENUM('manha', 'tarde', 'noite') NOT NULL,
    data_criacao DATE NOT NULL DEFAULT (CURRENT_DATE),
    numero_alunos INT DEFAULT 0 COMMENT 'Atualizado via trigger',
    INDEX idx_turma_nome (nome_turma),
    INDEX idx_turma_tipo (tipo_turma),
    INDEX idx_turma_periodo (periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Cadastro de turmas';

-- Tabela de Relacionamento Alunos-Turmas
CREATE TABLE IF NOT EXISTS tb_alunos_turma (
    id_aluno_turma INT AUTO_INCREMENT PRIMARY KEY,
    id_aluno INT NOT NULL,
    id_turma INT NOT NULL,
    numero_chamada INT NOT NULL,
    data_matricula DATE NOT NULL DEFAULT (CURRENT_DATE),
    total_presencas INT DEFAULT 0 COMMENT 'Total de presenças registradas',
    total_faltas INT DEFAULT 0 COMMENT 'Total de faltas registradas',
    percentual_presenca DECIMAL(5,2) DEFAULT 100.00 COMMENT 'Calculado automaticamente',
    
    CONSTRAINT fk_aluno FOREIGN KEY (id_aluno) 
        REFERENCES tb_alunos(id_alunos)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
        
    CONSTRAINT fk_turma FOREIGN KEY (id_turma) 
        REFERENCES tb_turmas(id_turmas)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    
    UNIQUE KEY uk_aluno_turma (id_aluno, id_turma),
    UNIQUE KEY uk_turma_chamada (id_turma, numero_chamada),
    
    INDEX idx_matricula_aluno (id_aluno),
    INDEX idx_matricula_turma (id_turma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Relacionamento alunos-turmas';

-- =============================================
-- TRIGGERS PARA ATUALIZAÇÃO AUTOMÁTICA
-- =============================================

DELIMITER //

-- Trigger para inserção de matrícula
CREATE TRIGGER tg_after_insert_matricula
AFTER INSERT ON tb_alunos_turma
FOR EACH ROW
BEGIN
    -- Atualiza contador de alunos na turma
    UPDATE tb_turmas 
    SET numero_alunos = (
        SELECT COUNT(*) 
        FROM tb_alunos_turma 
        WHERE id_turma = NEW.id_turma
    )
    WHERE id_turma = NEW.id_turma;
END//

-- Trigger para atualização de matrícula
CREATE TRIGGER tg_after_update_matricula
AFTER UPDATE ON tb_alunos_turma
FOR EACH ROW
BEGIN
    -- Se a turma foi alterada, atualiza ambas as turmas
    IF OLD.id_turma != NEW.id_turma THEN
        -- Atualiza turma antiga
        UPDATE tb_turmas 
        SET numero_alunos = (
            SELECT COUNT(*) 
            FROM tb_alunos_turma 
            WHERE id_turma = OLD.id_turma
        )
        WHERE id_turma = OLD.id_turma;
        
        -- Atualiza nova turma
        UPDATE tb_turmas 
        SET numero_alunos = (
            SELECT COUNT(*) 
            FROM tb_alunos_turma 
            WHERE id_turma = NEW.id_turma
        )
        WHERE id_turma = NEW.id_turma;
    END IF;
END//

-- Trigger para exclusão de matrícula
CREATE TRIGGER tg_after_delete_matricula
AFTER DELETE ON tb_alunos_turma
FOR EACH ROW
BEGIN
    -- Atualiza contador de alunos na turma
    UPDATE tb_turmas 
    SET numero_alunos = (
        SELECT COUNT(*) 
        FROM tb_alunos_turma 
        WHERE id_turma = OLD.id_turma
    )
    WHERE id_turma = OLD.id_turma;
END//

-- Trigger para cálculo de percentual de presença
CREATE TRIGGER tg_before_update_presencas
BEFORE UPDATE ON tb_alunos_turma
FOR EACH ROW
BEGIN
    -- Calcula percentual sempre que presenças ou faltas forem atualizadas
    IF NEW.total_presencas != OLD.total_presencas OR NEW.total_faltas != OLD.total_faltas THEN
        SET NEW.percentual_presenca = CASE 
            WHEN (NEW.total_presencas + NEW.total_faltas) > 0 
            THEN ROUND((NEW.total_presencas * 100.0) / (NEW.total_presencas + NEW.total_faltas), 2)
            ELSE 100.00
        END;
    END IF;
END//

-- Trigger para validação de número de chamada
CREATE TRIGGER tg_before_insert_chamada
BEFORE INSERT ON tb_alunos_turma
FOR EACH ROW
BEGIN
    DECLARE chamada_existe INT DEFAULT 0;
    
    -- Verifica se o número de chamada já existe na turma
    SELECT COUNT(*) INTO chamada_existe
    FROM tb_alunos_turma
    WHERE id_turma = NEW.id_turma AND numero_chamada = NEW.numero_chamada;
    
    IF chamada_existe > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erro: Número de chamada já está em uso nesta turma';
    END IF;
END//

DELIMITER ;

-- =============================================
-- PROCEDURES ÚTEIS (OPCIONAIS)
-- =============================================

DELIMITER //

-- Procedure para matricular aluno em turma
CREATE PROCEDURE sp_matricular_aluno(
    IN p_id_aluno INT,
    IN p_id_turma INT,
    IN p_numero_chamada INT
)
BEGIN
    DECLARE turma_existe INT;
    DECLARE aluno_existe INT;
    
    -- Verifica se turma existe
    SELECT COUNT(*) INTO turma_existe FROM tb_turmas WHERE id_turmas = p_id_turma;
    IF turma_existe = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Turma não encontrada';
    END IF;
    
    -- Verifica se aluno existe
    SELECT COUNT(*) INTO aluno_existe FROM tb_alunos WHERE id_alunos = p_id_aluno;
    IF aluno_existe = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Aluno não encontrado';
    END IF;
    
    -- Realiza matrícula
    INSERT INTO tb_alunos_turma (id_aluno, id_turma, numero_chamada)
    VALUES (p_id_aluno, p_id_turma, p_numero_chamada);
    
    SELECT 'Matrícula realizada com sucesso' AS resultado;
END//

DELIMITER ;