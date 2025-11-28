<?php
// O topo.php e o config.inc.php já foram incluídos e a sessão está ativa.
require_once "../ParteDeRick/config.inc.php";
// Assume-se que a variável $conexao com o banco de dados está disponível.
$mensagem = "";

// --- 1. PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar_vendedor'])) {
    
    // Captura e sanitiza dados do formulário
    $nome = mysqli_real_escape_string($conexao, $_POST['nome'] ?? '');
    $email = mysqli_real_escape_string($conexao, $_POST['email'] ?? '');
    $senha_simples = $_POST['senha'] ?? '';
    $nivel_acesso = mysqli_real_escape_string($conexao, $_POST['nivel_acesso'] ?? 'vendedor');

    // Criptografia da senha (ESSENCIAL PARA SEGURANÇA)
    $senha_hash = password_hash($senha_simples, PASSWORD_DEFAULT);

    // Verifica se o e-mail ou nome de usuário já existe
    $check_sql = "SELECT id FROM vendedores WHERE email = '$email'";
    $check_result = mysqli_query($conexao, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $mensagem = "<p class='erro'>Erro: O e-mail informado já está cadastrado.</p>";
    } else {
        // SQL de Inserção (usando a senha criptografada)
        // IMPORTANTE: Use Prepared Statements na versão final!
        $sql = "INSERT INTO vendedores (nome, email, senha, nivel_acesso) 
                VALUES ('$nome', '$email', '$senha_hash', '$nivel_acesso')";

        if (mysqli_query($conexao, $sql)) {
            $mensagem = "<p class='sucesso' style='background-color: #d4edda; border-color: #c3e6cb; color: #155724;'>Vendedor **$nome** cadastrado com sucesso!</p>";
        } else {
            $mensagem = "<p class='erro'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }
}
?>

    <h1>Cadastro e Gestão de Vendedores</h1>
    <?php echo $mensagem; ?>
    
    <div class="form-container" style="max-width: 600px; margin: 20px auto; padding: 30px; border: 1px solid #ff8d00; border-radius: 10px; background-color: #fff;">
        
        <h2>Cadastrar Novo Usuário Interno</h2>
        
        <form method="POST">
            
            <label for="nome" style="display: block; margin-top: 15px;">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">

            <label for="email" style="display: block; margin-top: 15px;">E-mail (Login):</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
            
            <label for="senha" style="display: block; margin-top: 15px;">Senha:</label>
            <input type="password" id="senha" name="senha" required minlength="6" style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
            
            <label for="nivel_acesso" style="display: block; margin-top: 15px;">Nível de Acesso:</label>
            <select id="nivel_acesso" name="nivel_acesso" style="width: 100%; padding: 10px; margin-bottom: 20px; box-sizing: border-box;">
                <option value="vendedor">Vendedor</option>
                <option value="admin">Administrador (Acesso total)</option>
            </select>
            
            <button type="submit" name="cadastrar_vendedor" class="botao" style="background-color: #ff8d00; width: 100%; padding: 12px; font-size: 1.1em; cursor: pointer;">
                Cadastrar Vendedor
            </button>
        </form>
        <a href="?pg=../ParteDePauloeGui/login">Já tem conta? Faça Login</a>
    </div>

    <hr style="margin: 40px 0;">

    <h2>Vendedores Ativos</h2>

    <table border="1" style="width: 80%; margin: 20px auto; border-collapse: collapse; text-align: left;">
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
            $sql_list = "SELECT id, nome, email, nivel_acesso FROM vendedores ORDER BY nome ASC";
            $resultado_list = mysqli_query($conexao, $sql_list);

            if (mysqli_num_rows($resultado_list) > 0) {
                while ($dados_list = mysqli_fetch_array($resultado_list)) {
                    $cor_nivel = $dados_list['nivel_acesso'] == 'admin' ? '#cc0000' : '#007bff';
                    echo "<tr>";
                    echo "<td style='padding: 8px;'>$dados_list[id]</td>";
                    echo "<td>$dados_list[nome]</td>";
                    echo "<td>$dados_list[email]</td>";
                    echo "<td style='font-weight: bold; color: $cor_nivel;'>$dados_list[nivel_acesso]</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center; padding: 15px;'>Nenhum vendedor cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>


    