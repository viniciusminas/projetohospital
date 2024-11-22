<?php
header('Content-Type: application/json');

// Conexão com o banco de dados
require_once '../src/db.php'; // Altere para o caminho correto do arquivo de conexão

// Função para gerar um JWT simples
function gerarJWT($login) {
    $key = 'adm'; // Substitua por uma chave secreta segura
    $payload = [
        'login' => $login,
        'iat' => time(),
        'exp' => time() + (60 * 60), // Expiração: 1 hora
    ];
    return base64_encode(json_encode($payload)) . '.' . base64_encode(hash_hmac('sha256', json_encode($payload), $key, true));
}

try {
    $input = json_decode(file_get_contents('php://input'), true);

    $login = $input['username'] ?? '';
    $senha = $input['password'] ?? '';

    if (empty($login) || empty($senha)) {
        http_response_code(400);
        echo json_encode(['erro' => 'Login e senha são obrigatórios.']);
        exit;
    }

    // Consulta o banco para verificar as credenciais
    $pdo = conectarBanco();
    $stmt = $pdo->prepare('SELECT * FROM usuarios_adm WHERE login = :login');
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $senha === $usuario['senha']) {
        // Credenciais válidas
        echo json_encode([
            'jwt' => gerarJWT($usuario['login']),
            'login' => $usuario['login'],
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['erro' => 'Login ou senha inválidos.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro interno: ' . $e->getMessage()]);
}
