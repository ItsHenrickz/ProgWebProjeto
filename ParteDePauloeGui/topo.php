<?php
// 1. Inicia a sessão se ainda não tiver sido iniciada.
// session_status() é usado para evitar erro se a sessão já estiver ativa.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Inclui o arquivo de configuração e conexão com o banco de dados.
// Deve estar um nível acima da pasta 'parteAdmin'.
require_once "../ParteDeRick/config.inc.php";

// 3. --- VERIFICAÇÃO DE LOGIN DE ADMIN (ESSENCIAL) ---
// Se o vendedor não estiver logado, ele é redirecionado para a tela de login.
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: ../ParteDePauloeGui/login.php");
    exit();
}

// O nome do vendedor logado, para personalizar o cabeçalho.
$nome_vendedor = $_SESSION['admin_nome'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin | Sousa Decor</title>
    <link rel="stylesheet" href="../style.css"> 
    <style>
        /* Estilos específicos para a área administrativa, usando a paleta de cores */
        
        .admin-header {
            background-color: #ffc266; /* Tom mais claro de laranja/amarelo */
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff8d00;
        }

        .admin-header h2 {
            color: #6a3200; 
            margin: 0;
            font-size: 20px;
        }
        
        /* Navegação interna do painel (adaptada do seu <nav> original) */
        .admin-nav {
            background-color: #ffb84d; /* Cor intermediária */
            padding: 15px 0;
        }
        
        .admin-nav ul {
            list-style: none;
            display: flex;
            justify-content: space-around; /* Distribui os itens */
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .admin-nav ul li {
            font-size: 16px;
        }

        .admin-nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .admin-nav ul li a:hover {
            background-color: #ff8d00;
            color: white;
        }

        /* Estilo para a área de conteúdo principal */
        .conteudo {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Estilo do botão Sair (vermelho para destaque) */
        .botao.sair {
            background-color: #c94c4c;
            padding: 8px 15px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }
        .botao.sair:hover {
            background-color: #a30000;
        }

    </style>
</head>
<body>
    
    <div class="admin-header">
        <h2 style="color: #6a3200; margin: 0;">Painel de Controle | Sousa Decor</h2>
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <span>Bem-vindo, **<?php echo $nome_vendedor; ?>**!</span>
            <a href="index.php?pg=logout" class="botao sair">Sair</a>
        </div>
    </div>

    <nav class="admin-nav">
        <ul>
            <li><a href="index.php?pg=dashboard">Dashboard</a></li>
            <li>|</li>
            <li><a href="index.php?pg=CRUD_produtos">Produtos (CRUD)</a></li>
            <li>|</li>
            <li><a href="index.php?pg=historico_vendas">Histórico de Vendas</a></li>
            <li>|</li>
            <li><a href="index.php?pg=dinheiro">Financeiro</a></li>
            <li>|</li>
            <li><a href="index.php?pg=contato_cliente">Contatos de Clientes</a></li>
            <li>|</li>
            <li><a href="index.php?pg=cadastro_vendedor">Cadastrar Vendedores</a></li>
        </ul>
    </nav>
    
    <div class="conteudo">