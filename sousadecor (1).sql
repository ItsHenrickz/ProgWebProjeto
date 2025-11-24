-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2025 às 15:02
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
-- Banco de dados: `sousadecor`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `almofadas`
--

CREATE TABLE `almofadas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `almofadas`
--

INSERT INTO `almofadas` (`id`, `nome`, `imagem`, `preco`) VALUES
(5, 'Almofadas Floral', 'https://www.tazmo.com.br/cdn/shop/files/Capa-de-Almofada-Boho-Floral-Almofadas-Decorativas-Tazmo-361.webp?v=1725074771&width=1024', 'R$: 59,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `arranjos`
--

CREATE TABLE `arranjos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `arranjos`
--

INSERT INTO `arranjos` (`id`, `nome`, `imagem`, `preco`) VALUES
(1, 'Vaso Decorativo Moderno', 'https://m.media-amazon.com/images/I/71+K2WB572L._AC_UF894,1000_QL80_.jpg', 'R$: 89,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `calendarios`
--

CREATE TABLE `calendarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `calendarios`
--

INSERT INTO `calendarios` (`id`, `nome`, `imagem`, `preco`) VALUES
(4, 'Calendário de Mesa', 'https://a-static.mlcdn.com.br/420x420/calendario-de-mesa-decorativo-permanente-com-plaquinhas-madeira-decoracao-escritorio-mm/lojamultimimo/2941-dou/6e024e237c7520f8a6fdf0ac707cafb9.jpeg', 'R$: 99,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ceramicas`
--

CREATE TABLE `ceramicas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ceramicas`
--

INSERT INTO `ceramicas` (`id`, `nome`, `imagem`, `preco`) VALUES
(2, 'Cerâmica Coração', 'https://chiquehome.com.br/cdn/shop/files/Vaso_Decorativo_para_Sala_Decoracao_de_Ceramica_Coracao.webp?v=1729369134', 'R$: 159,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `nome` varchar(100) NOT NULL,
  `data` varchar(50) NOT NULL,
  `assunto` varchar(150) NOT NULL,
  `mensagem` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `portaretrato`
--

CREATE TABLE `portaretrato` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `portaretrato`
--

INSERT INTO `portaretrato` (`id`, `nome`, `imagem`, `preco`) VALUES
(3, 'Kit 3 Porta Retratos', 'https://img.elo7.com.br/product/685x685/3BFBFE5/kit-3-porta-retratos-decoracao-moldura-caixa-alta-e-vidro-kit-3-porta-retrato.jpg', 'R$: 119,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `imagem`, `preco`) VALUES
(1, 'Vaso Decorativo Moderno', 'https://m.media-amazon.com/images/I/71+K2WB572L._AC_UF894,1000_QL80_.jpg', 'R$: 89,90'),
(2, 'Cerâmica Coração', 'https://chiquehome.com.br/cdn/shop/files/Vaso_Decorativo_para_Sala_Decoracao_de_Ceramica_Coracao.webp?v=1729369134', 'R$: 159,90'),
(3, 'Kit 3 Porta Retratos', 'https://img.elo7.com.br/product/685x685/3BFBFE5/kit-3-porta-retratos-decoracao-moldura-caixa-alta-e-vidro-kit-3-porta-retrato.jpg', 'R$: 119,90'),
(4, 'Calendário de Mesa', 'https://a-static.mlcdn.com.br/420x420/calendario-de-mesa-decorativo-permanente-com-plaquinhas-madeira-decoracao-escritorio-mm/lojamultimimo/2941-dou/6e024e237c7520f8a6fdf0ac707cafb9.jpeg', 'R$: 99,90'),
(5, 'Almofadas Floral', 'https://www.tazmo.com.br/cdn/shop/files/Capa-de-Almofada-Boho-Floral-Almofadas-Decorativas-Tazmo-361.webp?v=1725074771&width=1024', 'R$: 59,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `nome_do_cliente` varchar(300) NOT NULL,
  `data` varchar(30) NOT NULL,
  `preco` varchar(50) NOT NULL,
  `nome_do_produto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `almofadas`
--
ALTER TABLE `almofadas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `arranjos`
--
ALTER TABLE `arranjos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `calendarios`
--
ALTER TABLE `calendarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ceramicas`
--
ALTER TABLE `ceramicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `portaretrato`
--
ALTER TABLE `portaretrato`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `almofadas`
--
ALTER TABLE `almofadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `arranjos`
--
ALTER TABLE `arranjos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `calendarios`
--
ALTER TABLE `calendarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `ceramicas`
--
ALTER TABLE `ceramicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `portaretrato`
--
ALTER TABLE `portaretrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
