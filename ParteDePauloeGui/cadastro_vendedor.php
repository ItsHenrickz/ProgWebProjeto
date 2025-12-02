<?php
// Adicionado para exibir todos os erros e facilitar a depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// O topo.php já foi incluído no index.php e já iniciou a sessão e verificou o login.
// Assume-se que a variável $conexao com o banco de dados está disponível.

// --- VERIFICAÇÃO DE NÍVEL DE ACESSO (APENAS 'admin') ---
if (($_SESSION['nivel_acesso'] ?? 'vendedor') !== 'admin') {
    echo "<div style='text-align: center; padding: 50px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; margin-top: 20px;'>";
    echo "<h2 style='color: #721c24;'>ACESSO NEGADO</h2>";
    // Mudei o texto para ser mais informativo
    echo "<p style='color: #721c24;'>Esta funcionalidade é restrita a administradores. Verifique se o seu usuário logado tem o nível de acesso **admin**.</p>";
    echo "</div>";
    exit();
}

$mensagem = "";

// --- 1. PROCESSAMENTO DO FORMULÁRIO (USANDO PREPARED STATEMENTS) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar_vendedor'])) {
    
    // Captura dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha_simples = $_POST['senha'] ?? '';
    // Garante que o nível de acesso seja seguro (vendedor ou admin)
    $nivel_acesso = $_POST['nivel_acesso'] ?? 'vendedor';

    // Criptografia da senha (ESSENCIAL PARA SEGURANÇA)
    $senha_hash = password_hash($senha_simples, PASSWORD_DEFAULT);

    $email_existente = false;
    
    // --- 1.1. VERIFICAÇÃO DE E-MAIL EXISTENTE (Prepared Statement) ---
    // Prepara a declaração para evitar SQL Injection
    $check_stmt = mysqli_prepare($conexao, "SELECT id FROM vendedores WHERE email = ?");
    
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $email_existente = true;
        }
        mysqli_stmt_close($check_stmt);
    } else {
        // Erro se a PREPARAÇÃO da query de checagem falhar
        $mensagem = "<p class='erro' style='background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Erro de Preparação na Verificação: " . mysqli_error($conexao) . "</p>";
    }

    if ($email_existente) {
        $mensagem = "<p class='erro' style='background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Erro: O e-mail informado já está cadastrado.</p>";
    } else if (isset($check_stmt) && !$email_existente) {
        // --- 1.2. SQL de Inserção (Prepared Statement) ---
        $insert_stmt = mysqli_prepare($conexao, "INSERT INTO vendedores (nome, email, senha, nivel_acesso) VALUES (?, ?, ?, ?)");
        
        if ($insert_stmt) {
            // "ssss" indica que todos os 4 parâmetros são strings
            mysqli_stmt_bind_param($insert_stmt, "ssss", $nome, $email, $senha_hash, $nivel_acesso);

            if (mysqli_stmt_execute($insert_stmt)) {
                // Sucesso: Limpa o formulário (Redireciona para evitar reenvio)
                header("Location: index.php?pg=cadastro_vendedor&msg=sucesso");
                exit();
            } else {
                // Erro se a EXECUÇÃO da query falhar
                $mensagem = "<p class='erro' style='background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Erro ao cadastrar: " . mysqli_stmt_error($insert_stmt) . "</p>";
            }
            mysqli_stmt_close($insert_stmt);
        } else {
             // Erro se a PREPARAÇÃO da query de inserção falhar
             $mensagem = "<p class='erro' style='background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'>Erro de Preparação da Query de Inserção: " . mysqli_error($conexao) . "</p>";
        }
    }
}

// Verifica se houve uma mensagem de sucesso após o redirecionamento (Padrão PRG)
if (isset($_GET['msg']) && $_GET['msg'] == 'sucesso') {
    $mensagem = "<p class='sucesso' style='background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;'>Vendedor cadastrado com sucesso!</p>";
}
?>

