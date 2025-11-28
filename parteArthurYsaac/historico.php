<?php
require_once __DIR__ . '/config.php';

exigir_login(); // Garante que o cliente esteja logado

$id_usuario = $_SESSION['cliente_id']; // ID padronizado

$sql = "SELECT c.quantidade, c.status, p.nome, p.preco
        FROM compras c
        JOIN produtos p ON p.id = c.id_produto
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario); // <-- CORREÇÃO APLICADA AQUI
$stmt->execute();
$result = $stmt->get_result();
?>

<h1>Histórico de Compras</h1>

<?php while ($row = $result->fetch_assoc()): ?>
    <p>
        <strong><?= $row['nome'] ?></strong><br>
        Status: <?= $row['status'] ?><br>
        Quantidade: <?= $row['quantidade'] ?><br>
        Valor: R$ <?= number_format($row['preco'], 2, ',', '.') ?>
    </p>
<?php endwhile; ?>