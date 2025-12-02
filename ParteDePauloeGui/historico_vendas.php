<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php, fornecendo $conexao.

// Array para definir as cores e os status permitidos
$status_cores = [
    'pendente'      => '#ffc107', // Amarelo
    'processando'   => '#17a2b8', // Azul-claro
    'enviado'       => '#6f42c1', // Roxo
    'concluido'     => '#28a745', // Verde
    'cancelado'     => '#dc3545'  // Vermelho
];

// Lista branca de status permitidos (incluindo 'todos')
$status_permitidos = array_keys($status_cores);
$status_permitidos[] = 'todos';

// 1. Define o filtro de status (padrão é 'todos')
$filtro = $_GET['status'] ?? 'todos';

// 2. Validação rigorosa do filtro (Lista Branca)
if (!in_array($filtro, $status_permitidos)) {
    $filtro = 'todos'; // Se o filtro for inválido, volta para 'todos'
}

// 3. Define o SQL base
$sql_base = "SELECT id, data, nome_do_cliente, preco, status FROM vendas";
$sql = $sql_base;

// --- 4. APLICA A CONDIÇÃO WHERE COM PREPARED STATEMENT ---
// Usaremos Prepared Statements para garantir a segurança, mesmo que o filtro seja uma lista branca.

$where_clause = "";
$bind_types = "";
$bind_params = [];

if ($filtro != 'todos') {
    $where_clause = " WHERE status = ?";
    $bind_types = "s"; // 's' para string
    $bind_params[] = $filtro;
}

// 5. Adiciona a ordenação
$sql .= $where_clause . " ORDER BY data DESC";

// --- EXECUÇÃO DA CONSULTA COM PREPARED STATEMENT (SE HOUVER FILTRO) ---

$stmt = mysqli_prepare($conexao, $sql);

if ($filtro != 'todos') {
    // Se há filtro, faz o bind dos parâmetros
    mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_params);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// --- Exibição ---
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h2>Histórico de Vendas</h2>

    <div style="margin-bottom: 20px;">
        <span style="font-weight: bold; margin-right: 10px;">Filtrar por Status:</span>
        <a href="index.php?pg=vendas_historico&status=todos" class="botao <?php echo $filtro == 'todos' ? 'ativo' : ''; ?>" style="background-color: #6c757d; margin-right: 10px;">Todos</a>
        
        <?php foreach ($status_cores as $status => $cor): ?>
            <a href="index.php?pg=vendas_historico&status=<?php echo $status; ?>" class="botao <?php echo $filtro == $status ? 'ativo' : ''; ?>" 
                style="background-color: <?php echo $cor; ?>; margin-right: 10px;">
                <?php echo ucfirst($status); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style='background-color: #ffd68a;'>
                <th style='padding: 10px; width: 60px;'>Pedido ID</th>
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
                    // htmlspecialchars para evitar XSS
                    echo "<td>" . htmlspecialchars($dados['nome_do_cliente']) . "</td>"; 
                    // Formatação de moeda brasileira
                    echo "<td>R$ " . number_format($dados['preco'], 2, ',', '.') . "</td>"; 
                    // Exibe o status com a cor de destaque
                    echo "<td style='color: white; background-color: $cor_status; font-weight: bold; text-align: center;'>" . ucfirst($dados['status']) . "</td>";
                    echo "<td>";
                    // Link para 'detalhes_pedido' usando a chave do roteador (main_admin.php)
                    echo "<a href='index.php?pg=detalhes_pedido&id=$dados[id]' class='botao' style='padding: 5px 10px; background-color: #007bff;'>Ver Detalhes</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='padding: 15px; text-align: center;'>Nenhuma venda encontrada com o filtro **" . strtoupper($filtro) . "**</td></tr>";
            }
            // Fecha o statement
            mysqli_stmt_close($stmt); 
            ?>
        </tbody>
    </table>
</div>

<style>
    /* Estilos para o filtro ativo */
    .botao.ativo {
        opacity: 0.8;
        border: 2px solid #333;
    }
</style>