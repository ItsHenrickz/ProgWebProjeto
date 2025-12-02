<?php
// =========================
// clientes/comprar.php
// =========================

require_once __DIR__ . '/config.php';

// Exigir que o cliente esteja logado
exigir_login();

require "config.inc.php";

$id = $_REQUEST["id"];

$sql = "SELECT * FROM produtos WHERE id = '$id'";

$resultado = mysqli_query($conexao, $sql);

if(mysqli_num_rows($resultado) > 0){
    while($dados = mysqli_fetch_array($resultado)){
        $nome = $dados["nome"];
        $img = $dados['imagem'];

            if (strpos($img, 'uploads/') === 0) {
                $src = '../' . $img;   // porque a vitrine também está dentro de /ParteDeAlgumLugar
            } else {
                $src = $img;
            }
        $preco = $dados["preco"];
        $id = $dados["id"];
        $descricao = $dados["descricao"];
        ?>

        <p>Logado como: <strong><?= htmlspecialchars($_SESSION['cliente_nome']) ?></strong></p>
        <p><a href="?pg=../parteArthurYsaac/logout">Sair</a></p>
        <div class="pagproduto">
        <div class="product-image">
            <?php echo"<img src='$src'>";?>
        </div>

        <div class="product-info">
            <h1><?php echo $dados['nome']; ?></h1>
            <div class="price"><?php
            echo "R$:";
            echo $dados['preco'];
             ?></div><p style='text-align: left; color: #444; margin-bottom: 15px;'><?php echo $dados['descricao']; ?></p>
                <a class="buy-btn" href="?pg=../parteArthurYsaac/adicionar_ao_carrinho&id=<?= $id ?>">
                    Comprar agora
                </a>
            </div>
        </div><?php
    }
}

?>



</div>
<p><a class="botao" href="?pg=../parteArthurYsaac/contatoadmin">Falar com o vendedor</a></p>

</body>
</html>
