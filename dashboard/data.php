<?php
require_once '../vendor/autoload.php';
require_once '../src/db.php';

function verificarJWT($jwt) {
    $chaveSecreta = 'sua-chave-secreta'; 

    try {
        $dados = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key($chaveSecreta, 'HS256'));
        return $dados;
    } catch (Exception $e) {
        return null;
    }
}

// Permitir ambos os métodos 
$metodo = $_SERVER['REQUEST_METHOD'];
if (!in_array($metodo, ['POST', 'GET'])) {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);
    exit;
}

// Verifica se o JWT está presente no cabeçalho da requisição
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso negado. Token não fornecido.']);
    exit;
}

// Extrai o token do cabeçalho (formato: "Bearer <token>")
list($tipo, $jwt) = explode(' ', $headers['Authorization'], 2);
if ($tipo !== 'Bearer' || !$jwt) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso negado. Token inválido.']);
    exit;
}

// Valida o token JWT
$dadosUsuario = verificarJWT($jwt);
if (!$dadosUsuario) {
    http_response_code(401);
    echo json_encode(['erro' => 'Acesso negado. Token inválido ou expirado.']);
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
