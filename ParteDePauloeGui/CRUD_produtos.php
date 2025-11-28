<?php
// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// O arquivo config.inc.php já foi incluído no topo.php, fornecendo $conexao.
require_once "../ParteDeRick/config.inc.php";

// categorias que têm tabelas próprias
$categorias_validas = [
    'almofadas',
    'portaretrato',
    'arranjos',
    'ceramicas',
    'calendarios'
];

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
$categoria = '';
$destaque_checked = '';
$titulo_form = 'Adicionar Novo Produto';

// =========================================================================
// AÇÕES DE PROCESSAMENTO (POST e GET)
// =========================================================================

// --- 1. PROCESSAR ADIÇÃO/EDIÇÃO (Requisição POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['salvar_adicao']) || isset($_POST['salvar_edicao']))) {
    
    // Captura e sanitiza dados do formulário
    $nome        = mysqli_real_escape_string($conexao, $_POST['nome'] ?? '');
    $descricao   = mysqli_real_escape_string($conexao, $_POST['descricao'] ?? '');
    $preco       = floatval($_POST['preco'] ?? 0);
    $estoque     = intval($_POST['estoque'] ?? 0);
    $destaque    = isset($_POST['destaque']) ? 1 : 0;
    $id_edicao   = $_POST['id_edicao'] ?? null;

    // *** NOVO: pegar categoria do formulário (apenas na adição) ***
    $categoria = $_POST['categoria'] ?? '';
    $categoria = mysqli_real_escape_string($conexao, $categoria);

    // Lógica para Upload de Imagem
    $imagem_nova = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $diretorio_uploads = "../uploads/produtos/"; // Crie essa pasta na raiz!
        if (!is_dir($diretorio_uploads)) mkdir($diretorio_uploads, 0777, true);

        $nome_arquivo = time() . '_' . basename($_FILES['imagem']['name']);
        $caminho_destino = $diretorio_uploads . $nome_arquivo;
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino)) {
            $imagem_nova = "uploads/produtos/" . $nome_arquivo; // Caminho para salvar no banco
        }
    }

    // ======================================================
    // ADIÇÃO DE NOVO PRODUTO
    // ======================================================
    if (isset($_POST['salvar_adicao'])) {

        // valida categoria (tem que ser uma das 5)
        if (empty($categoria) || !in_array($categoria, $categorias_validas)) {
            $mensagem = "<p class='erro'>Selecione uma categoria válida.</p>";
            $acao = 'adicionar';

        } else {

            // 1) insere normalmente na tabela produtos
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, destaque, imagem) 
                    VALUES ('$nome', '$descricao', $preco, $estoque, $destaque, '$imagem_nova')";
            
            if (mysqli_query($conexao, $sql)) {

                // 2) também insere na tabela da categoria escolhida
                //    (tabelas têm apenas nome, imagem, preco)
                $tabela_categoria = $categoria; // nome da tabela = value do select

                $sql_cat = "INSERT INTO $tabela_categoria (nome, imagem, preco)
                            VALUES ('$nome', '$imagem_nova', $preco)";
                mysqli_query($conexao, $sql_cat); // se der erro, não quebra o cadastro principal

                $mensagem = "<p class='sucesso'>Produto <strong>$nome</strong> cadastrado com sucesso na categoria <strong>$categoria</strong>!</p>";
                $acao = 'listar'; // Volta para a lista

            } else {
                $mensagem = "<p class='erro'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
                $acao = 'adicionar'; // Permanece no formulário
            }
        }

    // ======================================================
    // EDIÇÃO DE PRODUTO
    // ======================================================
    } elseif (isset($_POST['salvar_edicao']) && $id_edicao) {

        $sql = "UPDATE produtos SET 
                    nome = '$nome', 
                    descricao = '$descricao', 
                    preco = $preco, 
                    estoque = $estoque, 
                    destaque = $destaque";
        
        // Adiciona a atualização da imagem apenas se uma nova foi enviada
        if (!empty($imagem_nova)) {
            $sql .= ", imagem = '$imagem_nova'";
        }
        
        $sql .= " WHERE id = $id_edicao";
        
        if (mysqli_query($conexao, $sql)) {
            $mensagem = "<p class='sucesso'>Produto ID <strong>$id_edicao</strong> atualizado com sucesso!</p>";
            $acao = 'listar'; // Volta para a lista
        } else {
            $mensagem = "<p class='erro'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
            $acao = 'editar'; // Permanece no formulário
            $id_produto = $id_edicao;
        }
    }
}

