<?php
require_once __DIR__ . '/config.php';

exigir_login(); // Garante que o cliente esteja logado

$id_usuario = $_SESSION['cliente_id']; // ID padronizado

$sql = "SELECT c.id_produto, c.quantidade, p.nome, p.preco 
        FROM carrinho c
        JOIN produtos p ON p.id = c.id_produto
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1>Seu Carrinho</h1>

<?php while ($row = $result->fetch_assoc()): ?>
    <p>
        <strong><?= $row['nome'] ?></strong> — R$ <?= number_format($row['preco'], 2, ',', '.') ?>  
        (Qtd: <?= $row['quantidade'] ?>)
    </p>
<?php endwhile; ?>

<a href="finalizar_compra.php">Finalizar compra</a>