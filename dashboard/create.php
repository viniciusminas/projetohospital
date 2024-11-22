<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if ($username === 'admin' && $password === 'adm') { 
        $token = 'exemploDeToken'; 
        echo json_encode(['token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['erro' => 'Credenciais inválidas']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['erro' => 'Método não permitido']);
exit;
