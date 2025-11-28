<?php
// =========================
// clientes/contatoadmin.php
// =========================

require_once __DIR__ . '/config.php';
require_once "../ParteDeRick/config.inc.php";
// Página protegida
exigir_login();

$mensagem = '';
$errors = [];

// GARANTE QUE TEMOS UMA CONEXÃO COM O BANCO
// Se no seu config.php a conexão estiver em $conexao (mysqli), estará ok.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $assunto = trim($_POST['assunto'] ?? '');
    $texto   = trim($_POST['texto'] ?? '');

    if ($assunto === '') {
        $errors[] = 'O assunto é obrigatório.';
    }

    if ($texto === '') {
        $errors[] = 'A mensagem não pode estar vazia.';
    }

    if (empty($errors)) {

        // Pega os dados do cliente logado
        $cliente_nome = $_SESSION['cliente_nome'] ?? 'Desconhecido';
        $data = date('Y-m-d H:i:s');

        // ============================
        // SALVAR NA TABELA "mensagens"
        // ============================

        $sql = "INSERT INTO mensagens (nome, data, assunto, mensagem)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $cliente_nome, $data, $assunto, $texto);
            $stmt->execute();
            $stmt->close();

            $mensagem = "Mensagem enviada ao administrador com sucesso!";
        } else {
            $errors[] = "Erro ao preparar consulta SQL: " . $conexao->error;
        }
    }
}
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Contato com o Administrador - Sousa Decor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mesmo CSS da página de login -->
    <link rel="stylesheet" href="../ParteDeRick/style.css">
</head>
<body>

<div class="login-container">
    <h1>Contato com o Administrador</h1>

    <p style="margin-bottom: 15px;">
        Logado como: <strong><?= htmlspecialchars($_SESSION['cliente_nome']) ?></strong>
    </p>
    <p style="margin-bottom: 25px;">
        <a href="?pg=produtos">Voltar</a>
        &nbsp;&nbsp;
        <a href="?pg=../parteArthurYsaac/logout">Sair</a>
    </p>

    <?php if ($mensagem): ?>
        <p class="ok" style="margin-bottom: 20px; color: green; font-weight: bold;">
            <?= htmlspecialchars($mensagem) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="erro">
            <ul style="margin: 0; padding-left: 20px; text-align: left;">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" style="text-align: left; margin-top: 20px;">
        <label for="assunto">Assunto:</label>
        <input
            type="text"
            id="assunto"
            name="assunto"
            value="<?= htmlspecialchars($_POST['assunto'] ?? '') ?>"
            required
        >

        <label for="texto">Mensagem:</label>
        <textarea
            id="texto"
            name="texto"
            rows="6"
            required
        ><?= htmlspecialchars($_POST['texto'] ?? '') ?></textarea>

        <button type="submit">Enviar</button>
    </form>
</div>

</body>
</html>