-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 14/07/2026 às 01:56
-- Versão do servidor: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ess_apps`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuraplanejamentos`
--

CREATE TABLE IF NOT EXISTS `configuraplanejamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semestre` char(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `versao` smallint(2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='planejamento';

-- --------------------------------------------------------

--
-- Estrutura para tabela `dias`
--

CREATE TABLE IF NOT EXISTS `dias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordem` tinyint(4) NOT NULL,
  `dia` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas`
--

CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `disciplina` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `creditos` tinyint(2) DEFAULT NULL,
  `carga_horaria` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `periodo_diurno` tinyint(2) DEFAULT NULL,
  `periodo_noturno` tinyint(2) DEFAULT NULL,
  `requisitos` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `optativa` tinyint(1) NOT NULL DEFAULT 0,
  `departamento` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `observacoes` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `curriculo` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='planejamento';

-- --------------------------------------------------------

--
-- Estrutura para tabela `docentes`
--

CREATE TABLE IF NOT EXISTS `docentes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `cpf` char(14) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siape` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cress` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `regiao` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `codigo_telefone` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '21',
  `telefone` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `codigo_celular` char(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '21',
  `celular` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `curriculolattes` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `atualizacaolattes` date NULL DEFAULT NULL,
  `dataingresso` date DEFAULT NULL,
  `tipocargo` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `departamento` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `dataegresso` date DEFAULT NULL,
  `motivoegresso` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `observacoes` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT 0,
  `estagiarios_count` int(10) unsigned NULL DEFAULT 0,
  `status` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'ativo',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `docente_disponibilidades`
--

CREATE TABLE IF NOT EXISTS `docente_disponibilidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `docente_id` int(11) DEFAULT NULL,
  `configuraplanejamento_id` int(11) DEFAULT NULL,
  `disponivel` tinyint(1) NOT NULL,
  `motivo` varchar(100) NOT NULL DEFAULT '',
  `observacoes` text DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ementas`
--

CREATE TABLE IF NOT EXISTS `ementas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configuraplanejamento_id` int(3) NOT NULL,
  `disciplina_id` int(3) NOT NULL,
  `optativa_id` int(3) NOT NULL,
  `docente_id` int(3) NOT NULL,
  `titulo` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ementa` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='planejamento';

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios`
--

CREATE TABLE IF NOT EXISTS `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordem` int(11) NOT NULL,
  `horario` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `optativas`
--

CREATE TABLE IF NOT EXISTS `optativas` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `disciplina` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `creditos` tinyint(2) DEFAULT NULL,
  `carga_horaria` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `periodo_diurno` tinyint(2) DEFAULT NULL,
  `periodo_noturno` tinyint(2) DEFAULT NULL,
  `requisitos` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `optativa` tinyint(1) NOT NULL DEFAULT 0,
  `departamento` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `observacoes` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='planejamento';

-- --------------------------------------------------------

--
-- Estrutura para tabela `planejamentos`
--

CREATE TABLE IF NOT EXISTS `planejamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configuraplanejamento_id` int(3) NOT NULL,
  `turno` varchar(10) DEFAULT NULL,
  `periodo` tinyint(4) DEFAULT NULL,
  `dia_id` int(11) DEFAULT NULL,
  `horario_id` int(11) DEFAULT NULL,
  `sala_id` int(11) DEFAULT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `ementa_id` int(3) DEFAULT NULL,
  `optativa_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `salas`
--

CREATE TABLE IF NOT EXISTS `salas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sala` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `localizacao` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lotacao` int(11) NOT NULL,
  `recursos` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `observacoes` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `senha` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `role` enum('READ ONLY','NO ACCESS','ADMIN') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'READ ONLY',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmaotps`
--

CREATE TABLE IF NOT EXISTS `turmaotps` (
  `id` int(11) NOT NULL,
  `configuraplanejamento_id` int(11) NOT NULL,
  `turno` varchar(10) DEFAULT NULL,
  `periodo` tinyint(4) DEFAULT NULL,
  `turmaotp` varchar(5) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `dia_id` int(11) DEFAULT NULL,
  `horario_id` int(11) DEFAULT NULL,
  `sala_id` int(11) DEFAULT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
