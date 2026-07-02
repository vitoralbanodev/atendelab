-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 04/06/2026 às 02:18
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `atendelab`
--

CREATE DATABASE IF NOT EXISTS `atendelab` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `atendelab`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos`
--

CREATE TABLE `atendimentos` (
  `id_atendimento` int(11) NOT NULL,
  `id_tipo_atendimento` int(11) DEFAULT NULL,
  `id_pessoa` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data_atendimento` date DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `hora` time DEFAULT NULL,
  `status` enum('aberto','em_andamento','concluido') NOT NULL DEFAULT 'aberto',
  `observacao_final` text DEFAULT NULL,
  PRIMARY KEY (`id_atendimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id_endereco` int(11) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(10) NOT NULL,
  PRIMARY KEY (`id_endereco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `id_pessoa` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `curso` varchar(100) DEFAULT NULL,
  `periodo` varchar(20) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_nascimento` date DEFAULT NULL,
  `id_endereco` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_atendimentos`
--

CREATE TABLE `tipo_atendimentos` (
  `id_tipo_atendimento` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_tipo_atendimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','atendente') DEFAULT 'atendente',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `status`, `criado_em`) VALUES
(1, 'Administrador', 'admin@atendelab.com', '$2y$10$MFvpUffGdrXMh1wB3Z/46ufRgZ1mk96GPHmDgwoKzOJusy/vNTCiK', 'admin', 'ativo', '2026-06-04 00:10:34');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  MODIFY `id_atendimento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id_pessoa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_atendimentos`
--
ALTER TABLE `tipo_atendimentos`
  MODIFY `id_tipo_atendimento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
