<?php
require_once 'db.php';

// Função de login
function login($login, $senha) {
    $pdo = conectarBanco();
    $sql = "SELECT * FROM usuarios_admin WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        return true;
    }
    return false;
}

// Verificação de sessão ativa
function verificarSessao() {
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>
