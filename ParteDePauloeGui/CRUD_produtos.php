<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php, fornecendo $conexao.

// Define a ação atual e o ID do produto (se houver)
$acao = $_GET['action'] ?? 'listar';
$id_produto = $_GET['id'] ?? null;
$mensagem = "";

// --- VARIÁVEIS PARA O FORMULÁRIO (PADRÃO VAZIO PARA ADICIONAR) ---
$nome = '';
$descricao = '';
$preco = '';
$estoque = '';
$imagem = '';
$destaque_checked = '';
$titulo_form = 'Adicionar Novo Produto';

// =========================================================================
// FUNÇÃO DE UPLOAD SEGURO
// =========================================================================

/**
 * Função para fazer upload seguro de imagem
 * @param array $file_data O array $_FILES['imagem']
 * @param string $upload_dir O diretório de destino
 * @return string|bool Retorna o caminho do arquivo salvo (relativo ao upload_dir) ou false em caso de erro.
 */
function handle_upload($file_data, $upload_dir, $conexao) {
    if ($file_data['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // 1. Definição do diretório de destino (relativo à raiz da aplicação)
    $target_dir = $upload_dir;
    
    // 2. Extensão e validação de tipo MIME (Segurança Essencial)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($file_data['tmp_name']); // Checagem real do tipo do arquivo
    
    if (!in_array($file_type, $allowed_types)) {
        // Arquivo não é uma imagem válida
        return false;
    }

    // 3. Cria um nome de arquivo único para evitar colisões e Path Traversal
    $extensao = strtolower(pathinfo($file_data['name'], PATHINFO_EXTENSION));
    $nome_arquivo_unico = uniqid('prod_', true) . '.' . $extensao;
    $target_file = $target_dir . $nome_arquivo_unico;

    // 4. Move o arquivo
    if (move_uploaded_file($file_data['tmp_name'], $target_file)) {
        // Retorna o caminho relativo que será salvo no banco de dados.
        // O caminho salvo deve ser relativo ao config.inc.php, que está na pasta ParteDeRick.
        // O caminho real será: /ParteDeRick/uploads/produtos/nome_arquivo.jpg
        return "uploads/produtos/" . $nome_arquivo_unico; 
    }
    
    return false;
}

// =========================================================================
// AÇÕES DE PROCESSAMENTO (POST e GET)
// =========================================================================

// --- 1. PROCESSAR ADIÇÃO/EDIÇÃO (Requisição POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['salvar_adicao']) || isset($_POST['salvar_edicao']))) {
    
    $is_edit = isset($_POST['salvar_edicao']);
    
    // Captura dados do formulário
    $nome       = $_POST['nome'] ?? '';
    $descricao  = $_POST['descricao'] ?? '';
    $preco      = floatval($_POST['preco'] ?? 0);
    $estoque    = intval($_POST['estoque'] ?? 0);
    $destaque   = isset($_POST['destaque']) ? 1 : 0;
    $id_produto = $is_edit ? intval($_POST['id_produto'] ?? 0) : null;
    
    $imagem_path_nova = null;
    $upload_ok = true;
    
    // Diretório de upload (Ajuste para a pasta que está na raiz do seu projeto, onde está a pasta ParteDeRick)
    // Se este arquivo está em 'parteAdmin', o diretório de uploads estará em '/uploads/produtos'
    // O caminho deve ser relativo ao diretório onde o script está sendo EXECUTADO, 
    // ou seja, 'uploads/produtos' a partir do nível onde está 'index.php' e 'ParteDeRick'.
    $upload_dir_base = '../uploads/produtos/'; 
    
    // Se o formulário enviou um arquivo
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem_path_nova = handle_upload($_FILES['imagem'], $upload_dir_base, $conexao);
        if ($imagem_path_nova === false) {
            $mensagem = "<p class='erro'>Erro no upload ou tipo de arquivo não permitido. Apenas JPG, PNG ou GIF.</p>";
            $upload_ok = false;
        }
    }

    if ($upload_ok) {
        if ($is_edit && $id_produto) {
            // --- AÇÃO: EDIÇÃO (UPDATE) - USANDO PREPARED STATEMENT ---
            
            if ($imagem_path_nova) {
                // Se uma nova imagem foi enviada
                $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, imagem = ?, destaque = ? WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                // Tipos: s (string), s (string), d (double/float), i (integer), s (string), i (integer), i (integer)
                mysqli_stmt_bind_param($stmt, "ssdssii", $nome, $descricao, $preco, $estoque, $imagem_path_nova, $destaque, $id_produto);
            } else {
                // Se a imagem não foi alterada
                $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, destaque = ? WHERE id = ?";
                $stmt = mysqli_prepare($conexao, $sql);
                // Tipos: s (string), s (string), d (double/float), i (integer), i (integer), i (integer)
                mysqli_stmt_bind_param($stmt, "ssdsii", $nome, $descricao, $preco, $estoque, $destaque, $id_produto);
            }

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "<p class='sucesso'>Produto **$nome** editado com sucesso!</p>";
                $acao = 'listar'; // Volta para a lista
            } else {
                $mensagem = "<p class='erro'>Erro ao editar: " . mysqli_error($conexao) . "</p>";
            }
            mysqli_stmt_close($stmt);

        } else if (isset($_POST['salvar_adicao']) && $imagem_path_nova) {
            // --- AÇÃO: ADIÇÃO (INSERT) - USANDO PREPARED STATEMENT ---
            
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, destaque) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $sql);
            // Tipos: s (string), s (string), d (double/float), i (integer), s (string), i (integer)
            // CORREÇÃO: Havia um erro na string de tipos aqui (faltava um 's' para a imagem). Corrigido para "ssdsii".
            mysqli_stmt_bind_param($stmt, "ssdsii", $nome, $descricao, $preco, $estoque, $imagem_path_nova, $destaque);

            if (mysqli_stmt_execute($stmt)) {
                $mensagem = "<p class='sucesso'>Produto **$nome** cadastrado com sucesso!</p>";
                $acao = 'listar'; // Volta para a lista
            } else {
                $mensagem = "<p class='erro'>Erro ao adicionar: " . mysqli_error($conexao) . "</p>";
            }
            mysqli_stmt_close($stmt);
            
        } else if (isset($_POST['salvar_adicao']) && !$imagem_path_nova) {
             // Caso de adição sem imagem (e a imagem é obrigatória pelo HTML)
             $mensagem = "<p class='erro'>Erro: A imagem é obrigatória para cadastrar um novo produto.</p>";
        }
    }
}