<style>
    /* Estilos para o layout principal (side-by-side) */
    .main-content-layout {
        display: flex;
        gap: 30px; /* Espaçamento entre os blocos */
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
        align-items: flex-start; /* Alinha no topo */
    }

    .form-block {
        /* Largura fixa para o formulário */
        flex: 0 0 400px; 
    }

    .list-block {
        /* Ocupa o restante do espaço */
        flex: 1; 
        min-width: 0; 
    }
    
    /* Estilos de responsividade: empilha verticalmente em telas menores */
    @media (max-width: 900px) {
        .main-content-layout {
            flex-direction: column; 
        }
        .form-block, .list-block {
            width: 100%;
            max-width: 600px; /* Limita a largura do formulário empilhado */
            margin: 0 auto;
        }
        .list-block table {
            width: 100% !important; /* Força a tabela a ocupar o espaço total no mobile */
            margin: 20px 0 !important;
        }
    }

    /* Estilos para o formulário de Cadastro */
    .form-container {
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    .form-container label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"],
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .form-container button {
        background-color: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
        transition: background-color 0.3s;
    }
    .form-container button:hover {
        background-color: #218838;
    }
</style>

<!-- Bloco que gerencia o Layout de 2 colunas -->
<div class="main-content-layout">
    <!-- COLUNA 1: FORMULÁRIO -->
    <div class="form-block">
        <div class="form-container">
            <h1>Cadastrar Novo Vendedor</h1>

            <?php echo $mensagem; // Exibe a mensagem de sucesso ou erro ?>

            <form method="POST">
                <label for="nome">Nome do Vendedor:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">E-mail (Login):</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha Temporária:</label>
                <input type="password" id="senha" name="senha" required>

                <label for="nivel_acesso">Nível de Acesso:</label>
                <select id="nivel_acesso" name="nivel_acesso">
                    <option value="vendedor">Vendedor Comum</option>
                    <option value="admin">Administrador (Acesso Total)</option>
                </select>

                <button type="submit" name="cadastrar_vendedor">Cadastrar Vendedor</button>
            </form>
        </div>
    </div>

    <!-- COLUNA 2: LISTA DE VENDEDORES -->
    <div class="list-block">
        <h2>Vendedores Ativos</h2>

        <table border="1" style="width: 100%; margin: 20px 0; border-collapse: collapse; text-align: left; background-color: white; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style='background-color: #ffd68a;'>
                    <th style='padding: 12px; width: 50px;'>ID</th>
                    <th>Nome</th>
                    <th style='width: 200px;'>E-mail</th>
                    <th style='width: 150px;'>Nível</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Se a variável $conexao não estiver definida, esta query falhará
                if (isset($conexao)) {
                    $sql_list = "SELECT id, nome, email, nivel_acesso FROM vendedores ORDER BY nome ASC";
                    $resultado_list = mysqli_query($conexao, $sql_list);

                    if ($resultado_list && mysqli_num_rows($resultado_list) > 0) {
                        while ($dados_list = mysqli_fetch_array($resultado_list)) {
                            $cor_nivel = $dados_list['nivel_acesso'] == 'admin' ? '#cc0000' : '#007bff';
                            echo "<tr>";
                            echo "<td style='padding: 8px;'>$dados_list[id]</td>";
                            echo "<td>$dados_list[nome]</td>";
                            echo "<td>$dados_list[email]</td>";
                            echo "<td style='padding: 8px; font-weight: bold; color: $cor_nivel;'>" . strtoupper($dados_list['nivel_acesso']) . "</td>";
                            echo "</tr>";
                        }
                    } else if (!$resultado_list) {
                        echo "<tr><td colspan='4' style='text-align: center; padding: 15px; color: red;'>Erro na Query de Lista: " . mysqli_error($conexao) . "</td></tr>";
                    } else {
                         echo "<tr><td colspan='4' style='text-align: center; padding: 15px;'>Nenhum vendedor cadastrado ainda.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 15px; color: red;'>ERRO: Variável \$conexao não está definida. Verifique o config.inc.php.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>