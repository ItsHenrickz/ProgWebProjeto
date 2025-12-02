<?php
// O arquivo topo.php contém:
// 1. session_start()
// 2. A verificação de login.
// 3. O início do HTML e o menu de navegação do painel.
include_once 'topo.php';

// 1. Define a CHAVE da página que será carregada por padrão
$pagina_padrao_key = "produtos_crud";

// 2. Mapeamento seguro:
// A CHAVE (Key) CORRESPONDE AOS LINKS DA URL (?pg=...)
// O VALOR (Value) CORRESPONDE AO ARQUIVO REAL NO SERVIDOR
$paginas_admin = [
    'dashboard'         => 'dashboard.php',
    'produtos_crud'     => 'CRUD_produtos.php',      // Gerenciamento de Produtos
    'vendedores_cadastro' => 'cadastro_vendedor.php', // Cadastro de Vendedores
    'vendas_historico'  => 'historico_vendas.php',   // Histórico de Vendas
    'financeiro'        => 'dinheiro.php',           // Relatórios Financeiros
    'contatos'          => 'contato_cliente.php',    // Mensagens de Clientes
    'detalhes_pedido'   => 'detalhes_pedido.php'     // Detalhe de um pedido específico
];

// 3. Obtém o parâmetro 'pg' da URL. Se não existir, usa a chave padrão.
$pg = $_GET['pg'] ?? $pagina_padrao_key;

// 4. Lógica de Logout
if ($pg == 'logout') {
    session_destroy(); // Destrói todas as informações da sessão
    
    // REDIRECIONAMENTO MODIFICADO: 
    // Redireciona para a página inicial do site (um nível acima: ../index.php)
    header("Location: ../ParteDeRick/index.php"); 
    exit();
}

// 5. Lógica de Inclusão Segura

// Assume o arquivo padrão como fallback
$arquivo_incluir = $paginas_admin[$pagina_padrao_key];

if (array_key_exists($pg, $paginas_admin)) {
    // Se a chave for válida na lista branca, usa o arquivo mapeado
    $arquivo_incluir = $paginas_admin[$pg];
}


include $arquivo_incluir;
?>
</body>
</html>