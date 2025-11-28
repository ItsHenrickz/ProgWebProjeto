<?php
require_once __DIR__ . '/config.php'; // Garante que a sessão esteja iniciada

session_unset();
session_destroy();

header("Location: ?pg=../parteArthurYsaac/login");
exit;