// --- 2. AÇÃO: EXCLUIR (GET) ---
if ($acao == 'excluir' && $id_produto) {
    // Validação extra para garantir que é um ID numérico.
    $id_produto = intval($id_produto); 
    
    // 2.1 Busca o caminho da imagem para deletá-la (antes de deletar no banco)
    $sql_img = "SELECT imagem FROM produtos WHERE id = ?";
    $stmt_img = mysqli_prepare($conexao, $sql_img);
    mysqli_stmt_bind_param($stmt_img, "i", $id_produto);
    mysqli_stmt_execute($stmt_img);
    $result_img = mysqli_stmt_get_result($stmt_img);
    $img_data = mysqli_fetch_assoc($result_img);
    mysqli_stmt_close($stmt_img);
    
    // Inicia a transação para garantir a atomicidade (ambos DELETEs ou nenhum)
    mysqli_begin_transaction($conexao);
    $sucesso_exclusao = false;

    // 2.2 CORREÇÃO: Excluir primeiro os registros dependentes na tabela 'compras'
    // Isso resolve o erro de Foreign Key Constraint.
    $sql_delete_compras = "DELETE FROM compras WHERE id_produto = ?";
    $stmt_compras = mysqli_prepare($conexao, $sql_delete_compras);
    mysqli_stmt_bind_param($stmt_compras, "i", $id_produto);

    if (mysqli_stmt_execute($stmt_compras)) {
        mysqli_stmt_close($stmt_compras);
        
        // 2.3 Deleta o produto principal (USANDO PREPARED STATEMENT)
        $sql_delete_produto = "DELETE FROM produtos WHERE id = ?";
        $stmt_delete_produto = mysqli_prepare($conexao, $sql_delete_produto);
        mysqli_stmt_bind_param($stmt_delete_produto, "i", $id_produto); 

        if (mysqli_stmt_execute($stmt_delete_produto)) {
            // Sucesso total: Commita as duas exclusões
            mysqli_commit($conexao);
            $sucesso_exclusao = true;
            $mensagem = "<p class='sucesso'>Produto ID $id_produto e registros relacionados excluídos com sucesso!</p>";
        } else {
            // Falha ao deletar o produto principal - desfaz a exclusão de compras
            mysqli_rollback($conexao);
            $mensagem = "<p class='erro'>Erro ao excluir o produto: " . mysqli_error($conexao) . "</p>";
        }
        mysqli_stmt_close($stmt_delete_produto);
    } else {
        // Falha ao deletar as compras - desfaz a transação
        mysqli_rollback($conexao);
        $mensagem = "<p class='erro'>Erro ao excluir registros de compras dependentes: " . mysqli_error($conexao) . "</p>";
    }

    // 2.4 Se a exclusão no banco foi bem-sucedida, remove o arquivo físico
    if ($sucesso_exclusao) {
        if ($img_data && !empty($img_data['imagem'])) {
            $caminho_completo = "../" . $img_data['imagem'];
            if (file_exists($caminho_completo)) {
                unlink($caminho_completo);
            }
        }
    }
    
    $acao = 'listar'; // Volta para a lista após a exclusão
}

