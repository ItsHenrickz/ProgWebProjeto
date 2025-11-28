<?php
require_once __DIR__ . '/config.php';

$errors = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['senha'] ?? '';
    $senha2   = $_POST['senha2'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    // ====== VALIDAÇÃO ======
    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if (strlen($senha) < 6) $errors[] = 'A senha precisa de pelo menos 6 caracteres.';
    if ($senha !== $senha2) $errors[] = 'As senhas não coincidem.';

    // ====== VERIFICAR SE JÁ EXISTE NO MYSQL ======
    if (empty($errors)) {
        $sql_check = "SELECT id FROM clientes WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $errors[] = 'Este e-mail já está cadastrado.';
        }
        $stmt_check->close();
    }

    // ====== SE PASSOU NA VALIDAÇÃO, SALVAR NO MYSQL ======
    if (empty($errors)) {

        // Criptografia da senha antes de salvar
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO clientes (nome, email, senha, telefone, endereco)
                       VALUES (?, ?, ?, ?, ?)";
        
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $nome, $email, $senha_hash, $telefone, $endereco);
        
        if ($stmt_insert->execute()) {
            $sucesso = true;
        } else {
            $errors[] = 'Erro ao cadastrar: ' . $conn->error;
        }
        $stmt_insert->close();
    }
}
?>
<div class="login-container">
<h1>Cadastro de Cliente</h1>

<?php if ($sucesso): ?>
    <p class="ok">Cadastro realizado com sucesso! <a href="?pg=../parteArthurYsaac/login">Clique aqui para entrar</a>.</p>

<?php else: ?>

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

<label>Nome:
    <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
</label>

<label>E-mail:
    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
</label>

<label>Senha:
    <input type="password" name="senha">
</label>

<label>Confirmar senha:
    <input type="password" name="senha2">
</label>

<label>Telefone:
    <input type="text" name="telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
</label>

<label>Endereço:
    <textarea name="endereco"><?= htmlspecialchars($_POST['endereco'] ?? '') ?></textarea>
</label>

<button type="submit">Cadastrar</button>

</form>

<p>Já tem conta? <a href="?pg=../parteArthurYsaac/login">Entrar</a></p>

<?php endif; ?>
</div>