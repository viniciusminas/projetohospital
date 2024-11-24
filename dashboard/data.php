<?php 
session_start();

require_once '../src/db.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso negado. Usuário não autenticado.']);
    exit;
}

function obterDadosDashboard($setor = null) {
    $pdo = conectarBanco();
    $sql = "
        SELECT 
            setores.nome AS setor,
            perguntas.texto AS pergunta,
            dispositivos.nome AS dispositivo,
            AVG(avaliacoes.resposta) AS media_resposta,
            COUNT(avaliacoes.id) AS total_respostas
        FROM avaliacoes
        INNER JOIN setores ON avaliacoes.id_setor = setores.id
        INNER JOIN perguntas ON avaliacoes.id_pergunta = perguntas.id
        INNER JOIN dispositivos ON avaliacoes.id_dispositivo = dispositivos.id
    ";

//filtros pelo setor
    if ($setor) {
        $sql .= " WHERE setores.nome = :setor";
    }

    $sql .= " GROUP BY setores.nome, perguntas.texto, dispositivos.nome
              ORDER BY setores.nome, perguntas.texto, dispositivos.nome";

    $stmt = $pdo->prepare($sql);

    if ($setor) {
        $stmt->bindParam(':setor', $setor, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');

try {
    $metodo = $_SERVER['REQUEST_METHOD'];
    $input = $metodo === 'POST' 
        ? json_decode(file_get_contents('php://input'), true) 
        : $_GET;

    $setor = isset($input['setor']) ? urldecode($input['setor']) : null;

    $dados = obterDadosDashboard($setor);

    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro ao carregar dados do dashboard: ' . $e->getMessage()]);
}
?>
