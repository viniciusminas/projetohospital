<?php
require_once '../src/db.php';

$pdo = conectarBanco();

$pdo->beginTransaction();

try {
    $feedback = $_POST['feedback'] ?? null;
    $queryFeedback = $pdo->prepare("INSERT INTO feedbacks (texto) VALUES (:feedback)");
    $queryFeedback->execute([':feedback' => $feedback]);

    foreach ($_POST['respostas'] as $pergunta_id => $nota) {
        $queryResposta = $pdo->prepare("INSERT INTO respostas (pergunta_id, nota) VALUES (:pergunta_id, :nota)");
        $queryResposta->execute([':pergunta_id' => $pergunta_id, ':nota' => $nota]);
    }

    $pdo->commit();
    echo "O Hospital Regional Alto Vale (HRAV) agradece sua resposta e ela é muito importante para nós, pois nos ajuda a melhorar continuamente nossos serviços.";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao salvar avaliação: " . $e->getMessage();
}
?>
