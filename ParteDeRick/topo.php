<?php
require_once "../parteArthurYsaac/config.php"
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
        <a href="../ParteDeRick/index.php">
            <img class="logo" src="../IMG_1291 (1).PNG">
        </a>
            <?php
            if (cliente_logado()){
                echo "
                <div class='menu-usuario' style='display: flex; align-items: center; gap: 10px;'>

                    <!-- Ícone do Carrinho -->
                    <a href='?pg=../parteArthurYsaac/carrinho'>
                        <img src='https://cdn-icons-png.flaticon.com/512/126/126510.png' 
                            height='35px' 
                            style='cursor: pointer;'>
                    </a>

                    <!-- Ícone de Histórico -->
                    <a href='?pg=../parteArthurYsaac/finalizar_compra'>
                        <img src='https://cdn-icons-png.flaticon.com/512/3908/3908120.png' 
                            height='35px' 
                            style='cursor: pointer;'>
                    </a>

                    <!-- Ícone do usuário -->
                    <button class='icone'>
                        <img src='https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png' 
                            height='50px'>
                    </button>

                    <div class='submenu'>
                        <p style='margin: 0; padding: 8px; color: #555; white-space: nowrap;'>
                            Logado como <strong>".$_SESSION['cliente_nome']."</strong>
                        </p>
                        <a href='?pg=../parteArthurYsaac/logout'>Sair</a>
                    </div>
                </div>";
            } else {
                echo "
                <div class='menu-usuario'>
                    <button class='icone'>
                        <img src='https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png' height='50px'>
                    </button>

                    <div class='submenu'>
                        <a href='?pg=../parteArthurYsaac/login'>Fazer login</a>
                        <a href='?pg=../parteArthurYsaac/cadastro'>Cadastre-se</a>
                    </div>
                </div>
                ";
            }
            
            ?>
        </a>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Início</a></li>
            <li>|</li>
            <li><a href="?pg=produtos">Produtos</a></li>
            <li>|</li>
            <li><a href="?pg=contato">Contato</a></li>
        </ul>
    </nav>
