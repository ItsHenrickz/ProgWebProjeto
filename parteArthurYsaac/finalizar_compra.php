<?php
require_once __DIR__ . '/config.php';

exigir_login(); // Garante que o cliente esteja logado

$id_usuario = $_SESSION['cliente_id']; // ID padronizado

// 1. Seleciona itens do carrinho
$sql = "SELECT id_produto, quantidade FROM carrinho WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 2. Move itens para a tabela 'compras'
while ($item = $result->fetch_assoc()) {
    $sqlInsert = "INSERT INTO compras (id_usuario, id_produto, quantidade, status)
                  VALUES (?, ?, ?, 'espera')";
    $stmt2 = $conn->prepare($sqlInsert);
    $stmt2->bind_param("iii", $id_usuario, $item['id_produto'], $item['quantidade']);
    $stmt2->execute();
    $stmt2->close(); // Fechar o statement interno
}

// 3. Limpa o carrinho
$sql = "DELETE FROM carrinho WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->close();

header("Location: historico.php");
exit;