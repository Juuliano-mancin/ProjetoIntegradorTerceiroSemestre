-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 05:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gestao_escolar`
--
CREATE Database db_gestao_escolar;
USE db_gestao_escolar;

-- --------------------------------------------------------

--
-- Table structure for table `tb_alunos`
--

CREATE TABLE `tb_alunos` (
  `id_alunos` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `nome_social` varchar(100) DEFAULT NULL,
  `sobrenome` varchar(150) NOT NULL,
  `genero` enum('masculino','feminino','nao_binario','transgenero','outro','nao_informado') NOT NULL DEFAULT 'nao_informado',
  `nascimento` date NOT NULL,
  `cpf` char(11) NOT NULL,
  `rg` varchar(12) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `tipo_matricula` enum('pre_vestibular','vestibulinho') NOT NULL DEFAULT 'pre_vestibular',
  `semestre` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cep` char(8) DEFAULT NULL,
  `logradouro` varchar(200) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(30) DEFAULT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` char(2) NOT NULL,
  `cont_emergencia_nome` varchar(150) DEFAULT NULL,
  `cont_emergencia_relacao` enum('pai_mae','parentesco_proximo','outros','nenhum') DEFAULT 'nenhum',
  `cont_emergencia_contato` varchar(20) DEFAULT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_alunos`
--

