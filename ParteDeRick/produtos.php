<h1 style='text-align: center'>Página de Produtos</h1>
<div class="container-produtos">

    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <h3>Categorias</h3>

        <?php
        // categoria que veio na URL
        $categoria = isset($_GET['cat']) ? $_GET['cat'] : 'todos';

        // mapa de tabelas do banco
        $tabelas = [
            'todos'         => 'produtos',
            'ceramicas'     => 'ceramicas',
            'arranjos'      => 'arranjos',
            'portaretrato' => 'portaretrato',
            'calendarios'   => 'calendarios',
            'almofadas'     => 'almofadas'
        ];

        function linkCategoria($slug, $label, $atual) {
            $ativo = $slug == $atual ? 'ativo' : '';
            $href  = $slug == 'todos' ? '?pg=produtos' : "?pg=produtos&cat=$slug";

            echo "<li><a class='$ativo' href='$href'>$label</a></li>";
        }
        ?>

        <ul>
            <?php
                linkCategoria('todos', 'Todas', $categoria);
                linkCategoria('ceramicas', 'Cerâmicas', $categoria);
                linkCategoria('arranjos', 'Arranjos', $categoria);
                linkCategoria('portaretrato', 'Porta Retratos', $categoria);
                linkCategoria('calendarios', 'Calendários', $categoria);
                linkCategoria('almofadas', 'Almofadas', $categoria);
            ?>
        </ul>
    </aside>


    <div class="produtos">


    <?php

    require_once 'config.inc.php';

    $tabela = $tabelas[$categoria];
    
    $sql = "SELECT * FROM $tabela";

    $resultado = mysqli_query($conexao, $sql);

    if(mysqli_num_rows($resultado)> 0){


        while($dados = mysqli_fetch_array($resultado)){
            $img = $dados['imagem'];

            if (strpos($img, 'uploads/') === 0) {
                $src = '../' . $img;   // porque a vitrine também está dentro de /ParteDeAlgumLugar
            } else {
                $src = $img;
            }

            echo "<div class='produto'> <a href='?pg=../parteArthurYsaac/comprar&id=$dados[id]'>
                <img style='height:250px; width:250px;' src='$src'>
                <p>$dados[nome]</p>
                    <p>R$:$dados[preco]</p></a></div>";
        }
    }

    ?>
    </div>
</div>