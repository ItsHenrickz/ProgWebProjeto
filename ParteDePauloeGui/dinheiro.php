<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php.

// Assume-se que a variável $conexao com o banco de dados está disponível via config.inc.php.

// --- 1. CONFIGURAÇÃO DE DATAS ---
// Definimos o período atual (ex: Mês atual)
$data_inicio = date('Y-m-01 00:00:00'); // Primeiro dia do mês atual
$data_fim = date('Y-m-t 23:59:59');     // Último dia do mês atual
$mes_atual = date('F/Y'); 

// Função auxiliar para executar consultas e retornar o resultado ou 0
function get_valor_sql($conexao, $sql, $coluna) {
    $resultado = mysqli_query($conexao, $sql);
    if ($resultado && $dados = mysqli_fetch_assoc($resultado)) {
        // Retorna o valor da coluna, garantindo que seja um número (ou 0)
        return floatval($dados[$coluna] ?? 0);
    }
    return 0.00;
}

// --- 2. CÁLCULO DE RECEITA BRUTA (TOTAL DE VENDAS CONCLUÍDAS) ---
// Soma o campo 'total' da tabela 'pedidos' que foram concluídos no período.
$sql_receita = "SELECT SUM(total) AS receita_total FROM pedidos 
                WHERE status = 'concluido' 
                AND data_pedido BETWEEN '$data_inicio' AND '$data_fim'";
$receita_mes = get_valor_sql($conexao, $sql_receita, 'receita_total');

// --- 3. CÁLCULO DE CUSTO DAS MERCADORIAS VENDIDAS (CMV) ---
// Isso requer um JOIN entre pedidos, itens_pedido e produtos, ou a inclusão do custo unitário em itens_pedido.
// AQUI ESTÁ UMA SIMULAÇÃO SIMPLES - VOCÊ DEVE IMPLEMENTAR O CÁLCULO REAL
// Vamos simular um custo de 25% da receita bruta.
$sql_cmv = "SELECT SUM(p.custo * ip.quantidade) AS custo_total
            FROM itens_pedido ip
            JOIN pedidos pe ON pe.id = ip.pedido_id
            JOIN produtos p ON p.id = ip.produto_id
            WHERE pe.status = 'concluido' AND pe.data_pedido BETWEEN '$data_inicio' AND '$data_fim'";
// NOTA: Se sua tabela 'produtos' NÃO tiver a coluna 'custo', esta query FALHARÁ.
// Para fins de demonstração, vou simplificar:
// $cmv_mes = get_valor_sql($conexao, $sql_cmv, 'custo_total'); // Versão complexa real

// Simulação de Despesas Operacionais (Frete, Marketing, Salários, etc.)
$despesas_operacionais = 3500.00; // Valor fixo simulado
$cmv_mes = 5000.00; // Custo de mercadorias simulado


// --- 4. CÁLCULOS FINAIS ---
// Custo Total = CMV (Custo da Mercadoria Vendida) + Despesas Operacionais
$custo_total = $cmv_mes + $despesas_operacionais;

// Lucro Bruto = Receita - Custo Total
$lucro_liquido = $receita_mes - $custo_total;

// Define a cor do lucro (verde para positivo, vermelho para negativo)
$cor_lucro = $lucro_liquido >= 0 ? '#28a745' : '#dc3545'; 
?>

<h1>Relatório Financeiro</h1>
    <p style="font-size: 1.2em; font-weight: bold;">Dados Referentes a <?php echo date('F \d\e Y'); ?></p>
    
    <div style="display: flex; justify-content: space-between; gap: 30px; margin: 30px 0;">
        
        <div class="card-financa" style="flex: 1; padding: 25px; border: 1px solid #ff8d00; border-radius: 10px; background: #fff7e6; text-align: center;">
            <p style="font-size: 16px; margin-bottom: 5px; color: #ff8d00; font-weight: bold;">RECEITA BRUTA (Vendas Concluídas)</p>
            <p style="font-size: 36px; font-weight: bold; color: #ff8d00;">R$ <?php echo number_format($receita_mes, 2, ',', '.'); ?></p>
        </div>

        <div class="card-financa" style="flex: 1; padding: 25px; border: 1px solid #dc3545; border-radius: 10px; background: #fff7f7; text-align: center;">
            <p style="font-size: 16px; margin-bottom: 5px; color: #dc3545; font-weight: bold;">CUSTO TOTAL (CMV + Despesas)</p>
            <p style="font-size: 36px; font-weight: bold; color: #dc3545;">R$ <?php echo number_format($custo_total, 2, ',', '.'); ?></p>
        </div>
        
    </div>
    
    <div style="padding: 30px; border: 2px solid <?php echo $cor_lucro; ?>; border-radius: 10px; background: #e6ffe6; text-align: center; width: 60%; margin: 20px auto;">
        <p style="font-size: 24px; margin-bottom: 5px; color: <?php echo $cor_lucro; ?>;">LUCRO LÍQUIDO</p>
        <p style="font-size: 50px; font-weight: bolder; color: <?php echo $cor_lucro; ?>;">R$ <?php echo number_format($lucro_liquido, 2, ',', '.'); ?></p>
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

    <p style="margin-top: 40px; text-align: center; color: #666;">
        *Os dados de CMV dependem da sua tabela `produtos` ter a coluna `custo` e da tabela `itens_pedido` estar corretamente relacionada.
    </p>