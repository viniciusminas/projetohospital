<?php
require_once 'db.php';

function salvarResposta($pergunta_id, $id_setor, $id_dispositivo, $resposta, $feedback_textual = null) {
    try {
        $pdo = conectarBanco(); 
        $sql = "INSERT INTO avaliacoes (id_pergunta, id_setor, id_dispositivo, resposta, feedback_textual) 
                VALUES (:pergunta_id, :id_setor, :id_dispositivo, :resposta, :feedback_textual)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'pergunta_id' => $pergunta_id,
            'id_setor' => $id_setor,
            'id_dispositivo' => $id_dispositivo,
            'resposta' => $resposta,
            'feedback_textual' => $feedback_textual
        ]);

        return true; 
    } catch (PDOException $e) {

        error_log("Erro ao salvar resposta: " . $e->getMessage());  
        return false; 
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recebe os dados em formato JSON e os decodifica para um array PHP
        $dados = json_decode(file_get_contents('php://input'), true);

        // Verifica se todos os campos necessários estão presentes
        if (isset($dados['pergunta_id'], $dados['id_setor'], $dados['id_dispositivo'], $dados['resposta'])) {
            $pergunta_id = (int)$dados['pergunta_id'];  // Garantindo que o ID da pergunta seja um inteiro
            $id_setor = (int)$dados['id_setor'];  // Garantindo que o ID do setor seja um inteiro
            $id_dispositivo = (int)$dados['id_dispositivo'];  // Garantindo que o ID do dispositivo seja um inteiro
            $resposta = (int)$dados['resposta'];  // Garantindo que a resposta seja um inteiro
            $feedback_textual = isset($dados['feedback_textual']) ? trim($dados['feedback_textual']) : null;

            // Chama a função para salvar a resposta no banco
            $salvoComSucesso = salvarResposta($pergunta_id, $id_setor, $id_dispositivo, $resposta, $feedback_textual);

            if ($salvoComSucesso) {
                echo json_encode(['sucesso' => 'Resposta salva com sucesso!']);
            } else {
                echo json_encode(['erro' => 'Erro ao salvar a resposta. Tente novamente mais tarde.']);
            }
        } else {
            // Retorna um erro caso algum campo necessário esteja faltando
            echo json_encode(['erro' => 'Campos obrigatórios não fornecidos']);
        }
    } catch (Exception $e) {
        // Captura qualquer outro erro no processo
        echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
    }
} else {
    // Caso o método não seja POST
    echo json_encode(['erro' => 'Método HTTP inválido. Apenas POST é permitido.']);
}
?>
