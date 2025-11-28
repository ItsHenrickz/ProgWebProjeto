<?php
require_once __DIR__ . '/config.php';

exigir_login(); // Garante que o cliente esteja logado

$id_usuario   = $_SESSION['cliente_id'];        // ID padronizado
$cliente_nome = $_SESSION['cliente_nome'] ?? 'Desconhecido';
$data_compra  = date('Y-m-d H:i:s');
$status       = 'pendente';

// 1. Seleciona itens do carrinho JÁ com as infos do produto
$sql = "SELECT c.id_produto,
               c.quantidade,
               p.nome  AS nome_produto,
               p.preco AS preco_produto
        FROM carrinho c
        JOIN produtos p ON p.id = c.id_produto
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 2. Prepara os inserts em compras e vendas
$sqlInsertCompras = "INSERT INTO compras (id_usuario, id_produto, quantidade, status)
                     VALUES (?, ?, ?, ?)";
$stmtCompras = $conn->prepare($sqlInsertCompras);

$sqlInsertVendas = "INSERT INTO vendas (nome_do_cliente, data, preco, nome_do_produto, status)
                    VALUES (?, ?, ?, ?, ?)";
$stmtVendas = $conn->prepare($sqlInsertVendas);

// 3. Move itens para 'compras' e registra em 'vendas'
while ($item = $result->fetch_assoc()) {
    $id_produto    = $item['id_produto'];
    $quantidade    = $item['quantidade'];
    $nome_produto  = $item['nome_produto'];
    $preco_produto = $item['preco_produto'];

    // compras
    $stmtCompras->bind_param("iiis", $id_usuario, $id_produto, $quantidade, $status);
    $stmtCompras->execute();

    // vendas
    // tipos: s (nome), s (data), d (preço), s (nome_produto), s (status)
    $stmtVendas->bind_param("ssdss", $cliente_nome, $data_compra, $preco_produto, $nome_produto, $status);
    $stmtVendas->execute();
}

// Fecha statements
$stmtCompras->close();
$stmtVendas->close();
$result->free();

// 4. Limpa o carrinho
$sql = "DELETE FROM carrinho WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->close();

// Redireciona para o histórico
header("Location: ?pg=../parteArthurYsaac/historico");
exit;
