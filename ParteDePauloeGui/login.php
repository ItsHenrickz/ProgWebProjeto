<?php


// Inclui o arquivo de configuração, que contém a conexão com o banco ($conexao).
require_once "../ParteDeRick/config.inc.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recebe os dados do formulário
    $usuario_input = $_POST['usuario'] ?? '';
    $senha_input = $_POST['senha'] ?? '';

    // 2. Consulta o banco de dados
    // IMPORTANTE: Em um ambiente real, NUNCA use as variáveis diretamente na query! 
    // Use Prepared Statements (PDO ou MySQLi) para prevenir SQL Injection.
    $sql = "SELECT id, nome, email, senha FROM vendedores WHERE email = '$usuario_input' OR nome = '$usuario_input'";
    $resultado = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_assoc($resultado);

    // 3. Verifica se o usuário existe e se a senha está correta
    // A função password_verify é essencial para checar senhas criptografadas
    if ($dados && password_verify($senha_input, $dados['senha'])) {
        // LOGIN BEM-SUCEDIDO: Armazena dados na sessão
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_nome'] = $dados['nome'];
        $_SESSION['admin_id'] = $dados['id'];
        
        // Redireciona para o painel de administração
        header("Location: ?pg=../ParteDePauloeGui/CRUD_produtos");
        exit();
    } else {
        // LOGIN FALHOU
        $erro = "Usuário ou senha inválidos. Verifique suas credenciais.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sousa Decor Admin</title>
    <link rel="stylesheet" href="../style.css"> 
    <style>
        /* Estilos específicos para a tela de login, aproveitando classes do style.css */
        
        /* Centraliza a caixa de login no meio da tela */
    
        
        /* Reutiliza a classe .login-container do seu CSS ou aprimora: */
        .login-container {
            width: 400px;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            text-align: center;
            color: #ff8d00; /* Cor da sua identidade visual */
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
        /* Usa o estilo do botão do seu site, mas aplicado ao submit */
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

<div class="login-container">
    <h1>Acesso Administrativo</h1>

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
    <a href="?pg=../ParteDePauloeGui/cadastro_vendedor">Ainda não tem conta? Cadastre-se</a>
</div>

</body>
</html>