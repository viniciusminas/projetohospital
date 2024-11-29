<?php
require_once '../src/db.php';

$conexao = conectarBanco();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // verifica se o parâmetro 'disp' foi passado na URL
        $disp = isset($_GET['disp']) ? intval($_GET['disp']) : null;

        if ($disp !== null) {
            //busca o setor associado ao dispositivo
            $stmtSetor = $conexao->prepare("SELECT setor_id FROM dispositivos WHERE id = :disp");
            $stmtSetor->bindParam(':disp', $disp, PDO::PARAM_INT);
            $stmtSetor->execute();
            $setor = $stmtSetor->fetch(PDO::FETCH_ASSOC);

            if ($setor) {
                $setorId = $setor['setor_id'];

                //buscar as perguntas do setor associado
                $stmtPerguntas = $conexao->prepare("SELECT perguntas.id AS id_pergunta, perguntas.texto AS texto_pergunta, setores.nome AS setor_nome 
                                                    FROM perguntas
                                                    LEFT JOIN setores ON perguntas.id_setor = setores.id
                                                    WHERE perguntas.id_setor = :setor_id");
                $stmtPerguntas->bindParam(':setor_id', $setorId, PDO::PARAM_INT);
                $stmtPerguntas->execute();

                $perguntas = $stmtPerguntas->fetchAll(PDO::FETCH_ASSOC);

                if (empty($perguntas)) {
                    echo json_encode(['erro' => 'Nenhuma pergunta encontrada para este setor']);
                } else {
                    echo json_encode($perguntas); // retorna as perguntas do setor
                }
            } else {
                echo json_encode(['erro' => 'Dispositivo não encontrado ou sem setor associado']);
            }
        } else {
            echo json_encode(['erro' => 'Parâmetro "disp" ausente na URL']);
        }
    } catch (PDOException $e) {
        echo json_encode(['erro' => 'Erro ao buscar perguntas: ' . $e->getMessage()]);
    }
}
