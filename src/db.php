<?php
require_once '../config.php';

function conectarBanco() {
    // constantes definidas no config.php
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    
    try {
        // instância PDO para se conectar ao banco de dados
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        return $pdo;
    } catch (PDOException $e) {
        // Finaliza o script se houver erro na conexão
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

// Torna a conexão disponível globalmente
$pdo = conectarBanco();
