<div class="login-container">
<?php
require_once __DIR__ . '/config.php'; // já inicia sessão e define funções

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $errors[] = 'Preencha todos os campos.';
    } else {

        $sql = "SELECT id, nome, senha FROM clientes WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $clienteEncontrado = $result->fetch_assoc();
        $stmt->close();

        if ($clienteEncontrado) {

            // Verificar senha usando o hash
            if (password_verify($senha, $clienteEncontrado['senha'])) {

                // LOGIN OK
                session_regenerate_id(true);

                $_SESSION['cliente_id']    = $clienteEncontrado['id'];
                $_SESSION['cliente_nome']  = $clienteEncontrado['nome'];
                $_SESSION['cliente_email'] = $email;

                header("Location: ?pg=produtos");
                exit;

            } else {
                $errors[] = 'Senha incorreta.';
            }

        } else {
            $errors[] = 'E-mail não encontrado.';
        }
    }
}
?>
<h1>Login</h1>

<p><a href="?pg=../parteArthurYsaac/cadastro">Não tem conta? Cadastre-se</a></p>

<?php if (!empty($errors)): ?>
    <div class="erro">
        <ul>
            <?php foreach($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <label>E-mail:
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label>

    <label>Senha:
        <input type="password" name="senha">
    </label>

    <button type="submit">Entrar</button>
</form>
</div>
