<?php
// O topo.php e o config.inc.php já foram incluídos.

// Assume-se que a variável $conexao com o banco de dados está disponível.

// Define o filtro de status (padrão é 'nao_lido')
$filtro = $_GET['status'] ?? 'nao_lido';
$mensagem_status = "";

// --- 1. AÇÃO PARA MARCAR COMO LIDO ---
$acao_msg = $_GET['acao_msg'] ?? '';
$id_msg = $_GET['id'] ?? null;

if ($acao_msg == 'marcar_lido' && $id_msg) {
    $id_msg = mysqli_real_escape_string($conexao, $id_msg);

    // SQL para atualizar o status 'lido' para 1
    // IMPORTANTE: Use Prepared Statements na versão final!
    $sql_update = "UPDATE contatos SET lido = 1 WHERE id = '$id_msg'";
    
    if (mysqli_query($conexao, $sql_update)) {
        $mensagem_status = "<p class='sucesso'>Mensagem ID **$id_msg** marcada como lida.</p>";
    } else {
        $mensagem_status = "<p class='erro'>Erro ao atualizar status: " . mysqli_error($conexao) . "</p>";
    }
    // Redireciona para evitar reenvio do GET (PRG Pattern)
    header("Location: index.php?pg=contatos&status=$filtro"); 
    exit();
}

// --- 2. CONFIGURAÇÃO DA CONSULTA SQL ---
$sql = "SELECT id, nome, email, assunto, mensagem, data_envio, lido FROM contatos";

if ($filtro == 'nao_lido') {
    $sql .= " WHERE lido = 0";
} elseif ($filtro == 'lido') {
    $sql .= " WHERE lido = 1";
}

$sql .= " ORDER BY data_envio DESC"; // Mais recentes primeiro

$resultado = mysqli_query($conexao, $sql);

// Função auxiliar para criar links de filtro ativos
function criarLinkFiltroContatos($status, $label, $filtro_atual) {
    $ativo = ($status == $filtro_atual) ? 'style="background-color: #ff6600; color: white; border-color: #ff6600;"' : '';
    $href  = "index.php?pg=contatos&status=$status";
    
    echo "<a class='botao filtro-btn' href='$href' $ativo style='padding: 8px 15px;'>$label</a>";
}
?>

    <h1>Contatos de Clientes</h1>
    <?php echo $mensagem_status; ?>

    <div class="filtros-contatos" style="margin-bottom: 25px; display: flex; gap: 10px; align-items: center;">
        <span style="font-weight: bold;">Filtrar Mensagens:</span>
        <?php
            criarLinkFiltroContatos('nao_lido', 'Não Lidas', $filtro);
            criarLinkFiltroContatos('lido', 'Lidas', $filtro);
            criarLinkFiltroContatos('todos', 'Todas', $filtro);
        ?>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style='background-color: #ffd68a;'>
                <th style='padding: 12px; width: 50px;'>ID</th>
                <th style='width: 200px;'>Remetente</th>
                <th>Assunto e Prévia</th>
                <th style='width: 100px;'>Data</th>
                <th style='width: 150px;'>Status/Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($dados = mysqli_fetch_array($resultado)) {
                    // Estilo para destacar mensagens não lidas
                    $estilo_nao_lido = $dados['lido'] == 0 ? 'style="font-weight: bold; background-color: #fff8e1;"' : '';
                    $preview_msg = substr($dados['mensagem'], 0, 50) . '...';
                    $data_formatada = date('d/m/y', strtotime($dados['data_envio']));

                    echo "<tr $estilo_nao_lido>";
                    echo "<td style='padding: 10px;'>$dados[id]</td>";
                    echo "<td>$dados[nome]<br><small style='color: #666;'>$dados[email]</small></td>";
                    echo "<td>
                            <strong>$dados[assunto]</strong><br>
                            <span style='font-size: 0.9em; color: #555;'>$preview_msg</span>
                          </td>";
                    echo "<td>$data_formatada</td>";
                    echo "<td>";

                    if ($dados['lido'] == 0) {
                        echo "<span style='color: #ff8d00; font-weight: bold;'>NOVA</span><br>";
                        echo "<a href='index.php?pg=contatos&acao_msg=marcar_lido&id=$dados[id]&status=$filtro' class='botao' style='padding: 5px 10px; background-color: #007bff; margin-top: 5px;'>Marcar Lido</a>";
                    } else {
                        echo "<span style='color: #28a745;'>Lida</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>Nenhuma mensagem encontrada com o filtro atual.</td></tr>";
            }
            ?>
        </tbody>
    </table>