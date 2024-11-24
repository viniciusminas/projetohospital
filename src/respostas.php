<?php
require_once 'db.php';

function obterSetorIdDoDispositivo($id_dispositivo) {
    try {
        $pdo = conectarBanco();
        // Query para obter o setor_id associado ao id_dispositivo
        $sql = "SELECT setor_id FROM dispositivos WHERE id = :id_dispositivo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_dispositivo' => $id_dispositivo]);

        // Verifica se um resultado foi encontrado
        $setor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log para verificar o que foi retornado
        error_log("Resultado da consulta setor_id para dispositivo $id_dispositivo: " . var_export($setor, true));

        if ($setor && isset($setor['setor_id'])) {
            return $setor['setor_id']; // Retorna o setor_id
        }

        // Se não encontrar, loga e retorna null
        error_log("setor_id não encontrado para dispositivo $id_dispositivo.");
        return null;
    } catch (PDOException $e) {
        error_log("Erro ao obter setor_id: " . $e->getMessage());
        return null;
    }
}


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
    error_log("JSON recebido: " . file_get_contents('php://input'));

    try {
        // Recebe os dados em formato JSON
        $dados = json_decode(file_get_contents('php://input'), true);

        if (isset($dados['pergunta_id'], $dados['id_dispositivo'], $dados['resposta'])) {
            $pergunta_id = (int)$dados['pergunta_id'];
            $id_dispositivo = (int)$dados['id_dispositivo'];
            $resposta = (int)$dados['resposta'];
            $feedback_textual = !empty($dados['feedback_textual']) ? trim($dados['feedback_textual']) : null;

            // Obter o setor_id do dispositivo
            $id_setor = obterSetorIdDoDispositivo($id_dispositivo);
            if ($id_setor === null) {
                echo json_encode(['erro' => 'Não foi possível determinar o setor associado ao dispositivo.']);
                exit;
            }

            error_log("Valor recebido para feedback_textual: " . var_export($feedback_textual, true));

            if ($feedback_textual !== null && strlen($feedback_textual) > 1000) {
                echo json_encode(['erro' => 'O feedback textual excede o limite de 1000 caracteres.']);
                exit;
            }

            $salvoComSucesso = salvarResposta($pergunta_id, $id_setor, $id_dispositivo, $resposta, $feedback_textual);

            if ($salvoComSucesso) {
                echo json_encode(['sucesso' => 'Resposta salva com sucesso!']);
            } else {
                echo json_encode(['erro' => 'Erro ao salvar a resposta. Tente novamente mais tarde.']);
            }
        } else {
            echo json_encode(['erro' => 'Campos obrigatórios não fornecidos']);
        }
    } catch (Exception $e) {
        echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['erro' => 'Método HTTP inválido. Apenas POST é permitido.']);
}
?>
