<?php
// O arquivo topo.php contém:
// 1. session_start()
// 2. A verificação se $_SESSION['admin_logado'] é TRUE.
// 3. Se não estiver logado, ele redireciona para login.php.
// 4. O início do HTML e o menu de navegação do painel.
include_once 'topo.php';

// --- IMPLEMENTAÇÃO DA LISTA BRANCA DE SEGURANÇA ---
// 1. Define o nome da página que será carregada por padrão (Dashboard)
$pagina_padrao = "CRUD_produtos";

// 2. Mapeamento seguro:
// A chave é o valor do parâmetro '?pg=' na URL
// O valor é o nome do arquivo PHP que deve ser incluído (dentro da pasta parteAdmin/)
$paginas_admin = [
    'dashboard'           => 'dashboard.php',
    'CRUD_produtos'       => 'CRUD_produtos.php', // Gerenciamento de Produtos
    'cadastro_vendedor' => 'cadastro_vendedor.php', // Cadastro de Paulo e Gui
    'historico_vendas'    => 'historico_vendas.php', // Histórico de Vendas
    'dinheiro'          => 'dinheiro.php', // Relatórios Financeiros
    'contato_cliente'            => 'contato_cliente.php', // Mensagens de Clientes
    'detalhes_pedido'     => 'detalhes_pedido.php' // Detalhe de um pedido específico
];

// 3. Obtém o parâmetro 'pg' da URL. Se não existir, usa a página padrão.
// O operador ?? é o "null coalescing operator" (PHP 7+), que simplifica o isset().
$pg = $_GET['pg'] ?? $pagina_padrao;

// 4. Lógica de Logout
if ($pg == 'logout') {
    session_destroy(); // Destrói todas as informações da sessão
    header("Location: login.php");
    exit();
}

// 5. Inclusão Segura
// Verifica se a chave ($pg) existe no array $paginas_admin.
if (array_key_exists($pg, $paginas_admin)) {
    // Se for válido, inclui o arquivo mapeado (Ex: 'produtos_crud.php')
    $arquivo_incluir = $paginas_admin[$pg];
    include $arquivo_incluir;
} else {
    // Se o valor de 'pg' não estiver na lista branca, inclui a página padrão.
    // Isso previne a inclusão de arquivos maliciosos ou inexistentes.
    echo "<div class='conteudo'><p class='erro' style='text-align: center; color: #cc0000;'>Página não encontrada ou acesso negado. Exibindo Dashboard.</p></div>";
    include $paginas_admin[$pagina_padrao];
}

// O arquivo rodape.php fecha a tag <body> e <html>.
include_once '../ParteDeRick/rodape.html';
?>