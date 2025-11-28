<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php.

// Assume-se que a variável $conexao com o banco de dados está disponível via config.inc.php.

// 1. Define o filtro de status (padrão é 'todos')
$filtro = $_GET['status'] ?? 'todos';
$filtro = mysqli_real_escape_string($conexao, $filtro); // Segurança: Limpa a variável

// 2. Define o SQL base
$sql = "SELECT id, data, nome_do_cliente, preco, status FROM vendas";

// 3. Aplica a condição WHERE com base no filtro
if ($filtro != 'todos') {
    // Certifique-se que o status passado na URL é válido para a coluna ENUM
    $status_permitidos = ['pendente', 'processando', 'enviado', 'concluido', 'cancelado'];
    if (in_array($filtro, $status_permitidos)) {
        $sql .= " WHERE status = '$filtro'";
    } else {
        // Se o filtro for inválido, volta para 'todos'
        $filtro = 'todos';
    }
}

// 4. Ordena os pedidos mais recentes primeiro
$sql .= " ORDER BY data DESC";

$resultado = mysqli_query($conexao, $sql);

// Array para definir as cores de destaque na tabela (mantendo a paleta)
$status_cores = [
    'pendente' => '#ff8d00',      // Laranja principal
    'processando' => '#ffb84d',   // Laranja mais claro
    'enviado' => '#17a2b8',       // Azul (Informação)
    'concluido' => '#28a745',     // Verde (Sucesso)
    'cancelado' => '#dc3545'      // Vermelho (Erro)
];

// Função auxiliar para criar links de filtro ativos
function criarLinkFiltro($status, $label, $filtro_atual) {
    $ativo = ($status == $filtro_atual) ? 'style="background-color: #ff6600; color: white; border-color: #ff6600;"' : '';
    $href  = "index.php?pg=historico_vendas&status=$status";
    
    echo "<a class='botao filtro-btn' href='$href' $ativo>$label</a>";
}
?>

<h1>Histórico de Vendas</h1>

    <div class="filtros-vendas" style="margin-bottom: 25px; display: flex; gap: 10px; align-items: center;">
        <span style="font-weight: bold;">Filtrar por Status:</span>
        <?php
            // Exibe os botões de filtro
            criarLinkFiltro('todos', 'Todos', $filtro);
            criarLinkFiltro('pendente', 'Pendente', $filtro);
            criarLinkFiltro('processando', 'Processando', $filtro);
            criarLinkFiltro('enviado', 'Enviado', $filtro);
            criarLinkFiltro('concluido', 'Concluído', $filtro);
            criarLinkFiltro('cancelado', 'Cancelado', $filtro);
        ?>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; text-align: center; margin-top: 20px;">
        <thead>
            <tr style='background-color: #ffd68a;'>
                <th style='padding: 12px; width: 80px;'>ID Pedido</th>
                <th style='width: 120px;'>Data</th>
                <th>Cliente</th>
                <th style='width: 150px;'>Total</th>
                <th style='width: 150px;'>Status</th>
                <th style='width: 120px;'>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($dados = mysqli_fetch_array($resultado)) {
                    $cor_status = $status_cores[$dados['status']] ?? 'black';
                    $data_formatada = date('d/m/Y', strtotime($dados['data']));

                    echo "<tr>";
                    echo "<td style='padding: 10px;'>$dados[id]</td>";
                    echo "<td>$data_formatada</td>";
                    echo "<td>$dados[nome_do_cliente]</td>";
                    echo "<td>R$ " . number_format($dados['preco'], 2, ',', '.') . "</td>";
                    // Exibe o status com a cor de destaque
                    echo "<td style='color: white; background-color: $cor_status; font-weight: bold;'>$dados[status]</td>";
                    echo "<td>
                            <a href='index.php?pg=detalhes_pedido&id=$dados[id]'>Ver Detalhes</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhuma venda encontrada com o filtro **" . strtoupper($filtro) . "**</td></tr>";
            }
            ?>
        </tbody>
    </table>