<?php
require_once '../src/db.php';

session_start();

header('Content-Type: application/json');

function autenticarUsuario($usuario, $senha) {
    $pdo = conectarBanco();

    $sql = "SELECT * FROM usuarios_adm WHERE login = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioBD && password_verify($senha, $usuarioBD['senha'])) {
        return $usuarioBD;
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($usuario) || empty($senha)) {
        echo json_encode(['erro' => 'Preencha todos os campos.']);
        exit;
    }

    $usuarioAutenticado = autenticarUsuario($usuario, $senha);

    if ($usuarioAutenticado) {
        $_SESSION['usuario_id'] = $usuarioAutenticado['id'];
        $_SESSION['usuario_nome'] = $usuarioAutenticado['login'];

        echo json_encode([
            'sucesso' => 'Usuário autenticado com sucesso.',
            'usuario_id' => $usuarioAutenticado['id'],
            'usuario_nome' => $usuarioAutenticado['login']
        ]);
    } else {
        echo json_encode(['erro' => 'Usuário ou senha inválidos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['erro' => 'Método de requisição inválido.']);
}
?>

