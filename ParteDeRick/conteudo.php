<div class="conteudo">
<!-- Banner -->
<img class="banner" src="https://www.decorilla.com/online-decorating/wp-content/uploads/2024/07/Expert-decorating-for-a-small-living-room-by-Decorilla-scaled.jpg">

<!-- Seção de Vitrine de Produtos -->
<h1 class="h1vitrine">Produtos em Destaque<h1>
<div class="vitrinepaginainicial">
    <div class="prodpaginic">
        <?php

        require "config.inc.php";

        $sql = "SELECT * FROM produtos WHERE id = 1";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) > 0){
            while($dados = mysqli_fetch_array($resultado)){
                $nome = $dados["nome"];
                $imagem = $dados["imagem"];
                $preco = $dados["preco"];
                $id = $dados["id"];

                echo "<a href='?pg=../parteArthurYsaac/comprar&id=$dados[id]'><img src='$dados[imagem]'>
                <p>$dados[nome]</p>
                <p>R$:$dados[preco]</p></a>";
            }
        }
        ?>
    </div>
        
    <div class="prodpaginic">
        <?php

        require "config.inc.php";

        $sql = "SELECT * FROM produtos WHERE id = 2";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) > 0){
            while($dados = mysqli_fetch_array($resultado)){
                $nome = $dados["nome"];
                $imagem = $dados["imagem"];
                $preco = $dados["preco"];
                $id = $dados["id"];

                echo "<a href='?pg=../parteArthurYsaac/comprar&id=$dados[id]'><img src='$dados[imagem]'>
                <p>$dados[nome]</p>
                <p>R$:$dados[preco]</p></a>";
            }
        }
        ?>
    </div>

    <div class="prodpaginic">
        <?php

        require "config.inc.php";

        $sql = "SELECT * FROM produtos WHERE id = 3";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) > 0){
            while($dados = mysqli_fetch_array($resultado)){
                $nome = $dados["nome"];
                $imagem = $dados["imagem"];
                $preco = $dados["preco"];
                $id = $dados["id"];

                echo "<a href='?pg=../parteArthurYsaac/comprar&id=$dados[id]'><img src='$dados[imagem]'>
                <p>$dados[nome]</p>
                <p>R$:$dados[preco]</p></a>";
            }
        }
        ?>
    </div>

    <div class="prodpaginic">
        <?php

        require "config.inc.php";

        $sql = "SELECT * FROM produtos WHERE id = 4";
        $resultado = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($resultado) > 0){
            while($dados = mysqli_fetch_array($resultado)){
                $nome = $dados["nome"];
                $imagem = $dados["imagem"];
                $preco = $dados["preco"];
                $id = $dados["id"];

                echo "<a href='?pg=../parteArthurYsaac/comprar&id=$dados[id]'><img src='$dados[imagem]'>
                <p>$dados[nome]</p>
                <p>R$:$dados[preco]</p></a>";
            }
        }
        ?>
    </div>
</div>

<!-- Seção de Gifs -->
<div class="gifsdiv">
<img src="../Adobe Express - IMG_8154 (1).gif">
<img src="../Adobe Express - IMG_8155.gif">
<img src="../Adobe Express - IMG_8157.gif">
</div>

<!-- Categorias -->
<h1 class="h1cat">Categorias<h1>
<div class='categorias'>
    <div>
        <a href="?pg=produtos&cat=ceramicas">
        <img src="https://blog.retrobel.com.br/wp-content/uploads/2023/08/Ceramica-na-decoracao-Retrobel-2-1024x1024.png">
        <p>Cerâmicas</p></a>
    </div>
    <div>
        <a href="?pg=produtos&cat=arranjos">
        <img src="https://m.media-amazon.com/images/I/81LrcJfpdZL.jpg">
        <p>Arranjos</p></a>
    </div>
    <div>
        <a href="?pg=produtos&cat=portaretrato">
        <img src="https://chalebaunilha.cdn.magazord.com.br/img/2024/02/produto/3344/03-porta-retrato-decor-estilo-madeira-10x15.jpeg?ims=1000x1000">
        <p>Porta Retratos</p></a>
    </div>
    <div>
        <a href="?pg=produtos&cat=calendarios">
        <img src="https://down-br.img.susercontent.com/file/br-11134207-7r98o-lr356t3r5c7h24">
        <p>Calendários</p></a>
    </div>
    <div>
        <a href="?pg=produtos&cat=almofadas">
        <img src="https://images.tcdn.com.br/img/img_prod/1182945/kit_de_almofadas_decorativas_verde_10323_2_20021d11e612cd7d6cf7e1c64645e2ce.jpg">
        <p>Almofadas</p></a>
    </div>
</div>

<a href="?pg=../ParteDePauloeGui/index">Vendedor</a>
</div>