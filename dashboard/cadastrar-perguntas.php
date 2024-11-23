<?php
require_once '../src/db.php'; // Importa a conexão com o banco de dados

// Conexão com o banco
$conexao = conectarBanco();

// Manipulação de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pergunta'], $_POST['setor'])) {
    $pergunta = $_POST['pergunta'];
    $setor = $_POST['setor'];

    // Inserir pergunta no banco
    try {
        $stmt = $conexao->prepare("INSERT INTO perguntas (texto, status) VALUES (:texto, TRUE)");
        $stmt->execute([':texto' => $pergunta]);

        // Mensagem de sucesso
        echo "<script>alert('Pergunta cadastrada com sucesso!');</script>";
    } catch (PDOException $e) {
        die("Erro ao inserir pergunta: " . $e->getMessage());
    }
}

// Remoção de perguntas
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        $stmt = $conexao->prepare("DELETE FROM perguntas WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "<script>alert('Pergunta removida com sucesso!');</script>";
    } catch (PDOException $e) {
        die("Erro ao excluir pergunta: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Perguntas</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Hospital</a>
    </div>
</nav>
<div class="container py-4">
    <h1 class="text-center mb-4 text-primary">Cadastro de Perguntas</h1>
    <!-- Formulário -->
    <div class="card p-4 mb-4">
        <form id="formPerguntas" method="POST" action="">
            <div class="mb-3">
                <label for="pergunta" class="form-label">Pergunta</label>
                <input type="text" class="form-control" id="pergunta" name="pergunta" required>
            </div>
            <div class="mb-3">
                <label for="setor" class="form-label">Setor</label>
                <select id="setor" name="setor" class="form-select" required>
                    <?php
                    // Preencher opções de setor
                    $stmt = $conexao->query("SELECT id, nome FROM setores");
                    while ($setor = $stmt->fetch()) {
                        echo "<option value='{$setor['id']}'>{$setor['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Pergunta</button>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card p-4">
        <h3 class="mb-3">Perguntas Cadastradas</h3>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Listar perguntas
                $stmt = $conexao->query("SELECT * FROM perguntas");
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['texto']}</td>
                        <td>" . ($row['status'] ? 'Ativo' : 'Inativo') . "</td>
                        <td>
                            <a href='?delete={$row['id']}' class='btn btn-danger btn-sm'>Excluir</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
