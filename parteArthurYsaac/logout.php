<?php
// O arquivo config.php deve ser incluído para ter certeza que a sessão foi iniciada.
// Ele está na mesma pasta que este arquivo.
require_once __DIR__ . '/config.php';

// Limpa as variáveis de sessão específicas do cliente
unset($_SESSION['cliente_id']);
unset($_SESSION['cliente_nome']);
unset($_SESSION['cliente_email']);

// Opcional: Destrói completamente a sessão (todos os dados dela)
// session_destroy(); 
// session_unset();

// Redireciona o usuário para a página inicial (index.php)
header("Location: index.php");
exit;

?>