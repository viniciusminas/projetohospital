<?php
session_start(); // Inicia a sessão

require_once '../src/db.php'; // Conectar ao banco de dados

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso negado. Usuário não autenticado.']);
    exit;
}

// Função para obter dados do dashboard
function obterDadosDashboard($setor = null) {
    $pdo = conectarBanco();
    $sql = "
        SELECT 
            setores.nome AS setor,
            perguntas.texto AS pergunta,
            AVG(avaliacoes.resposta) AS media_resposta,
            COUNT(avaliacoes.id) AS total_respostas
        FROM avaliacoes
        INNER JOIN setores ON avaliacoes.id_setor = setores.id
        INNER JOIN perguntas ON avaliacoes.id_pergunta = perguntas.id
    ";

    // Adiciona filtro por setor se informado
    if ($setor) {
        $sql .= " WHERE setores.nome = :setor";
    }

    $sql .= " GROUP BY setores.nome, perguntas.texto
              ORDER BY setores.nome, perguntas.texto";

    $stmt = $pdo->prepare($sql);

    if ($setor) {
        $stmt->bindParam(':setor', $setor, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');

try {
    // Aceitar parâmetros tanto de GET quanto de POST
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
