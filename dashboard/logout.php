<?php
session_start(); // Inicia a sessão

// Destrói a sessão e redireciona para a tela de login
session_unset();
session_destroy();

echo json_encode(['sucesso' => 'Sessão encerrada com sucesso.']);
?>
