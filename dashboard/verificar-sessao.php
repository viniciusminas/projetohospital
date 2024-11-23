<?php
session_start();

// Verifica se o usuário está autenticado
if (isset($_SESSION['usuario_id'])) {
    echo json_encode(['autenticado' => true, 'usuario_id' => $_SESSION['usuario_id'], 'usuario_nome' => $_SESSION['usuario_nome']]);
} else {
    echo json_encode(['autenticado' => false]);
}
?>
