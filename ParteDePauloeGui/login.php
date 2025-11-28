<?php
// Inicia sessão (importante para usar $_SESSION)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de configuração, que contém a conexão com o banco ($conexao).
require_once "../ParteDeRick/config.inc.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recebe os dados do formulário
    $usuario_input = trim($_POST['usuario'] ?? '');
    $senha_input   = $_POST['senha'] ?? '';

    if ($usuario_input === '' || $senha_input === '') {
        $erro = "Preencha usuário e senha.";
    } else {

        // 2. Consulta o banco de dados com prepared statement
        $sql = "SELECT id, nome, email, senha 
                FROM vendedores 
                WHERE email = ? OR nome = ?";

        if ($stmt = mysqli_prepare($conexao, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $usuario_input, $usuario_input);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $dados = mysqli_fetch_assoc($resultado);
            mysqli_stmt_close($stmt);
        } else {
            $erro = "Erro na consulta ao banco de dados.";
            $dados = null;
        }

        // 3. Verifica se o usuário existe e se a senha está correta
        if ($dados) {

            // ATENÇÃO:
            // Se as senhas na tabela 'vendedores' estiverem salvas com password_hash(),
            // use password_verify (como abaixo).
            // Se estiverem em texto puro (ex: "123456"), TEMPORARIAMENTE use comparação direta.

            if (password_verify($senha_input, $dados['senha'])) {
            // Se as senhas NÃO forem hash, use:
            // if ($senha_input === $dados['senha']) {

                // LOGIN BEM-SUCEDIDO: Armazena dados na sessão
                $_SESSION['admin_logado'] = true;
                $_SESSION['admin_nome']   = $dados['nome'];
                $_SESSION['admin_id']     = $dados['id'];
                
                // Redireciona para o painel de administração
                header("Location: index.php");
                exit();
            } else {
                $erro = "Usuário ou senha inválidos. Verifique suas credenciais.";
            }
        } else {
            $erro = "Usuário ou senha inválidos. Verifique suas credenciais.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sousa Decor Admin</title>
    <link rel="stylesheet" href="../ParteDeRick/style.css"> 
    <style>
        .login-container {
            width: 400px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            text-align: center;
            color: #ff8d00;
            margin-bottom: 30px;
        }
        .login-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .login-container button[type="submit"] {
            width: 100%;
            background-color: #ff8d00; 
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .login-container button[type="submit"]:hover {
            background-color: #ff6600;
        }
        .erro {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="admin-body">

<?php
// Esse topo é o topo do SITE (clientes), não do admin.
// Não tem problema usar, é só visual.
include_once "../ParteDeRick/topo.php";
?>

<div class="login-container">
    <h1>Acesso Administrativo</h1>

    <?php if ($erro): ?>
        <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="usuario">Usuário / E-mail</label>
        <input type="text" id="usuario" name="usuario" required>
        
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
        
        <button type="submit">Entrar</button>
    </form>
    <a href="cadastro_vendedor.php">Ainda não tem conta? Cadastre-se</a>
</div>

</body>
</html>
