<?php
// Inclui o autoload gerado pelo Composer
require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Chave secreta para assinatura do JWT
$key = "exemplo_chave_secreta";
$payload = [
    "iss" => "http://seuprojeto.com", // Emissor
    "aud" => "http://seuprojeto.com", // Público
    "iat" => time(),                 // Emitido em
    "nbf" => time(), // Válido imediatamente
    "data" => [
        "id" => 1,
        "nome" => "Usuário Teste"
    ]
];

// Gerar o token
$jwt = JWT::encode($payload, $key, 'HS256');
echo "Token Gerado: " . $jwt . PHP_EOL;

// Decodificar o token
$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
echo "Token Decodificado: ";
print_r($decoded);
