<?php
// 1. Inicia a sessão se ainda não tiver sido iniciada.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Inclui o arquivo de configuração e conexão com o banco de dados.
// O arquivo config.inc.php deve estar um nível acima, na pasta 'ParteDeRick'.
require_once "../ParteDeRick/config.inc.php";

// 3. --- VERIFICAÇÃO DE LOGIN DE ADMIN (ESSENCIAL) ---

// Se o vendedor não estiver logado, ele é redirecionado para a tela de login.
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    
    // CORREÇÃO: Ajuste do caminho de redirecionamento.
    // Presume-se que o arquivo de login está dentro da pasta 'ParteDePauloeGui'.
    header("Location: ../ParteDePauloeGui/login.php"); 
    exit();
}

// 4. VERIFICAÇÃO DE PERMISSÃO (NOVO)
// Se você implementar níveis de acesso no login.php, use esta variável.
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'vendedor';
$nome_vendedor = $_SESSION['admin_nome'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin | Sousa Decor</title>
    <link rel="stylesheet" href="../ParteDeRick/style.css"> 
    
    <link rel="stylesheet" href="../ParteDePauloeGui/admin.css">

    <style>
        /* Estilos específicos para a área administrativa */
        
        /* ... (Restante dos seus estilos CSS) ... */

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
            
            <li><a href="index.php?pg=produtos_crud">Produtos (CRUD)</a></li>
            <li>|</li>
            <li><a href="index.php?pg=vendas_historico">Histórico de Vendas</a></li>
            <li>|</li>
            <li><a href="index.php?pg=financeiro">Financeiro</a></li>
            <li>|</li>
            <li><a href="index.php?pg=contatos">Contatos</a></li>
            
            <?php if ($nivel_acesso == 'admin'): ?>
                <li>|</li>
                <li><a href="index.php?pg=vendedores_cadastro">Cadastrar Vendedores</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="admin-content">