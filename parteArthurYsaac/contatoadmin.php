<?php
// =========================
// clientes/contatoadmin.php
// =========================

require_once __DIR__ . '/config.php';

// Página protegida
exigir_login();

$mensagem = '';
$errors = [];

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

        // Cria o registro do contato
        $registro  = "=================================\n";
        $registro .= "Data: " . date('Y-m-d H:i:s') . "\n";
        $registro .= "Cliente ID: " . ($_SESSION['cliente_id'] ?? '-') . "\n";
        $registro .= "Nome: " . ($_SESSION['cliente_nome'] ?? '-') . "\n";
        $registro .= "Assunto: $assunto\n";
        $registro .= "Mensagem:\n$texto\n\n";

        // Salva no arquivo
        file_put_contents(
            __DIR__ . '/contatos_admin_log.txt',
            $registro,
            FILE_APPEND | LOCK_EX
        );

        $mensagem = "Mensagem enviada ao administrador com sucesso!";
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Contato com o Administrador - Sousadecor</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:Arial,Helvetica,sans-serif;max-width:720px;margin:0 auto;padding:20px}
label{display:block;margin-top:12px}
input,textarea{width:100%;padding:10px;margin-top:4px}
.erro{color:#b71c1c;margin-bottom:12px}
.ok{color:green;font-weight:bold;margin-bottom:16px}
button{padding:8px 16px;margin-top:12px}
</style>
</head>
<body>

<h1>Contato com o Administrador</h1>

<p>Logado como: <strong><?= htmlspecialchars($_SESSION['cliente_nome']) ?></strong></p>
<p><a href="comprar.php">Voltar</a> | <a href="logout.php">Sair</a></p>

<?php if ($mensagem): ?>
<p class="ok"><?= htmlspecialchars($mensagem) ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="erro">
<ul>
<?php foreach ($errors as $e): ?>
<li><?= htmlspecialchars($e) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="post">

<label>Assunto:
<input type="text" name="assunto" value="<?= htmlspecialchars($_POST['assunto'] ?? '') ?>">
</label>

<label>Mensagem:
<textarea name="texto" rows="6"><?= htmlspecialchars($_POST['texto'] ?? '') ?></textarea>
</label>

<button type="submit">Enviar</button>

</form>

</body>
</html>
