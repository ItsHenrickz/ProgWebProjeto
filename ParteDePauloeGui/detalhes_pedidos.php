<?php
/**
 * detalhes_pedido.php
 * Arquivo responsável por exibir ou processar os detalhes de um pedido específico.
 * NOTA: Este arquivo é incluído (include) no index.php, então ele
 * NÃO deve ter as tags <html>, <head> ou <body>.
 */

// 1. VERIFICAÇÃO DE DADOS: Obtém o ID do pedido da URL
// Ele espera que a URL tenha o formato: index.php?pg=detalhes_pedido&id=XXX
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Se o parâmetro 'id' do pedido não foi passado, exibe a mensagem de erro formatada.
    ?>
    <div class="main-content">
        <!-- Usa a classe 'erro' do seu CSS -->
        <p class="erro">ERRO: Nenhum pedido selecionado para visualização. 
            <a href="index.php" class="botao secondary" style="margin-left: 10px;">Voltar para o Início</a>
        </p>
    </div>
    <?php
    
} else {
    // Sanitiza e obtém o ID
    $pedido_id = (int)$_GET['id'];
    
    // =========================================================================
    // 2. BUSCA NO BANCO DE DADOS - ATUALIZE ESTA SEÇÃO!
    // =========================================================================
    
    // Exemplo de dados estáticos (para teste): 
    $detalhes = [
        'id' => $pedido_id,
        'cliente' => 'João da Silva',
        'telefone' => '(83) 98765-4321',
        'data' => '2025-11-28',
        'valor_total' => 450.75,
        'status' => 'Em Produção',
        'itens' => [
            ['nome' => 'Tecido Veludo Avelã (10m)', 'quantidade' => 1, 'preco_unitario' => 350.00],
            ['nome' => 'Manta Acrílica', 'quantidade' => 1, 'preco_unitario' => 100.75],
        ]
    ];
    
    // 3. EXIBIÇÃO DO HTML DENTRO DO MAIN-CONTENT
    ?>

    <div class="main-content">
        <h2>Detalhes do Pedido #<?php echo htmlspecialchars($detalhes['id']); ?></h2>
        
        <div class="info-geral" style="margin-bottom: 25px; padding: 15px; border: 1px solid #e0e0e0; border-radius: 6px;">
            <h3>Informações do Cliente e Status</h3>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($detalhes['cliente']); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($detalhes['telefone']); ?></p>
            <p><strong>Data do Pedido:</strong> <?php echo date('d/m/Y', strtotime($detalhes['data'])); ?></p>
            <p>
                <strong>Status:</strong> 
                <span style="font-weight: bold; color: #ff8d00; background-color: #ffd68a; padding: 4px 8px; border-radius: 4px;">
                    <?php echo htmlspecialchars($detalhes['status']); ?>
                </span>
            </p>
        </div>
        
        <hr style="border-color: #ffd68a;">

        <h3>Itens do Pedido</h3>
        
        <!-- Aplica a classe de tabela estilizada -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Item / Descrição</th>
                    <th>Qtd</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalhes['itens'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nome']); ?></td>
                    <td><?php echo $item['quantidade']; ?></td>
                    <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>TOTAL GERAL</strong></td>
                    <td class="total-valor">
                        <strong>R$ <?php echo number_format($detalhes['valor_total'], 2, ',', '.'); ?></strong>
                    </td>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 30px;">
            <!-- BOTÃO APONTANDO PARA index.php para voltar ao padrão (produtos_crud) -->
            <a href="index.php" class="botao secondary">Voltar para a Página Inicial</a>
            
            <!-- Botões de ação para gerenciamento do pedido -->
            <button class="botao primary" style="margin-left: 10px;">Marcar como Concluído</button>
            <button class="botao danger" style="margin-left: 10px;">Excluir Pedido</button>
        </div>

    </div>

    <?php
}
?>