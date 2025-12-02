<?php
// 1. CORREÇÃO: Verifica se a sessão já está ativa antes de chamar session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. INCLUSÃO DO ARQUIVO DE CONFIGURAÇÃO USANDO CAMINHO ABSOLUTO RESOLVIDO
// Esta é a forma mais robusta de garantir que o config.inc.php seja encontrado.
$config_path = __DIR__ . '/../ParteDeRick/config.inc.php'; 

// 3. INCLUSÃO
// O 'config.inc.php' deve definir a variável $conexao.
require_once $config_path;

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $usuario_input = $_POST['usuario'] ?? '';
    $senha_input = $_POST['senha'] ?? '';

    // 4. SEGURANÇA CRÍTICA: Uso de Prepared Statements para evitar SQL Injection.
    // Consulta o banco de dados. Busca por e-mail OU nome de usuário.
    $sql = "SELECT id, nome, email, senha, nivel_acesso FROM vendedores WHERE email = ? OR nome = ?";
    
    // Prepara a declaração. A variável $conexao vem do config.inc.php
    if (isset($conexao)) {
        $stmt = mysqli_prepare($conexao, $sql);
    } else {
        $erro = "Erro de conexão: Variável \$conexao não definida. Verifique config.inc.php.";
        goto end_of_post; // Pula o restante do processamento POST em caso de erro crítico
    }
    
    // Vincula os parâmetros: 'ss' significa duas strings (email e nome)
    mysqli_stmt_bind_param($stmt, "ss", $usuario_input, $usuario_input);
    
    // Executa a consulta
    mysqli_stmt_execute($stmt);
    
    // Obtém o resultado
    $resultado = mysqli_stmt_get_result($stmt);
    $dados = mysqli_fetch_assoc($resultado);

    // 5. Verifica se o usuário existe e se a senha está correta
    if ($dados && password_verify($senha_input, $dados['senha'])) {
        // LOGIN BEM-SUCEDIDO: Armazena dados na sessão
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_nome'] = $dados['nome'];
        $_SESSION['admin_id'] = $dados['id'];
        $_SESSION['nivel_acesso'] = $dados['nivel_acesso']; // Ajustado para 'nivel_acesso'
        
        // Limpa a declaração preparada
        mysqli_stmt_close($stmt);
        
        // 6. REDIRECIONAMENTO: Para a página principal do painel administrativo.
        // Mantendo o caminho que você já estava usando:
        header("Location: ../ParteDePauloeGui/index.php");
        exit();

    } else {
        // LOGIN FALHOU
        $erro = "Usuário ou senha inválidos.";
        
        // Se a declaração foi preparada, mas o login falhou, ainda precisamos fechá-la
        if (isset($stmt) && $stmt) { 
            mysqli_stmt_close($stmt);
        }
    }
}
end_of_post: // Ponto de saída em caso de erro de conexão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Vendedor</title>
    <link rel="stylesheet" href="../ParteDeRick/style.css">
<body class="admin-body">

<div class="login-container">
    <h1>Acesso Administrativo (Vendedor)</h1>

    <?php if ($erro): ?>
        <p class="erro"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="usuario">Usuário / E-mail</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>