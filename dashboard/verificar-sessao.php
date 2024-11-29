<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    echo json_encode(['autenticado' => true, 'usuario_id' => $_SESSION['usuario_id'], 'usuario_nome' => $_SESSION['usuario_nome']]);
} else {
    echo json_encode(['autenticado' => false]);
}
?>