INSERT INTO `tb_alunos` (`id_alunos`, `nome`, `nome_social`, `sobrenome`, `genero`, `nascimento`, `cpf`, `rg`, `matricula`, `tipo_matricula`, `semestre`, `telefone`, `email`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cont_emergencia_nome`, `cont_emergencia_relacao`, `cont_emergencia_contato`, `status`, `data_cadastro`) VALUES
(1, 'Calebe', NULL, 'Fogaça', 'masculino', '2000-12-02', '59137217404', '286209980', '2025010001', 'vestibulinho', 1, '+55 51 0688 5502', 'helena53@almeida.br', '05710122', 'Lago Pinto', '874', '', 'Parque Real', 'Mogi Mirim', 'SP', 'Vitor Lima', 'outros', '(081) 8739 8950', 'ativo', '2025-06-10 00:21:11'),
(2, 'Agatha', NULL, 'Silva', 'nao_informado', '2006-10-30', '75955797489', '685426593', '2025010002', 'vestibulinho', 1, '0300-786-5802', 'kferreira@mendes.br', '76950391', 'Núcleo de Alves', '73', 'quibusdam', 'Parque Real', 'Mogi Mirim', 'SP', 'Rebeca Caldeira', 'outros', '(011) 7387 2287', 'ativo', '2025-06-10 00:21:11'),
(3, 'Vitor Gabriel', NULL, 'Rezende', 'transgenero', '2000-03-10', '17223471978', '489991690', '2025010003', 'pre_vestibular', 1, '+55 (071) 0454-6601', 'marina11@alves.br', '86054708', 'Pátio Samuel Araújo', '26', 'veritatis', 'Centro', 'Mogi Mirim', 'SP', 'João Lucas Duarte', 'parentesco_proximo', '+55 21 0868 4246', 'ativo', '2025-06-10 00:21:11'),
(4, 'Vicente', NULL, 'Costela', 'nao_informado', '2008-07-15', '47136963559', '555235645', '2025010004', 'vestibulinho', 1, '+55 71 2966 0270', 'tporto@uol.com.br', '28588825', 'Colônia de Viana', '63', 'consectetur', 'Centro', 'Mogi Mirim', 'SP', 'Luiz Fernando Farias', 'pai_mae', '+55 (084) 5460-8138', 'ativo', '2025-06-10 00:21:11'),
(5, 'João', NULL, 'Aragão', 'feminino', '2002-09-28', '78461535442', '247957085', '2025010005', 'pre_vestibular', 1, '+55 21 9515 8828', 'usales@araujo.org', '56382-90', 'Residencial de Mendes', '610', '', 'Jardim Bela Vista', 'Mogi Guaçu', 'SP', 'Isabelly Peixoto', 'outros', '(051) 5375 3260', 'ativo', '2025-06-10 00:21:11'),
(6, 'Amanda', 'Amanda', 'Nascimento', 'nao_informado', '2007-12-14', '25445222671', '919478932', '2025010006', 'pre_vestibular', 1, '84 6514-1489', 'nascimentocalebe@bol.com.br', '41795-37', 'Travessa Isabella Monteiro', '97', 'repellat', 'Santa Cruz', 'Mogi Guaçu', 'SP', 'Joaquim Barros', 'nenhum', '+55 (061) 1067-2780', 'ativo', '2025-06-10 00:21:11'),
(7, 'Brenda', NULL, 'Viana', 'nao_informado', '2001-11-05', '34109540990', '641765260', '2025010007', 'vestibulinho', 1, '+55 (051) 7894 9056', 'fernandesmiguel@peixoto.com', '72304828', 'Lagoa Sales', '53', 'quia', 'Santa Cruz', 'Mogi Mirim', 'SP', 'Calebe Pires', 'outros', '0800-884-3816', 'ativo', '2025-06-10 00:21:11'),
(8, 'Alice', NULL, 'Porto', 'masculino', '2001-04-29', '77567761789', '464564284', '2025010008', 'pre_vestibular', 1, '(021) 8527-2333', 'kaiquecardoso@araujo.com', '58003-64', 'Condomínio Miguel Martins', '18', '', 'Santa Cruz', 'Mogi Guaçu', 'SP', 'Sra. Valentina da Costa', 'nenhum', '(061) 3638-6683', 'ativo', '2025-06-10 00:21:11'),
(9, 'Yago', NULL, 'Cardoso', 'nao_informado', '2007-11-22', '8024890646', '307933913', '2025010009', 'pre_vestibular', 1, '+55 84 7121 7589', 'wsilveira@moreira.net', '01819-51', 'Estação Ana Beatriz Costela', '12', 'dolorem', 'Santa Cruz', 'Mogi Mirim', 'SP', 'Isaac Azevedo', 'nenhum', '+55 41 2376 3600', 'ativo', '2025-06-10 00:21:11'),
(10, 'Heitor', NULL, 'Correia', 'outro', '2007-04-15', '11577929559', '144235461', '2025010010', 'pre_vestibular', 1, '+55 (061) 5733 3652', 'goncalvespietro@monteiro.net', '08113432', 'Aeroporto de Viana', '96', '', 'Centro', 'Mogi Mirim', 'SP', 'Dr. Pedro Lucas Costela', 'outros', '84 0945 5058', 'ativo', '2025-06-10 00:21:11'),
(11, 'Sabrina', NULL, 'Almeida', 'feminino', '2004-06-10', '51882710695', '772685368', '2025010011', 'pre_vestibular', 1, '+55 (061) 9571-9836', 'juanmoraes@rocha.org', '60402352', 'Conjunto Castro', '21', 'magni', 'Santa Cruz', 'Mogi Mirim', 'SP', 'Fernando Moreira', 'outros', '+55 81 4619 4881', 'ativo', '2025-06-10 00:21:11'),
(12, 'Maria Cecília', NULL, 'Teixeira', 'masculino', '2001-06-30', '40071140121', '858185378', '2025010012', 'vestibulinho', 1, '(041) 7301 1917', 'maria-eduarda22@uol.com.br', '63306212', 'Ladeira de Peixoto', '56', '', 'Jardim Bela Vista', 'Mogi Guaçu', 'SP', 'Antônio Costa', 'parentesco_proximo', '0300 649 0834', 'ativo', '2025-06-10 00:21:11'),
(13, 'Pedro Henrique', NULL, 'Vieira', 'outro', '2005-03-11', '4265184600', '714663636', '2025010013', 'pre_vestibular', 1, '+55 (011) 4436-3706', 'joaquimbarros@campos.net', '96851-31', 'Setor Lima', '4', '', 'Santa Cruz', 'Mogi Guaçu', 'SP', 'Alícia da Costa', 'nenhum', '51 1067 9652', 'ativo', '2025-06-10 00:21:11'),
(14, 'Luna', NULL, 'Nunes', 'outro', '2006-09-24', '93020729214', '111596854', '2025010014', 'vestibulinho', 1, '+55 31 0322 9458', 'miguel62@bol.com.br', '09928991', 'Via Luiz Felipe Vieira', '60', '', 'Santa Cruz', 'Mogi Mirim', 'SP', 'Vitor Gabriel Rezende', 'nenhum', '31 4899 5320', 'ativo', '2025-06-10 00:21:11'),
(15, 'Vinicius', NULL, 'Barros', 'nao_informado', '2009-05-28', '64840386018', '861384203', '2025010015', 'vestibulinho', 1, '31 6219 1396', 'rodriguesemanuella@souza.br', '45723-10', 'Distrito de Moura', '3', 'dignissimos', 'Santa Cruz', 'Mogi Guaçu', 'SP', 'Bárbara Nunes', 'nenhum', '+55 (051) 8382-7807', 'ativo', '2025-06-10 00:21:11'),
(16, 'Henrique', 'Henrique', 'Freitas', 'transgenero', '2005-05-24', '94434092021', '542163099', '2025010016', 'pre_vestibular', 1, '(031) 3167-6506', 'martinsana-luiza@uol.com.br', '10555388', 'Trecho Maria Vitória Mendes', '30', '', 'Vila São João', 'Mogi Guaçu', 'SP', 'Pietro Porto', 'parentesco_proximo', '+55 (081) 7605-2458', 'ativo', '2025-06-10 00:21:11'),
(17, 'Pedro Henrique', NULL, 'Cardoso', 'outro', '2005-04-28', '13086370800', '599583828', '2025010017', 'pre_vestibular', 1, '+55 81 8097 8667', 'joao-guilhermemonteiro@melo.br', '61586134', 'Vila Costa', '42', 'blanditiis', 'Jardim Bela Vista', 'Mogi Guaçu', 'SP', 'Kevin Porto', 'outros', '(021) 7060-1486', 'ativo', '2025-06-10 00:21:11'),
(18, 'Vitor Gabriel', NULL, 'Melo', 'nao_informado', '2005-09-11', '10089628891', '357678956', '2025010018', 'vestibulinho', 1, '0500-210-7685', 'murilolopes@da.br', '53809-72', 'Feira Mariana Ribeiro', '334', '', 'Santa Cruz', 'Mogi Mirim', 'SP', 'Lucas Gabriel Rocha', 'outros', '(071) 7774 6416', 'ativo', '2025-06-10 00:21:11'),
(19, 'Bianca', NULL, 'Castro', 'transgenero', '2004-12-01', '78953391915', '605376657', '2025010019', 'pre_vestibular', 1, '0800-823-0268', 'lara34@hotmail.com', '61968157', 'Vereda de Cunha', '35', 'deserunt', 'Parque Real', 'Mogi Mirim', 'SP', 'Kaique da Costa', 'pai_mae', '+55 (031) 7493-9343', 'ativo', '2025-06-10 00:21:11'),
(20, 'Joana', NULL, 'Barros', 'nao_binario', '2004-09-11', '29067869927', '656177265', '2025010020', 'vestibulinho', 1, '(041) 0225-9250', 'brunomonteiro@cardoso.br', '18809316', 'Feira Lima', '37', 'repellendus', 'Vila São João', 'Mogi Mirim', 'SP', 'Dr. Anthony Rodrigues', 'nenhum', '(041) 6692 9233', 'ativo', '2025-06-10 00:21:11');

--
-- Triggers `tb_alunos`
--
DELIMITER $$
CREATE TRIGGER `tg_before_insert_matricula` BEFORE INSERT ON `tb_alunos` FOR EACH ROW BEGIN
  DECLARE ano_atual CHAR(4);
  DECLARE semestre_atual CHAR(2);
  DECLARE contador INT;

  SET ano_atual = YEAR(CURDATE());
  SET semestre_atual = IF(MONTH(CURDATE()) <= 6, '01', '02');

  SELECT IFNULL(MAX(CAST(SUBSTRING(matricula, 7, 4) AS UNSIGNED)), 0) + 1 INTO contador
  FROM tb_alunos
  WHERE matricula LIKE CONCAT(ano_atual, semestre_atual, '%');

  SET NEW.matricula = CONCAT(ano_atual, semestre_atual, LPAD(contador, 4, '0'));
  SET NEW.semestre = IF(semestre_atual = '01', 1, 2);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_alunos_turma`
--

CREATE TABLE `tb_alunos_turma` (
  `id_aluno_turma` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL,
  `numero_chamada` int(11) NOT NULL,
  `data_matricula` date NOT NULL DEFAULT curdate(),
  `total_presencas` int(11) DEFAULT 0,
  `total_faltas` int(11) DEFAULT 0,
  `percentual_presenca` decimal(5,2) DEFAULT 100.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_alunos_turma`
--

INSERT INTO `tb_alunos_turma` (`id_aluno_turma`, `id_aluno`, `id_turma`, `numero_chamada`, `data_matricula`, `total_presencas`, `total_faltas`, `percentual_presenca`) VALUES
(1, 2, 1, 1, '2025-06-10', 2, 1, 66.67);

--
-- Triggers `tb_alunos_turma`
--
DELIMITER $$
CREATE TRIGGER `tg_after_delete_matricula` AFTER DELETE ON `tb_alunos_turma` FOR EACH ROW BEGIN
  UPDATE tb_turmas
  SET numero_alunos = (
    SELECT COUNT(*) FROM tb_alunos_turma WHERE id_turma = OLD.id_turma
  )
  WHERE id_turmas = OLD.id_turma;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_after_insert_matricula` AFTER INSERT ON `tb_alunos_turma` FOR EACH ROW BEGIN
  UPDATE tb_turmas
  SET numero_alunos = (
    SELECT COUNT(*) FROM tb_alunos_turma WHERE id_turma = NEW.id_turma
  )
  WHERE id_turmas = NEW.id_turma;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_after_update_matricula` AFTER UPDATE ON `tb_alunos_turma` FOR EACH ROW BEGIN
  IF OLD.id_turma != NEW.id_turma THEN
    UPDATE tb_turmas
    SET numero_alunos = (
      SELECT COUNT(*) FROM tb_alunos_turma WHERE id_turma = OLD.id_turma
    )
    WHERE id_turmas = OLD.id_turma;

    UPDATE tb_turmas
    SET numero_alunos = (
      SELECT COUNT(*) FROM tb_alunos_turma WHERE id_turma = NEW.id_turma
    )
    WHERE id_turmas = NEW.id_turma;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_before_insert_chamada` BEFORE INSERT ON `tb_alunos_turma` FOR EACH ROW BEGIN
  DECLARE chamada_existe INT DEFAULT 0;
  SELECT COUNT(*) INTO chamada_existe
  FROM tb_alunos_turma
  WHERE id_turma = NEW.id_turma AND numero_chamada = NEW.numero_chamada;

  IF chamada_existe > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Erro: Número de chamada já está em uso nesta turma';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_before_update_presencas` BEFORE UPDATE ON `tb_alunos_turma` FOR EACH ROW BEGIN
  IF NEW.total_presencas != OLD.total_presencas OR NEW.total_faltas != OLD.total_faltas THEN
    SET NEW.percentual_presenca = CASE
      WHEN (NEW.total_presencas + NEW.total_faltas) > 0
      THEN ROUND((NEW.total_presencas * 100.0) / (NEW.total_presencas + NEW.total_faltas), 2)
      ELSE 100.00
    END;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_chamada`
--

CREATE TABLE `tb_chamada` (
  `id_chamada` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL,
  `data_chamada` date NOT NULL,
  `situacao` enum('presente','ausente') NOT NULL DEFAULT 'ausente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_chamada`
--

INSERT INTO `tb_chamada` (`id_chamada`, `id_aluno`, `id_turma`, `data_chamada`, `situacao`) VALUES
(1, 2, 1, '2025-06-09', 'presente'),
(2, 2, 1, '2025-06-10', 'presente'),
(3, 2, 1, '2025-06-08', 'ausente');

--
-- Triggers `tb_chamada`
--
DELIMITER $$
CREATE TRIGGER `trg_chamada_delete` AFTER DELETE ON `tb_chamada` FOR EACH ROW BEGIN
  IF OLD.situacao = 'presente' THEN
    UPDATE tb_alunos_turma
    SET total_presencas = total_presencas - 1
    WHERE id_aluno = OLD.id_aluno AND id_turma = OLD.id_turma;
  ELSE
    UPDATE tb_alunos_turma
    SET total_faltas = total_faltas - 1
    WHERE id_aluno = OLD.id_aluno AND id_turma = OLD.id_turma;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_chamada_insert` AFTER INSERT ON `tb_chamada` FOR EACH ROW BEGIN
  IF NEW.situacao = 'presente' THEN
    UPDATE tb_alunos_turma
    SET total_presencas = total_presencas + 1
    WHERE id_aluno = NEW.id_aluno AND id_turma = NEW.id_turma;
  ELSE
    UPDATE tb_alunos_turma
    SET total_faltas = total_faltas + 1
    WHERE id_aluno = NEW.id_aluno AND id_turma = NEW.id_turma;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_chamada_update` AFTER UPDATE ON `tb_chamada` FOR EACH ROW BEGIN
  IF OLD.situacao != NEW.situacao THEN
    IF NEW.situacao = 'presente' THEN
      UPDATE tb_alunos_turma
      SET total_presencas = total_presencas + 1,
          total_faltas = total_faltas - 1
      WHERE id_aluno = NEW.id_aluno AND id_turma = NEW.id_turma;
    ELSE
      UPDATE tb_alunos_turma
      SET total_presencas = total_presencas - 1,
          total_faltas = total_faltas + 1
      WHERE id_aluno = NEW.id_aluno AND id_turma = NEW.id_turma;
    END IF;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_turmas`
--

CREATE TABLE `tb_turmas` (
  `id_turmas` int(11) NOT NULL,
  `nome_turma` varchar(100) NOT NULL,
  `tipo_turma` enum('pre_vestibular','vestibulinho') NOT NULL DEFAULT 'pre_vestibular',
  `periodo` enum('manha','tarde','noite') NOT NULL,
  `data_criacao` date NOT NULL DEFAULT curdate(),
  `numero_alunos` int(11) DEFAULT 0,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_turmas`
--

INSERT INTO `tb_turmas` (`id_turmas`, `nome_turma`, `tipo_turma`, `periodo`, `data_criacao`, `numero_alunos`, `status`) VALUES
(1, 'Turma A - Pré-Vestibular 2025', 'pre_vestibular', 'noite', '2025-06-10', 1, 'ativo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_alunos`
--
ALTER TABLE `tb_alunos`
  ADD PRIMARY KEY (`id_alunos`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `rg` (`rg`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `idx_aluno_nome` (`nome`,`sobrenome`),
  ADD KEY `idx_aluno_matricula` (`matricula`),
  ADD KEY `idx_aluno_status` (`status`);

--
-- Indexes for table `tb_alunos_turma`
--
ALTER TABLE `tb_alunos_turma`
  ADD PRIMARY KEY (`id_aluno_turma`),
  ADD UNIQUE KEY `uk_aluno_turma` (`id_aluno`,`id_turma`),
  ADD UNIQUE KEY `uk_turma_chamada` (`id_turma`,`numero_chamada`),
  ADD KEY `idx_matricula_aluno` (`id_aluno`),
  ADD KEY `idx_matricula_turma` (`id_turma`);

--
-- Indexes for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  ADD PRIMARY KEY (`id_chamada`),
  ADD UNIQUE KEY `id_aluno` (`id_aluno`,`id_turma`,`data_chamada`),
  ADD KEY `fk_chamada_turma` (`id_turma`);

--
-- Indexes for table `tb_turmas`
--
ALTER TABLE `tb_turmas`
  ADD PRIMARY KEY (`id_turmas`),
  ADD KEY `idx_turma_nome` (`nome_turma`),
  ADD KEY `idx_turma_tipo` (`tipo_turma`),
  ADD KEY `idx_turma_periodo` (`periodo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_alunos`
--
ALTER TABLE `tb_alunos`
  MODIFY `id_alunos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_alunos_turma`
--
ALTER TABLE `tb_alunos_turma`
  MODIFY `id_aluno_turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  MODIFY `id_chamada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_turmas`
--
ALTER TABLE `tb_turmas`
  MODIFY `id_turmas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_alunos_turma`
--
ALTER TABLE `tb_alunos_turma`
  ADD CONSTRAINT `fk_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tb_alunos` (`id_alunos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turma` FOREIGN KEY (`id_turma`) REFERENCES `tb_turmas` (`id_turmas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  ADD CONSTRAINT `fk_chamada_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tb_alunos` (`id_alunos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chamada_turma` FOREIGN KEY (`id_turma`) REFERENCES `tb_turmas` (`id_turmas`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
