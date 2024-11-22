<?php
require_once 'db.php';

function listarPerguntasAtivas() {
    $pdo = conectarBanco();
    $sql = "SELECT id AS id_pergunta, texto AS texto_pergunta FROM perguntas WHERE status = TRUE";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');

try {
    $perguntas = listarPerguntasAtivas();
    if ($perguntas) {
        echo json_encode($perguntas);
    } else {
        echo json_encode(['erro' => 'Nenhuma pergunta disponível']);
    }
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro ao buscar perguntas: ' . $e->getMessage()]);
}
?>
