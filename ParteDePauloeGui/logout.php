<?php
// 1. Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Destrói todas as variáveis de sessão
$_SESSION = array();

// 3. Destrói a sessão.
session_destroy();

// 4. Redireciona o usuário para a página inicial do site (dois níveis acima)
header("Location: ../../index.php"); 
exit();
?>