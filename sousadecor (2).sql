-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 17:14
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
(5, 'Almofadas Floral', 'https://www.tazmo.com.br/cdn/shop/files/Capa-de-Almofada-Boho-Floral-Almofadas-Decorativas-Tazmo-361.webp?v=1725074771&width=1024', '59,90');

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
(1, 'Vaso Decorativo Moderno', 'https://m.media-amazon.com/images/I/71+K2WB572L._AC_UF894,1000_QL80_.jpg', '89,90'),
(2, 'Arranjo de Flores de Vidro', 'uploads/produtos/1764345550_imagem_2025-11-28_125840295.png', '349');

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
(4, 'Calendário de Mesa', 'https://a-static.mlcdn.com.br/420x420/calendario-de-mesa-decorativo-permanente-com-plaquinhas-madeira-decoracao-escritorio-mm/lojamultimimo/2941-dou/6e024e237c7520f8a6fdf0ac707cafb9.jpeg', '99,90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Cerâmica Coração', 'https://images.tcdn.com.br/img/img_prod/963739/coracao_de_ceramica_decorativa_em_bege_fosco_1197_1_34a209cc7098791e525f0d427be30840.jpg', '159,90'),
(4, 'Vaso Cerâmica Ione', 'uploads/produtos/1764345405_imagem_2025-11-28_125608685.png', '59'),
(5, 'Cerâmica Cauda de Baleia', 'uploads/produtos/1764345447_imagem_2025-11-28_125711850.png', '19'),
(6, 'Vaso de Cerâmica Luan', 'uploads/produtos/1764345493_imagem_2025-11-28_125749334.png', '219');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `senha`, `telefone`, `endereco`, `data_cadastro`) VALUES
(1, '123', '123@gmail.com', '$2y$10$SFumaOOb8vm0gNSJsIWWteQok8whPGwijhw03W5C1a7D8rHQE33ay', '', '', '2025-11-28 01:07:52'),
(2, 'paulo', 'paulo@gmail.com', '$2y$10$eTC.eGOV0Zmu2N1ox8IVY.OQo.Uz1lJDujtEHtIhOo2x56nUT/gKW', '', '', '2025-11-28 14:46:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pendente',
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras`
--

INSERT INTO `compras` (`id`, `id_usuario`, `id_produto`, `quantidade`, `status`, `data_compra`) VALUES
(1, 1, 2, 1, 'espera', '2025-11-28 14:40:37'),
(2, 1, 1, 3, 'espera', '2025-11-28 14:40:37'),
(3, 2, 6, 1, 'espera', '2025-11-28 14:47:13'),
(4, 1, 3, 1, 'espera', '2025-11-28 15:11:46'),
(5, 1, 6, 2, 'pendente', '2025-11-28 15:38:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data` varchar(50) NOT NULL,
  `assunto` varchar(150) NOT NULL,
  `mensagem` varchar(500) NOT NULL,
  `lido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `nome`, `data`, `assunto`, `mensagem`, `lido`) VALUES
(1, '123', '2025-11-28 15:28:05', 'Gostei de você', 'Gostaria de conversar', 1);

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
(3, 'Kit 3 Porta Retratos', 'https://img.elo7.com.br/product/685x685/3BFBFE5/kit-3-porta-retratos-decoracao-moldura-caixa-alta-e-vidro-kit-3-porta-retrato.jpg', '119,90'),
(4, 'Conjunto 2 Porta Retrato Casamento.', 'uploads/produtos/1764345090_imagem_2025-11-28_125052983.png', '79'),
(5, 'Porta Retrato Cortiça', 'uploads/produtos/1764345313_imagem_2025-11-28_125454343.png', '49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `preco` int(11) DEFAULT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `imagem`, `preco`, `estoque`, `destaque`, `descricao`) VALUES
(1, 'Vaso Decorativo Moderno', 'https://m.media-amazon.com/images/I/71+K2WB572L._AC_UF894,1000_QL80_.jpg', 42, 1, 0, ''),
(2, 'Cerâmica Coração', 'https://images.tcdn.com.br/img/img_prod/963739/coracao_de_ceramica_decorativa_em_bege_fosco_1197_1_34a209cc7098791e525f0d427be30840.jpg', 89, 23, 0, ''),
(3, 'Kit 3 Porta Retratos', 'https://img.elo7.com.br/product/685x685/3BFBFE5/kit-3-porta-retratos-decoracao-moldura-caixa-alta-e-vidro-kit-3-porta-retrato.jpg', 12, 4, 0, ''),
(4, 'Calendário de Mesa', 'https://a-static.mlcdn.com.br/420x420/calendario-de-mesa-decorativo-permanente-com-plaquinhas-madeira-decoracao-escritorio-mm/lojamultimimo/2941-dou/6e024e237c7520f8a6fdf0ac707cafb9.jpeg', 15, 3, 0, ''),
(5, 'Almofadas Floral', 'https://www.tazmo.com.br/cdn/shop/files/Capa-de-Almofada-Boho-Floral-Almofadas-Decorativas-Tazmo-361.webp?v=1725074771&width=1024', 109, 1, 0, ''),
(6, 'Almofadas Veludo', 'uploads/produtos/1764346090_imagem_2025-11-28_130809011.png', 69, 2, 1, 'Coleção Midnight, um pouquinho da Califórnia para sua casa!\r\nDescubra a sofisticação na medida certa e leve o clima californiano para dentro da sua casa com a linda Coleção Midnight. Inspirada no estilo inconfundível da Califórnia, essa coleção irá transformar seus espaços em um verdadeiro refúgio de elegância e conforto.'),
(14, 'Conjunto 2 Porta Retrato Casamento.', 'uploads/produtos/1764345090_imagem_2025-11-28_125052983.png', 79, 2, 0, 'Design exclusivo, visual chique – Adicione um toque de elegância atemporal às suas memórias queridas com um conjunto de 2 molduras decorativas.'),
(15, 'Porta Retrato Cortiça', 'uploads/produtos/1764345313_imagem_2025-11-28_125454343.png', 49, 3, 0, 'Um adorno com aquela pegada moderna e linda!'),
(16, 'Vaso Cerâmica Ione', 'uploads/produtos/1764345405_imagem_2025-11-28_125608685.png', 59, 4, 0, 'De todos os tamanhos, os vasos de transformam sua decoração. '),
(17, 'Cerâmica Cauda de Baleia', 'uploads/produtos/1764345447_imagem_2025-11-28_125711850.png', 19, 3, 0, 'Esse enfeite náutico vai deixar sua decoração incrível! Produto produzido em cerâmica, sendo único e especial. Personalize seu ambiente com muita elegância e originalidade.'),
(18, 'Vaso de Cerâmica Luan', 'uploads/produtos/1764345493_imagem_2025-11-28_125749334.png', 219, 4, 0, 'Sem sombra de dúvidas, um item essencial em qualquer decoração são os vasos. Seja qual for o modelo, tamanho, formato, cor, material, os vasos podem ser usados em diferentes estilos de decoração e são peças que tornam o ambiente mais vivo e alegre.'),
(19, 'Arranjo de Flores de Vidro', 'uploads/produtos/1764345550_imagem_2025-11-28_125840295.png', 349, 1, 0, 'Encante seu ambiente com este lindo arranjo de flores secas, cuidadosamente elaborado com paleta de cores terracota. O destaque fica por conta das delicadas mini margaridas em tom rosa seco, que acrescentam charme e elegância à composição. ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `nome_do_cliente` varchar(300) NOT NULL,
  `data` varchar(30) NOT NULL,
  `preco` varchar(50) NOT NULL,
  `nome_do_produto` varchar(100) NOT NULL,
  `status` enum('pendente','processando','enviado','concluido','cancelado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendas`
--

INSERT INTO `vendas` (`id`, `nome_do_cliente`, `data`, `preco`, `nome_do_produto`, `status`) VALUES
(1, '123', '2025-11-28 16:11:46', '12', 'Kit 3 Porta Retratos', ''),
(2, '123', '2025-11-28 16:38:08', '69', 'Almofadas Veludo', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedores`
--

CREATE TABLE `vendedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `nivel_acesso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendedores`
--

INSERT INTO `vendedores` (`id`, `nome`, `email`, `senha`, `nivel_acesso`) VALUES
(1, 'Paulo Eduardo', 'paulo@gmail.com', '$2y$10$dLLHovLIqKi7WjCY1JdbuuDHk.MIcbPXDb38Ano8np6', 'admin'),
(2, 'Rick', 'Rick@gmail.com', '$2y$10$frMIdNIt/gr4H7L.HARTyuEV/rlG0bUupUAIpuC/jI3', 'vendedor'),
(3, 'ysaac', 'ysaac@gmail.com', '$2y$10$zQfxRAyU1.BUnJZZCzkRSuJLNC/TANZ/jOdwhp35sU5', 'vendedor'),
(4, 'guilherme', 'guilherme@gmail.com', '$2y$10$rPkAcDz1vAd0LUhdEnNgz.kjRyRv8xSWehkSjwurvdolzprePMHu6', 'vendedor'),
(5, '123', '123@gmail.com', '$2y$10$EVFFDGqy0Y2yadw7ond1quRAsflO/xYgGQj17UX9teH7jGgiPTqg.', 'admin');

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
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_carrinho` (`id_usuario`,`id_produto`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `ceramicas`
--
ALTER TABLE `ceramicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
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
-- Índices de tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vendedores`
--
ALTER TABLE `vendedores`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `calendarios`
--
ALTER TABLE `calendarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `ceramicas`
--
ALTER TABLE `ceramicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `portaretrato`
--
ALTER TABLE `portaretrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendedores`
--
ALTER TABLE `vendedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