// --- 2. EXCLUIR PRODUTO (Requisição GET) ---
// --- 2. EXCLUIR PRODUTO (Requisição GET) ---
if ($acao == 'excluir' && $id_produto) {
    $id_produto = mysqli_real_escape_string($conexao, $id_produto);

    // 2.1 Busca dados do produto antes de excluir
    $sql_busca = "SELECT nome, imagem FROM produtos WHERE id = '$id_produto'";
    $res_busca = mysqli_query($conexao, $sql_busca);
    $dadosProd = mysqli_fetch_assoc($res_busca);

    if ($dadosProd) {
        $nome_prod   = mysqli_real_escape_string($conexao, $dadosProd['nome']);
        $imagem_prod = mysqli_real_escape_string($conexao, $dadosProd['imagem']);

        // 2.2 Exclui da tabela principal 'produtos'
        $sql_del = "DELETE FROM produtos WHERE id = '$id_produto'";
        
        if (mysqli_query($conexao, $sql_del)) {

            // 2.3 Também tenta excluir da tabela de categoria correspondente
            // Como não temos a categoria salva, tentamos em todas as tabelas válidas
            foreach ($categorias_validas as $tabela_cat) {
                $sql_del_cat = "
                    DELETE FROM $tabela_cat
                    WHERE imagem = '$imagem_prod'
                    LIMIT 1
                ";
                mysqli_query($conexao, $sql_del_cat);
            }

            $mensagem = "<p class='sucesso'>Produto ID <strong>$id_produto</strong> excluído com sucesso (tabela principal e categoria).</p>";
        } else {
            $mensagem = "<p class='erro'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        $mensagem = "<p class='erro'>Produto não encontrado para exclusão.</p>";
    }

    $acao = 'listar'; // Após a exclusão, volta para a lista
}

// --- 3. PRÉ-CARREGAR DADOS PARA EDIÇÃO (Requisição GET) ---
if ($acao == 'editar' && $id_produto) {
    $id_produto = mysqli_real_escape_string($conexao, $id_produto);
    $sql = "SELECT * FROM produtos WHERE id = $id_produto";
    $resultado = mysqli_query($conexao, $sql);
    
    if ($dados = mysqli_fetch_assoc($resultado)) {
        $nome = $dados['nome'];
        $descricao = $dados['descricao'];
        $preco = $dados['preco'];
        $estoque = $dados['estoque'];
        $imagem = $dados['imagem'];
        $destaque_checked = $dados['destaque'] == 1 ? 'checked' : '';
        $titulo_form = "Editar Produto ID #$id_produto";
    } else {
        $mensagem = "<p class='erro'>Produto não encontrado.</p>";
        $acao = 'listar';
    }
}

// =========================================================================
// EXIBIÇÃO DE CONTEÚDO (HTML/PHP)
// =========================================================================
?>

    <h1>Gerenciamento de Produtos (CRUD)</h1>
    <?php echo $mensagem; ?>

    <?php if ($acao == 'listar'): ?>
        
        <a class='botao' href='index.php?pg=../ParteDePauloeGui/CRUD_produtos&action=adicionar' style='margin-bottom: 25px; display: inline-block; background-color: #28a745;'>+ Cadastrar Novo Produto</a>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style='background-color: #ffd68a;'>
                    <th style='padding: 12px; width: 50px;'>ID</th>
                    <th style='width: 80px;'>Imagem</th>
                    <th>Nome</th>
                    <th style='width: 120px;'>Preço</th>
                    <th style='width: 80px;'>Estoque</th>
                    <th style='width: 80px;'>Destaque</th>
                    <th style='width: 180px;'>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nome, preco, estoque, imagem, destaque FROM produtos ORDER BY id DESC";
                $resultado = mysqli_query($conexao, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($dados = mysqli_fetch_array($resultado)) {
                        if (!empty($dados['imagem'])) {
                            $img = $dados['imagem'];

                            if (strpos($img, 'uploads/') === 0) {
                            // imagens novas, que você subiu pelo CRUD
                                $img_path = '../' . $img;
                            } else {
                        // imagens antigas, que já têm caminho próprio
                                $img_path = $img;
                            }
                        } else {
                            $img_path = "https://via.placeholder.com/60";
                        }
                        $destaque_label = $dados['destaque'] == 1 ? 'Sim' : 'Não';
                        $destaque_cor = $dados['destaque'] == 1 ? 'green' : 'red';
                        
                        echo "<tr>";
                        echo "<td style='padding: 10px;'>$dados[id]</td>";
                        echo "<td><img src='$img_path' style='width: 60px; height: 60px; object-fit: cover;'></td>";
                        echo "<td>$dados[nome]</td>";
                        echo "<td>R$ " . number_format($dados['preco'], 2, ',', '.') . "</td>";
                        echo "<td>$dados[estoque]</td>";
                        echo "<td style='color: $destaque_cor; font-weight: bold;'>$destaque_label</td>";
                        echo "<td>
                                <a href='index.php?pg=../ParteDePauloeGui/CRUD_produtos&action=editar&id=$dados[id]' class='botao' style='padding: 5px 10px; background-color: #007bff;'>Editar</a>
                                <a href='index.php?pg=../ParteDePauloeGui/CRUD_produtos&action=excluir&id=$dados[id]' class='botao sair' style='padding: 5px 10px; margin-left: 5px; background-color: #dc3545;' onclick='return confirm(\"Tem certeza que deseja excluir o produto $dados[id]?\")'>Excluir</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center; padding: 20px;'>Nenhum produto cadastrado no momento.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    
    <?php elseif ($acao == 'adicionar' || $acao == 'editar'): ?>
        
        <div class="form-container" style="max-width: 700px; margin: 20px auto; padding: 30px; border: 1px solid #ff8d00; border-radius: 10px; background-color: #fff;">
            <h2><?php echo $titulo_form; ?></h2>
            
            <form method="POST" enctype="multipart/form-data">
                
                <?php if ($acao == 'editar'): ?>
                    <input type="hidden" name="id_edicao" value="<?php echo $id_produto; ?>">
                <?php endif; ?>

                <label for="nome" style="display: block; margin-top: 15px;">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">

                <label for="descricao" style="display: block; margin-top: 15px;">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="5" style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;"><?php echo htmlspecialchars($descricao); ?></textarea>

                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label for="preco" style="display: block; margin-top: 15px;">Preço (R$):</label>
                        <input type="number" id="preco" name="preco" step="0.01" value="<?php echo htmlspecialchars($preco); ?>" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 1;">
                        <label for="estoque" style="display: block; margin-top: 15px;">Estoque:</label>
                        <input type="number" id="estoque" name="estoque" value="<?php echo htmlspecialchars($estoque); ?>" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
                    </div>
                </div>

                <label for="imagem" style="display: block; margin-top: 15px;">Imagem do Produto:</label>
                <?php if ($acao == 'editar' && !empty($imagem)): ?>
                    <p>Imagem atual: <img src="../<?php echo $imagem; ?>" style="width: 80px; height: 80px; object-fit: cover; vertical-align: middle;"></p>
                <?php endif; ?>
                <?php if ($acao == 'editar' && !empty($imagem)): ?>
                    <input type="text" value="<?php echo $imagem; ?>" 
                    style="width:100%;padding:8px;margin-bottom:10px;" readonly>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" <?php if ($acao == 'adicionar') echo 'required'; ?> style="margin-bottom: 15px;">

                <div style="margin-top: 20px;">
                    <input type="checkbox" id="destaque" name="destaque" value="1" <?php echo $destaque_checked; ?>>
                    <label for="destaque" style="display: inline; font-weight: normal;">Marcar como Produto em Destaque na Página Inicial</label>
                </div>
                
                <?php if ($acao == 'adicionar'): ?>
                <label for="categoria" style="display: block; margin-top: 15px;">Categoria do Produto:</label>
                <select id="categoria" name="categoria" required
                style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
                <option value="">Selecione uma categoria</option>
                <option value="almofadas"    <?php if ($categoria == 'almofadas')    echo 'selected'; ?>>Almofadas</option>
                <option value="portaretrato" <?php if ($categoria == 'portaretrato') echo 'selected'; ?>>Porta-retrato</option>
                <option value="arranjos"     <?php if ($categoria == 'arranjos')     echo 'selected'; ?>>Arranjos</option>
                <option value="ceramicas"    <?php if ($categoria == 'ceramicas')    echo 'selected'; ?>>Cerâmicas</option>
                <option value="calendarios"  <?php if ($categoria == 'calendarios')  echo 'selected'; ?>>Calendários</option>
                </select>
                <?php endif; ?>
            <?php if ($acao == 'editar'): ?>
                <p style="font-size: 12px; color: #666; margin-top: -5px;">Categoria não pode ser alterada na edição (vinculada à tabela <strong><?php echo htmlspecialchars($categoria); ?></strong>).</p>
            <?php endif; ?>

                <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                    <a href='?pg=../ParteDePauloeGui/CRUD_produtos' class='botao sair' style='background-color: #6c757d;'>Cancelar e Voltar</a>
                    <button type="submit" name="<?php echo $acao == 'adicionar' ? 'salvar_adicao' : 'salvar_edicao'; ?>" 
                        class="botao" style="background-color: <?php echo $acao == 'adicionar' ? '#28a745' : '#007bff'; ?>;">
                        <?php echo $acao == 'adicionar' ? 'Cadastrar Produto' : 'Salvar Alterações'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>