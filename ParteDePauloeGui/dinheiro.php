<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php.

// Assume-se que a variável $conexao com o banco de dados está disponível via config.inc.php.

// --- 1. CONFIGURAÇÃO DE DATAS ---
// Definimos o período atual (ex: Mês atual)
$data_inicio = date('Y-m-01 00:00:00'); // Primeiro dia do mês atual
$data_fim = date('Y-m-t 23:59:59'); // Último dia do mês atual
$mes_atual = date('F/Y'); 

// Função auxiliar para executar consultas e retornar o resultado ou 0
function get_valor_sql($conexao, $sql, $coluna) {
    // Inicializa a variável para garantir que sempre retorne um float
    $valor = 0.00; 
    
    $resultado = mysqli_query($conexao, $sql);
    if ($resultado && $dados = mysqli_fetch_assoc($resultado)) {
        // Retorna o valor da coluna, garantindo que seja um número (ou 0)
        $valor = floatval($dados[$coluna] ?? 0);
    }
    // Libera a memória do resultado da consulta
    if (isset($resultado)) {
        mysqli_free_result($resultado);
    }
    return $valor;
}

// --- 2. CÁLCULO DE RECEITA BRUTA (TOTAL DE VENDAS CONCLUÍDAS) ---
// Trocando 'pedidos' por 'vendas'. Trocando 'total' por 'preco'. Trocando 'data_pedido' por 'data'.
$sql_receita = "SELECT SUM(preco) AS receita_total FROM vendas 
                 WHERE status = 'concluido' 
                 AND data BETWEEN '$data_inicio' AND '$data_fim'";

$receita_bruta = get_valor_sql($conexao, $sql_receita, 'receita_total');
$receita_bruta = $receita_bruta ?? 0.00; // Garante que a variável não seja NULL

// --- 3. CÁLCULO DE CUSTO DA MERCADORIA VENDIDA (CMV) ---
// SIMULAÇÃO: 40% da Receita Bruta (Valor base para cálculo)
$cmv_mes = $receita_bruta * 0.40;
$cmv_mes = $cmv_mes ?? 0.00; 

// --- 4. DESPESAS OPERACIONAIS (Simuladas) ---
// Simulação de um valor fixo mensal
$despesas_operacionais = 500.00; 
$despesas_operacionais = $despesas_operacionais ?? 0.00; 

// --- 5. CÁLCULOS FINAIS ---
$custo_total = $cmv_mes + $despesas_operacionais;
$lucro_liquido = $receita_bruta - $custo_total;

// Cores para exibição
$cor_lucro = $lucro_liquido >= 0 ? '#28a745' : '#dc3545'; // Verde para positivo, vermelho para negativo


// =========================================================================================
// CÁLCULO DE VENDAS INDIVIDUAIS DO VENDEDOR LOGADO
// ESTA SEÇÃO FOI DESABILITADA (COMENTADA) DEVIDO AO ERRO: Unknown column 'id_vendedor'
// =========================================================================================

$id_vendedor_logado = $_SESSION['admin_id'] ?? null;
$vendas_vendedor = 0.00; // Vendas do vendedor logado, atualmente 0 até a coluna ser adicionada.
$nome_vendedor = $_SESSION['admin_nome'] ?? 'Vendedor';


/*
// --- INSTRUÇÕES PARA REATIVAR O RASTREAMENTO DO VENDEDOR ---
// 1. Você precisa rodar o seguinte comando SQL no seu banco de dados 'sousadecor' (ex: via phpMyAdmin)
//    para adicionar a coluna 'id_vendedor' na sua tabela 'vendas':
//
//    ALTER TABLE vendas ADD COLUMN id_vendedor INT NULL DEFAULT NULL AFTER status; // <--- Tabela CORRIGIDA
//
// 2. Você também precisará atualizar o código que salva um novo pedido para registrar
//    o $_SESSION['admin_id'] nessa nova coluna 'id_vendedor'.
//
// 3. Após adicionar a coluna, você pode DESCOMENTAR (remover o ' /* ' e ' * /') o bloco abaixo.

if ($id_vendedor_logado) {
    // Trocando 'pedidos' por 'vendas'. Trocando 'total' por 'preco'. Trocando 'data_pedido' por 'data'.
    $sql_vendas_vendedor = "SELECT SUM(preco) AS vendas_vendedor_total FROM vendas 
                             WHERE id_vendedor = '$id_vendedor_logado'
                             AND status = 'concluido' 
                             AND data BETWEEN '$data_inicio' AND '$data_fim'";
    
    $vendas_vendedor = get_valor_sql($conexao, $sql_vendas_vendedor, 'vendas_vendedor_total');
}
*/


