<link rel="stylesheet" href="style.css">

<?php
    include_once 'topo.php';

    if(empty($_SERVER["QUERY_STRING"])){
        $var = "conteudo";
        include_once "$var.php";
    }else{
        $pg = $_GET['pg'];
        include "$pg.php";
    }

    include_once 'rodape.html';
?>