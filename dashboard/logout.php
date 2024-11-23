<?php
session_start(); // Inicia a sessão

// Destrói todas as variáveis da sessão
session_unset(); 

// Destroi a sessão
session_destroy(); 

// Redireciona para a página de login
header("Location: login.php");
exit(); // Garante que o código após o redirecionamento não seja executado
?>