?>

<h1 style="text-align: center; margin-top: 20px;">Relatório Financeiro de Vendas</h1>

<p style="text-align: center; font-size: 1.1em; color: #555;">Dados Referentes ao Mês de **<?php echo $mes_atual; ?>**</p>

<div style="max-width: 800px; margin: 40px auto; padding: 20px; background-color: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    
    <h2 style="text-align: center; color: #ff8d00;">Resumo Geral (Loja)</h2>
    
    <div style="display: flex; justify-content: space-around; text-align: center; margin-bottom: 20px; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <div style="flex: 1;">
            <p style="font-size: 1.2em; color: #555; margin-bottom: 5px;">Receita Bruta Total</p>
            <p style="font-size: 30px; font-weight: bold; color: #007bff;">R$ <?php echo number_format($receita_bruta, 2, ',', '.'); ?></p>
        </div>
        <div style="flex: 1;">
            <p style="font-size: 1.2em; color: #555; margin-bottom: 5px;">Lucro Líquido</p>
            <p style="font-size: 30px; font-weight: bold; color: <?php echo $cor_lucro; ?>;">R$ <?php echo number_format($lucro_liquido, 2, ',', '.'); ?></p>
        </div>
    </div>

    <h2 style="text-align: center; color: #6a3200; margin-top: 30px;">Suas Vendas (<?php echo $nome_vendedor; ?>)</h2>
    <div style="text-align: center; padding: 15px; background-color: #ffe0b2; border-radius: 8px;">
        <p style="font-size: 1.2em; color: #555; margin-bottom: 5px;">Total de Vendas Concluídas no Mês</p>
        <?php if ($vendas_vendedor > 0) : ?>
            <p style="font-size: 35px; font-weight: bolder; color: #ff8d00;">R$ <?php echo number_format($vendas_vendedor, 2, ',', '.'); ?></p>
        <?php else : ?>
             <p style="font-size: 35px; font-weight: bolder; color: #999;">R$ 0,00</p>
             <p style="font-size: 0.9em; color: #777; margin-top: 10px;">
                 Para rastrear suas vendas individualmente, a coluna **'id_vendedor'** precisa ser adicionada na tabela **'vendas'** no banco de dados.
             </p>
        <?php endif; ?>
    </div>
    
    <hr style="margin: 40px 0; border-top: 1px solid #ccc;">

    <h2>Detalhamento de Custos</h2>
    <table border="1" style="width: 50%; border-collapse: collapse; margin: 20px auto; text-align: left;">
        <thead>
            <tr style='background-color: #ffd68a;'>
                <th style='padding: 10px;'>Item</th>
                <th style='padding: 10px;'>Valor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style='padding: 8px;'>Custo da Mercadoria Vendida (CMV)</td>
                <td>R$ <?php echo number_format($cmv_mes, 2, ',', '.'); ?></td>
            </tr>
            <tr>
                <td style='padding: 8px;'>Despesas Operacionais (Simulado)</td>
                <td>R$ <?php echo number_format($despesas_operacionais, 2, ',', '.'); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr style='font-weight: bold;'>
                <td style='padding: 10px;'>Total de Custos</td>
                <td>R$ <?php echo number_format($custo_total, 2, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>
    
    <h3 style="text-align: center; margin-top: 40px; color: #333;">Lucro Líquido Final</h3>
    <p style="text-align: center; font-size: 50px; font-weight: bolder; color: <?php echo $cor_lucro; ?>;">R$ <?php echo number_format($lucro_liquido, 2, ',', '.'); ?></p>
</div>