<?php
// =======================================================
// CONFIGURAÇÃO GLOBAL DO SISTEMA - Sousadecor
// =======================================================

// Iniciar sessão sempre
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------
// DADOS DE CONEXÃO COM O BANCO DE DADOS
// ------------------------------------
define('DB_SERVER', 'localhost:3310'); // Geralmente 'localhost'
define('DB_USERNAME', 'root');    // SEU USUÁRIO MYSQL
define('DB_PASSWORD', '');        // SUA SENHA MYSQL
define('DB_NAME', 'sousadecor');  // NOME DO SEU BANCO DE DADOS

// Criar a conexão com o MySQLi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    // Esta mensagem de erro não deve mais aparecer,
    // pois ajustamos a autenticação do usuário 'root' no MariaDB.
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// =======================================================
// FUNÇÕES ESSENCIAIS
// =======================================================

/**
 * Verifica se o cliente está logado (usando o padrão 'cliente_id').
 */
function cliente_logado() {
    return isset($_SESSION['cliente_id']);
}

/**
 * Redireciona para o login se não estiver logado.
 */
function exigir_login() {
    if (!cliente_logado()) {
        header("Location: ?pg=../parteArthurYsaac/login");
        exit;
    }
}
// As funções de JSON (ler_clientes, salvar_clientes, etc.) foram REMOVIDAS.
?>