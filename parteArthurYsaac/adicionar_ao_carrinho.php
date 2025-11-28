<?php
require_once __DIR__ . '/config.php';

exigir_login(); // Garante que o cliente esteja logado

$id_usuario = $_SESSION['cliente_id']; // ID padronizado
$id_produto = $_GET['id'] ?? null;

if (!$id_produto) {
    die("Produto inválido.");
}

$sql = "INSERT INTO carrinho (id_usuario, id_produto, quantidade)
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE quantidade = quantidade + 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_produto);
$stmt->execute();

header("Location: ?pg=../parteArthurYsaac/carrinho");
exit;