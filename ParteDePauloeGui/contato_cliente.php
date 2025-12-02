<?php
// O topo.php e o config.inc.php já foram incluídos (por meio do index.php).
// Assume-se que a variável $conexao com o banco de dados está disponível.

// Define o filtro de status (padrão é 'nao_lido')
$filtro = $_GET['status'] ?? 'nao_lido';
$mensagem_status = "";

// --- 1. AÇÃO PARA MARCAR COMO LIDO ---
$acao_msg = $_GET['acao_msg'] ?? '';
$id_msg = $_GET['id'] ?? null;

if ($acao_msg == 'marcar_lido' && $id_msg) { 
    // Garante que o ID é um inteiro
    $id_msg = intval($id_msg);

    // SQL para atualizar o status 'lido' para 1 (USANDO PREPARED STATEMENT)
    $sql_update = "UPDATE mensagens SET lido = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql_update);
    
    // Vincula 'i' (integer) para o ID
    mysqli_stmt_bind_param($stmt, "i", $id_msg);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensagem_status = "<p class='sucesso'>Mensagem ID **$id_msg** marcada como lida.</p>";
    } else {
        $mensagem_status = "<p class='erro'>Erro ao atualizar status: " . mysqli_error($conexao) . "</p>";
    }
    
    mysqli_stmt_close($stmt);

    // Redireciona para evitar reenvio do GET (PRG Pattern), MANTENDO O FILTRO ATUAL
    header("Location: index.php?pg=contatos&status=$filtro"); // Ajustei para 'pg=contatos' (chave no index.php)
    exit();
}

// --- 2. CONSTRUÇÃO DA CONSULTA DE LISTAGEM ---
$sql_list = "SELECT id, nome, assunto, mensagem, lido, data FROM mensagens";
$where = "";

if ($filtro == 'lido') {
    $where = " WHERE lido = 1";
} elseif ($filtro == 'nao_lido') {
    $where = " WHERE lido = 0";
}

$sql_list .= $where . " ORDER BY data DESC";

$resultado = mysqli_query($conexao, $sql_list);

// Configuração das cores do menu de filtro
// Uso de classes CSS existentes (primary, secondary, etc.) é mais recomendado que inline styles fortes
$cores = [
    'nao_lido' => 'botao primary', // Laranja
    'lido'     => 'botao success', // Verde (se houver no seu CSS, senão usaremos inline)
    'todos'    => 'botao secondary' // Amarelo suave
];

$cores['nao_lido'] = ($filtro == 'nao_lido' ? 'botao primary' : 'botao secondary');
$cores['lido']     = ($filtro == 'lido' ? 'botao primary' : 'botao secondary');
$cores['todos']    = ($filtro == 'todos' ? 'botao primary' : 'botao secondary');

?>

<div class="main-content" style="max-width: 1100px;">
    <h1>Mensagens de Clientes</h1>

    <?php echo $mensagem_status; ?>
    
 
    <?php if ($filtro == 'nao_lido'): ?>
        <p style="padding: 10px; background-color: #f0f8ff; border: 1px solid #cce5ff; border-radius: 6px; color: #004085;">
            **Atenção:** Ao marcar uma mensagem como lida, ela desaparecerá desta lista de "Não Lidas".
        </p>
    <?php endif; ?>

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <a href="index.php?pg=contatos&status=nao_lido" 
            class="<?php echo $cores['nao_lido']; ?>">
            Não Lidas
        </a>
        <a href="index.php?pg=contatos&status=lido" 
            class="<?php echo $cores['lido']; ?>" style="<?php echo ($filtro == 'lido' ? '' : 'background-color: #ccc; color: #333;'); ?>">
            Lidas
        </a>
        <a href="index.php?pg=contatos&status=todos" 
            class="<?php echo $cores['todos']; ?>" style="<?php echo ($filtro == 'todos' ? '' : 'background-color: #ccc; color: #333;'); ?>">
            Todas
        </a>
    </div>


    <table class="styled-table">
        <thead>
            <tr style='background-color: #ffd68a;'>
                <th style='width: 50px;'>ID</th>
                <th style='width: 200px;'>Remetente</th>
                <th>Mensagem</th>
                <th style='width: 100px;'>Data</th>
                <th style='width: 150px;'>Status & Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($dados = mysqli_fetch_array($resultado)) {

                    $estilo_nao_lido = $dados['lido'] == 0 ? "style='background-color: #fff8e1;'" : "";
                    $preview_msg = substr($dados['mensagem'], 0, 50) . '...';
                    $data_formatada = date('d/m/y', strtotime($dados['data']));

                    echo "<tr $estilo_nao_lido>";
                    echo "<td>$dados[id]</td>";
                    echo "<td>$dados[nome]</td>"; 
                    echo "<td>
                            <strong>$dados[assunto]</strong><br>
                            <span style='font-size: 0.9em; color: #555;'>$preview_msg</span>
                          </td>";
                    echo "<td>$data_formatada</td>";
                    echo "<td>";

                    if ($dados['lido'] == 0) {
                        echo "<span style='color: #ff8d00; font-weight: bold;'>NOVA</span><br>";
                        // Link para a ação
                        echo "<a href='index.php?pg=contatos&acao_msg=marcar_lido&id=$dados[id]&status=$filtro' class='botao primary' style='padding: 5px 10px; margin-top: 5px; font-size: 13px;'>Marcar Lido</a>";
                    } else {
                        echo "<span style='color: #28a745;'>Lida</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>Nenhuma mensagem encontrada com o filtro **" . strtoupper(str_replace('_', ' ', $filtro)) . "**</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>