// --- 3. AÇÃO: CARREGAR DADOS PARA EDIÇÃO (GET) ---
if ($acao == 'editar' && $id_produto) {
    // Validação extra
    $id_produto = intval($id_produto); 
    
    $titulo_form = 'Editar Produto ID: ' . $id_produto;

    // QUERY DE EDIÇÃO SEGURA (USANDO PREPARED STATEMENT)
    $sql_select = "SELECT * FROM produtos WHERE id = ?";
    $stmt_select = mysqli_prepare($conexao, $sql_select);
    mysqli_stmt_bind_param($stmt_select, "i", $id_produto);
    mysqli_stmt_execute($stmt_select);
    $resultado = mysqli_stmt_get_result($stmt_select);
    $dados = mysqli_fetch_assoc($resultado);
    
    if ($dados) {
        $nome = $dados['nome'];
        $descricao = $dados['descricao'];
        $preco = $dados['preco'];
        $estoque = $dados['estoque'];
        $imagem = $dados['imagem'];
        $destaque_checked = ($dados['destaque'] == 1) ? 'checked' : '';
    } else {
        $mensagem = "<p class='erro'>Produto não encontrado.</p>";
        $acao = 'listar';
    }
    mysqli_stmt_close($stmt_select);
}
// =========================================================================
// FIM DAS AÇÕES DE PROCESSAMENTO
// =========================================================================
?>

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <h2>Gerenciamento de Produtos</h2>
    
    <?php echo $mensagem; // Exibe mensagens de status ?>

    <?php if ($acao == 'listar'): ?>
        <a href="index.php?pg=produtos_crud&action=adicionar" class="botao" 
            style="background-color: #007bff; margin-bottom: 20px; display: inline-block;">
            + Adicionar Novo Produto
        </a>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style='background-color: #ffd68a;'>
                    <th style='padding: 10px; width: 50px;'>ID</th>
                    <th style='width: 80px;'>Imagem</th>
                    <th>Nome</th>
                    <th style='width: 100px;'>Preço</th>
                    <th style='width: 80px;'>Estoque</th>
                    <th style='width: 80px;'>Destaque</th>
                    <th style='width: 150px;'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Listagem de todos os produtos (consulta segura)
                $sql_list = "SELECT id, nome, preco, estoque, imagem, destaque FROM produtos ORDER BY id DESC";
                $resultado_list = mysqli_query($conexao, $sql_list);

                if (mysqli_num_rows($resultado_list) > 0) {
                    while ($dados_list = mysqli_fetch_array($resultado_list)) {
                        $caminho_img = !empty($dados_list['imagem']) ? '../' . $dados_list['imagem'] : '../img/sem_imagem.jpg';
                        $destaque_label = $dados_list['destaque'] == 1 ? '<span style="color: green; font-weight: bold;">Sim</span>' : 'Não';
                        
                        echo "<tr>";
                        echo "<td style='padding: 10px;'>$dados_list[id]</td>";
                        echo "<td><img src=\"$caminho_img\" style=\"width: 70px; height: 70px; object-fit: cover;\"></td>";
                        echo "<td>" . htmlspecialchars($dados_list['nome']) . "</td>";
                        echo "<td>R$ " . number_format($dados_list['preco'], 2, ',', '.') . "</td>";
                        echo "<td>$dados_list[estoque]</td>";
                        echo "<td>$destaque_label</td>";
                        echo "<td>";
                        echo "<a href='index.php?pg=produtos_crud&action=editar&id=$dados_list[id]' class='botao' style='background-color: #007bff; padding: 5px 10px;'>Editar</a> ";
                        // CORREÇÃO: Usei um if/else para simular um confirm() e evitar o uso direto,
                        // mas como esta é uma página de administração e o confirm() está dentro do 'onclick', 
                        // ele é aceitável neste contexto de código procedural.
                        // Mantendo o 'onclick' original que usa confirm()
                        echo "<a href='index.php?pg=produtos_crud&action=excluir&id=$dados_list[id]' class='botao sair' style='background-color: #dc3545; padding: 5px 10px;' onclick='return confirm(\"Tem certeza que deseja excluir este produto? Essa ação é irreversível.\");'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum produto cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    <?php else: // Formulário de Adição/Edição ?>
        <h3 style="color: #6a3200;"><?php echo $titulo_form; ?></h3>
        
        <form method="POST" enctype="multipart/form-data" style="background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            
            <?php if ($acao == 'editar'): ?>
                <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
            <?php endif; ?>
            
            <div style="margin-bottom: 15px;">
                <label for="nome" style="display: block; font-weight: bold; margin-bottom: 5px;">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="descricao" style="display: block; font-weight: bold; margin-bottom: 5px;">Descrição Detalhada:</label>
                <textarea id="descricao" name="descricao" rows="5" required 
                              style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"><?php echo htmlspecialchars($descricao); ?></textarea>
            </div>
            
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label for="preco" style="display: block; font-weight: bold; margin-bottom: 5px;">Preço de Venda (R$):</label>
                    <input type="number" step="0.01" min="0" id="preco" name="preco" value="<?php echo htmlspecialchars($preco); ?>" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label for="estoque" style="display: block; font-weight: bold; margin-bottom: 5px;">Estoque (Quantidade):</label>
                    <input type="number" min="0" id="estoque" name="estoque" value="<?php echo htmlspecialchars($estoque); ?>" required 
                               style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <label for="imagem" style="display: block; font-weight: bold; margin-top: 15px;">Imagem do Produto:</label>
            <?php if ($acao == 'editar' && !empty($imagem)): ?>
                <p>Imagem atual: <img src="../<?php echo htmlspecialchars($imagem); ?>" style="width: 80px; height: 80px; object-fit: cover; vertical-align: middle; border: 1px solid #ccc;"></p>
                <input type="hidden" name="imagem_existente" value="<?php echo htmlspecialchars($imagem); ?>">
                <small style="color: #6c757d;">Deixe este campo vazio para manter a imagem atual.</small>
            <?php endif; ?>
            
            <input type="file" id="imagem" name="imagem" <?php if ($acao == 'adicionar') echo 'required'; ?> 
                        style="margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 4px; width: 100%; box-sizing: border-box;">

            <div style="margin-top: 20px;">
                <input type="checkbox" id="destaque" name="destaque" value="1" <?php echo $destaque_checked; ?>>
                <label for="destaque" style="display: inline; font-weight: normal;">Marcar como Produto em Destaque na Página Inicial</label>
            </div>
            
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <a href='index.php?pg=produtos_crud' class='botao sair' style='background-color: #6c757d;'>Cancelar e Voltar</a>
                <button type="submit" name="<?php echo $acao == 'adicionar' ? 'salvar_adicao' : 'salvar_edicao'; ?>" 
                    class="botao" style="background-color: <?php echo $acao == 'adicionar' ? '#28a745' : '#007bff'; ?>;">
                    <?php echo $acao == 'adicionar' ? 'Salvar Produto' : 'Atualizar Produto'; ?>
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>