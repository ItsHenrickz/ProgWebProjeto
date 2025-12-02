<?php
// dashboard.php (Dentro da sua pasta administrativa)
// Assume que a variável $conexao (do config.inc.php) está disponível

// Função auxiliar para obter valores do banco (se ela não estiver no seu config.inc.php)
if (!function_exists('get_valor_sql')) {
    function get_valor_sql($conexao, $sql, $coluna) {
        $resultado = mysqli_query($conexao, $sql);
        if ($resultado && $dados = mysqli_fetch_assoc($resultado)) {
            return floatval($dados[$coluna] ?? 0);
        }
        return 0.00;
    }
}

// Lógica para obter dados
$sql_prod = "SELECT COUNT(id) AS total_produtos FROM produtos";
$res_prod = mysqli_query($conexao, $sql_prod);
$dados_prod = mysqli_fetch_assoc($res_prod);

// 🛑 CORREÇÃO 1: Trocando 'contatos' por 'mensagens' (tabela que existe)
$sql_msg = "SELECT COUNT(id) AS total_nao_lidas FROM mensagens WHERE lido = 0";
$res_msg = mysqli_query($conexao, $sql_msg);
$dados_msg = mysqli_fetch_assoc($res_msg);

// 🛑 CORREÇÃO 2: Trocando 'pedidos' por 'vendas' (tabela que existe)
// E garantindo que a coluna 'status' da tabela 'vendas' está sendo verificada
$sql_pend = "SELECT COUNT(id) AS total_pendente FROM vendas WHERE status = 'pendente'";
$res_pend = mysqli_query($conexao, $sql_pend);
$dados_pend = mysqli_fetch_assoc($res_pend);
?>

<div class="admin-content">
    <h1>Bem-vindo ao Dashboard, <?php echo $_SESSION['admin_nome']; ?>!</h1>
    
    <p>Aqui você pode ver um resumo rápido das principais métricas do negócio.</p>
    
    <div style="display: flex; gap: 20px; margin-top: 30px;">
        
        <div style="background: #ffffff; border: 1px solid #ddd; padding: 20px; border-radius: 8px; flex: 1; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2>Total de Produtos</h2>
            <?php
            // Exibe o total
            echo "<p style='font-size: 2.5em; color: #ff8d00; font-weight: bold; margin: 0;'>$dados_prod[total_produtos]</p>";
            ?>
            <a href="index.php?pg=CRUD_produtos" style="display: block; margin-top: 10px; color: #ff8d00;">Gerenciar Produtos</a>
        </div>
        
        <div style="background: #ffffff; border: 1px solid #ddd; padding: 20px; border-radius: 8px; flex: 1; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2>Mensagens Novas</h2>
            <?php
            // Exibe o total
            $cor_msg = $dados_msg['total_nao_lidas'] > 0 ? '#c94c4c' : '#28a745';
            echo "<p style='font-size: 2.5em; color: $cor_msg; font-weight: bold; margin: 0;'>$dados_msg[total_nao_lidas]</p>";
            ?>
            <!-- CORREÇÃO: Usando a chave 'contatos' da lista branca em index.php -->
            <a href="index.php?pg=contatos" style="display: block; margin-top: 10px; color: #ff8d00;">Ver Mensagens</a>
        </div>
        
        <div style="background: #ffffff; border: 1px solid #ddd; padding: 20px; border-radius: 8px; flex: 1; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2>Pedidos Pendentes</h2>
            <?php
            // Exibe o total
            $cor_pend = $dados_pend['total_pendente'] > 0 ? '#ff8d00' : '#28a745';
            echo "<p style='font-size: 2.5em; color: $cor_pend; font-weight: bold; margin: 0;'>$dados_pend[total_pendente]</p>";
            ?>
            <a href="index.php?pg=historico_vendas&status=pendente" style="display: block; margin-top: 10px; color: #ff8d00;">Processar Pedidos</a>
        </div>
    </div>
    
    <p style="margin-top: 30px;">Use o menu superior para navegar pelas ferramentas de gerenciamento.</p>
</div>