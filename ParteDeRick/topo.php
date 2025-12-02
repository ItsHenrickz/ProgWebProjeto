<?php
// O caminho para config.php deve estar correto (uma pasta acima, na pasta do Arthur)
// CORREÇÃO: Adicionado o ponto e vírgula (;)
require_once "../parteArthurYsaac/config.php";
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sousa Decor</title>
    <link rel="icon" href="../sousafavicon.PNG">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php">
            <img class="logo" src="../IMG_1291 (1).PNG">
        </a>
        <?php
            
        // CORREÇÃO ESSENCIAL: Lógica para aparecer o menu certo
        // Se o cliente ESTIVER logado
        if (cliente_logado()){
            // MOSTRA MENU SAIR
            echo "<div class='menu-usuario'>
                <button class='icone'>
                    <img src='https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png' height='50px'>
                </button>

                <div class='submenu'>
                    <a href='?pg=../parteArthurYsaac/logout'>Sair</a> 
                </div>
            </div>";
        }else{
            // Se o cliente NÃO estiver logado
            // MOSTRA MENU LOGIN/CADASTRO
            echo "<div class='menu-usuario'>
                <button class='icone'>
                    <img src='https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png' height='50px'>
                </button>

                <div class='submenu'>
                    <a href='?pg=../parteArthurYsaac/login'>Fazer login (Cliente)</a>
                    <a href='?pg=../parteArthurYsaac/cadastro'>Cadastre-se (Cliente)</a>

                    <hr style='margin: 5px 0; border: 0; border-top: 1px solid #eee;'>

                    <a href='?pg=../ParteDePauloeGui/login' style='color: #ff8d00; font-weight: bold;'>Acesso Vendedor</a>
                </div>
            </div>";
        }
            
        ?>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <li>|</li>
            <li><a href="?pg=produtos">Produtos</a></li>
            <li>|</li>
            <li><a href="?pg=contato">Contato</a></li>
            <li>|</li>
            <li><a href="?pg=sobrenos">Sobre Nós</a></li>
        </ul>
    </nav>